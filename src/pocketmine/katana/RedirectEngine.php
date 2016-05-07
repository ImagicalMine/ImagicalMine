<?php

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
 * ImagicalMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

/**
 * ImagicalMine is the Minecraft: PE multiplayer server software
 * Homepage: http://imagicalmine.imagicalcorp.ml/
 */

namespace pocketmine\imagical;

/*
* Sends a packet to redirect a player if the current server can't handle them.
*/

class RedirectEngine extends KatanaModule
{
    public $onFull = false;
    public $onThreshold = 18;

    private $dns;
    public $ip = "";
    public $port = 19132;

    private $dnsTTL = 0;
    private $lastDNSRefresh = 0;

    public function init()
    {
        parent::setName("redirect");
        parent::writeLoaded();

        $destination = parent::getKatana()->getProperty("redirect.destination", "play.myserver.com:19132");
        if (count($targets = explode(":", $destination)) !== 2) {
            parent::getKatana()->console->katana("Invalid redirect destination (" . $destination . ").", "warning");
            $targets = ["play.myserver.com", "19132"];
        }

        if (filter_var($targets[0], FILTER_VALIDATE_IP)) {
            $this->ip = $targets[0];
        } else {
            $this->dns = $targets[0];
        }

        if (intval($targets[1]) === 0) {
            parent::getKatana()->console->katana("Invalid port (" . $targets[1] . ").", "warning");
        } else {
            $this->port = intval($targets[1]);
        }

        $this->onFull = intval(parent::getKatana()->getProperty("redirect.on-full", true));
        $this->onThreshold = intval(parent::getKatana()->getProperty("redirect.on-threshold", 18));
        $this->dnsTTL = intval(parent::getKatana()->getProperty("redirect.dns-ttl", 300));
    }

    public function getIP()
    {
        if (time() > ($this->lastDNSRefresh + $this->dnsTTL)) {
            parent::getKatana()->console->katana("Refreshed DNS for player redirection", "debug");
            $this->ip = gethostbyname($this->dns);
            $this->lastDNSRefresh = time();
        }

        return $this->ip;
    }

    public function getPort()
    {
        return $this->port;
    }
}
