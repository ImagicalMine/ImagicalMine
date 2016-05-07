<?php
/**
 * src/pocketmine/OfflinePlayer.php
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
 * @link http://forums.imagicalmine.net/
 *
 *
*/

namespace pocketmine;

use pocketmine\metadata\MetadataValue;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\Plugin;

class OfflinePlayer implements IPlayer
{

    private $name;
    private $server;
    private $namedtag;

    /**
     *
     * @param Server  $server
     * @param string  $name
     */
    public function __construct(Server $server, $name)
    {
        $this->server = $server;
        $this->name = $name;
        if (file_exists($this->server->getDataPath() . "players/" . strtolower($this->getName()) . ".dat")) {
            $this->namedtag = $this->server->getOfflinePlayerData($this->name);
        } else {
            $this->namedtag = null;
        }
    }


    /**
     *
     * @return unknown
     */
    public function isOnline()
    {
        return $this->getPlayer() !== null;
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     *
     * @return unknown
     */
    public function getServer()
    {
        return $this->server;
    }


    /**
     *
     * @return unknown
     */
    public function isOp()
    {
        return $this->server->isOp(strtolower($this->getName()));
    }


    /**
     *
     * @param unknown $value
     */
    public function setOp($value)
    {
        if ($value === $this->isOp()) {
            return;
        }

        if ($value === true) {
            $this->server->addOp(strtolower($this->getName()));
        } else {
            $this->server->removeOp(strtolower($this->getName()));
        }
    }


    /**
     *
     * @return unknown
     */
    public function isBanned()
    {
        return $this->server->getNameBans()->isBanned(strtolower($this->getName()));
    }


    /**
     *
     * @param unknown $value
     */
    public function setBanned($value)
    {
        if ($value === true) {
            $this->server->getNameBans()->addBan($this->getName(), null, null, null);
        } else {
            $this->server->getNameBans()->remove($this->getName());
        }
    }


    /**
     *
     * @return unknown
     */
    public function isWhitelisted()
    {
        return $this->server->isWhitelisted(strtolower($this->getName()));
    }


    /**
     *
     * @param unknown $value
     */
    public function setWhitelisted($value)
    {
        if ($value === true) {
            $this->server->addWhitelist(strtolower($this->getName()));
        } else {
            $this->server->removeWhitelist(strtolower($this->getName()));
        }
    }


    /**
     *
     * @return unknown
     */
    public function getPlayer()
    {
        return $this->server->getPlayerExact($this->getName());
    }


    /**
     *
     * @return unknown
     */
    public function getFirstPlayed()
    {
        return $this->namedtag instanceof CompoundTag ? $this->namedtag["firstPlayed"] : null;
    }


    /**
     *
     * @return unknown
     */
    public function getLastPlayed()
    {
        return $this->namedtag instanceof CompoundTag ? $this->namedtag["lastPlayed"] : null;
    }


    /**
     *
     * @return unknown
     */
    public function hasPlayedBefore()
    {
        return $this->namedtag instanceof CompoundTag;
    }


    /**
     *
     * @param unknown       $metadataKey
     * @param MetadataValue $metadataValue
     */
    public function setMetadata($metadataKey, MetadataValue $metadataValue)
    {
        $this->server->getPlayerMetadata()->setMetadata($this, $metadataKey, $metadataValue);
    }


    /**
     *
     * @param unknown $metadataKey
     * @return unknown
     */
    public function getMetadata($metadataKey)
    {
        return $this->server->getPlayerMetadata()->getMetadata($this, $metadataKey);
    }


    /**
     *
     * @param unknown $metadataKey
     * @return unknown
     */
    public function hasMetadata($metadataKey)
    {
        return $this->server->getPlayerMetadata()->hasMetadata($this, $metadataKey);
    }


    /**
     *
     * @param unknown $metadataKey
     * @param Plugin  $plugin
     */
    public function removeMetadata($metadataKey, Plugin $plugin)
    {
        $this->server->getPlayerMetadata()->removeMetadata($this, $metadataKey, $plugin);
    }
}
