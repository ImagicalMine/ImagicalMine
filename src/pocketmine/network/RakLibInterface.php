<?php
/**
 * src/pocketmine/network/RakLibInterface.php
 *
 * @package default
 */


/*
 *
 *  _                       _           _ __  __ _
 * (_)                     (_)         | |  \/  (_)
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___|
 *                     __/ |
 *                    |___/
 *
 * This program is a third party build by ImagicalMine.
 *
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

namespace pocketmine\network;

use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info as ProtocolInfo;
use pocketmine\network\protocol\Info;
use pocketmine\Player;
use pocketmine\Server;
use raklib\protocol\EncapsulatedPacket;
use raklib\RakLib;
use raklib\server\RakLibServer;
use raklib\server\ServerHandler;
use raklib\server\ServerInstance;

class RakLibInterface implements ServerInstance, AdvancedSourceInterface
{

    /** @var Server */
    private $server;

    /** @var Network */
    private $network;

    /** @var RakLibServer */
    private $rakLib;

    /** @var Player[] */
    private $players = [];

    /** @var string[] */
    private $identifiers;

    /** @var int[] */
    private $identifiersACK = [];

    /** @var ServerHandler */
    private $interface;

    /**
     *
     * @param Server  $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->identifiers = [];

        $this->rakLib = new RakLibServer($this->server->getLogger(), $this->server->getLoader(), $this->server->getPort(), $this->server->getIp() === "" ? "0.0.0.0" : $this->server->getIp());
        $this->interface = new ServerHandler($this->rakLib, $this);
        // @deprecated - for 0.13 compatibility
        for ($i = 0; $i < 256; ++$i) {
            $this->channelCounts[$i] = 0;
        }
    }


    /**
     *
     * @param Network $network
     */
    public function setNetwork(Network $network)
    {
        $this->network = $network;
    }


    /**
     *
     * @return unknown
     */
    public function process()
    {
        $work = false;
        if ($this->interface->handlePacket()) {
            $work = true;
            while ($this->interface->handlePacket()) {
            }
        }

        if ($this->rakLib->isTerminated()) {
            $this->network->unregisterInterface($this);

            throw new \Exception("RakLib Thread crashed");
        }

        return $work;
    }


    /**
     *
     * @param unknown $identifier
     * @param unknown $reason
     */
    public function closeSession($identifier, $reason)
    {
        if (isset($this->players[$identifier])) {
            $player = $this->players[$identifier];
            unset($this->identifiers[spl_object_hash($player)]);
            unset($this->players[$identifier]);
            unset($this->identifiersACK[$identifier]);
            $player->close($player->getLeaveMessage(), $reason);
        }
    }


    /**
     *
     * @param Player  $player
     * @param unknown $reason (optional)
     */
    public function close(Player $player, $reason = "unknown reason")
    {
        if (isset($this->identifiers[$h = spl_object_hash($player)])) {
            unset($this->players[$this->identifiers[$h]]);
            unset($this->identifiersACK[$this->identifiers[$h]]);
            $this->interface->closeSession($this->identifiers[$h], $reason);
            unset($this->identifiers[$h]);
        }
    }


    /**
     *
     */
    public function shutdown()
    {
        $this->interface->shutdown();
    }


    /**
     *
     */
    public function emergencyShutdown()
    {
        $this->interface->emergencyShutdown();
    }


    /**
     *
     * @param unknown $identifier
     * @param unknown $address
     * @param unknown $port
     * @param unknown $clientID
     */
    public function openSession($identifier, $address, $port, $clientID)
    {
        $ev = new PlayerCreationEvent($this, Player::class, Player::class, null, $address, $port);
        $this->server->getPluginManager()->callEvent($ev);
        $class = $ev->getPlayerClass();

        $player = new $class($this, $ev->getClientId(), $ev->getAddress(), $ev->getPort());
        $this->players[$identifier] = $player;
        $this->identifiersACK[$identifier] = 0;
        $this->identifiers[spl_object_hash($player)] = $identifier;
        $this->server->addPlayer($identifier, $player);
    }


    /**
     *
     * @param unknown            $identifier
     * @param EncapsulatedPacket $packet
     * @param unknown            $flags
     */
    public function handleEncapsulated($identifier, EncapsulatedPacket $packet, $flags)
    {
        if (isset($this->players[$identifier])) {
            try {
                if ($packet->buffer !== "") {
                    $pk = $this->getPacket($packet->buffer);
                    if ($pk !== null) {
                        $pk->decode();
                        $this->players[$identifier]->handleDataPacket($pk);
                    }
                }
            } catch (\Throwable $e) {
                if (isset($pk)) {
                    $logger = $this->server->getLogger();
                    $logger->debug("Packet " . get_class($pk) . " 0x" . bin2hex($packet->buffer));
                    $logger->logException($e);
                }

                if (isset($this->players[$identifier])) {
                    $this->interface->blockAddress($this->players[$identifier]->getAddress(), 5);
                }
            }
        }
    }


    /**
     *
     * @param unknown $address
     * @param unknown $timeout (optional)
     */
    public function blockAddress($address, $timeout = 300)
    {
        $this->interface->blockAddress($address, $timeout);
    }


    /**
     *
     * @param unknown $address
     * @param unknown $port
     * @param unknown $payload
     */
    public function handleRaw($address, $port, $payload)
    {
        $this->server->handlePacket($address, $port, $payload);
    }


    /**
     *
     * @param unknown $address
     * @param unknown $port
     * @param unknown $payload
     */
    public function sendRawPacket($address, $port, $payload)
    {
        $this->interface->sendRaw($address, $port, $payload);
    }


    /**
     *
     * @param unknown $identifier
     * @param unknown $identifierACK
     */
    public function notifyACK($identifier, $identifierACK)
    {
    }


    /**
     *
     * @param unknown $name
     */
    public function setName($name)
    {
        $info = $this->server->getQueryInformation();

        $this->interface->sendOption("name",
            "MCPE;".addcslashes($name, ";") .";".
            Info::CURRENT_PROTOCOL.";".
            \pocketmine\MINECRAFT_VERSION_NETWORK.";".
            $info->getPlayerCount().";".
            $info->getMaxPlayerCount()
        );
    }


    /**
     *
     * @param unknown $name
     */
    public function setPortCheck($name)
    {
        $this->interface->sendOption("portChecking", (bool) $name);
    }


    /**
     *
     * @param unknown $name
     * @param unknown $value
     */
    public function handleOption($name, $value)
    {
        if ($name === "bandwidth") {
            $v = unserialize($value);
            $this->network->addStatistics($v["up"], $v["down"]);
        }
    }


    /**
     *
     * @param Player     $player
     * @param DataPacket $packet
     * @param unknown    $needACK   (optional)
     * @param unknown    $immediate (optional)
     * @return unknown
     */
    public function putPacket(Player $player, DataPacket $packet, $needACK = false, $immediate = false)
    {
        if (isset($this->identifiers[$h = spl_object_hash($player)])) {
            $identifier = $this->identifiers[$h];
            $pk = null;
            if (!$packet->isEncoded) {
                $packet->encode();
            } elseif (!$needACK) {
                if (!isset($packet->__encapsulatedPacket)) {
                    $packet->__encapsulatedPacket = new CachedEncapsulatedPacket;
                    $packet->__encapsulatedPacket->identifierACK = null;
                    //@todo backwart compatible - on 0.13 was
                    //$packet->__encapsulatedPacket->buffer = $packet->buffer;
                    $packet->__encapsulatedPacket->buffer = chr(0x8e) . $packet->buffer;
                    if ($packet->getChannel() !== 0) {
                        $packet->__encapsulatedPacket->reliability = 3;
                        $packet->__encapsulatedPacket->orderChannel = $packet->getChannel();
                        $packet->__encapsulatedPacket->orderIndex = 0;
                    } else {
                        $packet->__encapsulatedPacket->reliability = 2;
                    }
                }
                $pk = $packet->__encapsulatedPacket;
            }

            if (!$immediate and !$needACK and $packet::NETWORK_ID !== ProtocolInfo::BATCH_PACKET
                and Network::$BATCH_THRESHOLD >= 0
                and strlen($packet->buffer) >= Network::$BATCH_THRESHOLD) {
                //@todo backwart compatible - on 0.13 was
                //$this->server->batchPackets([$player], [$packet], true, $packet->getChannel());
                $this->server->batchPackets([$player], [$packet], true);
                return null;
            }

            if ($pk === null) {
                $pk = new EncapsulatedPacket();
                //@todo backwart compatible - on 0.13 was
                //$pk->buffer = $packet->buffer;
                $pk->buffer = chr(0x8e) . $packet->buffer;
                if ($packet->getChannel() !== 0) {
                    $packet->reliability = 3;
                    $packet->orderChannel = $packet->getChannel();
                    $packet->orderIndex = 0;
                } else {
                    $packet->reliability = 2;
                }
                if ($needACK === true) {
                    $pk->identifierACK = $this->identifiersACK[$identifier]++;
                }
            }

            $this->interface->sendEncapsulated($identifier, $pk, ($needACK === true ? RakLib::FLAG_NEED_ACK : 0) | ($immediate === true ? RakLib::PRIORITY_IMMEDIATE : RakLib::PRIORITY_NORMAL));

            return $pk->identifierACK;
        }

        return null;
    }


    /**
     *
     * @param unknown $buffer
     * @return unknown
     */
    private function getPacket($buffer)
    {
        //@todo backwart compatible - on 0.13 was
        //$pid = ord($buffer{0});
        $pid = ord($buffer{1});

        if (($data = $this->network->getPacket($pid)) === null) {
            return null;
        }
        //@todo backwart compatible - on 0.13 was
        //$data->setBuffer($buffer, 1);
        $data->setBuffer($buffer, 2);

        return $data;
    }
}
