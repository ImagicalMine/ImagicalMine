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

namespace pocketmine\katana;

use pocketmine\utils\Terminal;

/*
 * For now this just reads the config, for actual implementation see Level.php
 */

class CacheEngine extends KatanaModule
{
    public $cacheDisk = true;

    public function init()
    {
        parent::setName("cache");
        parent::writeLoaded();

        if (parent::getKatana()->getProperty("caching.save-to-disk", true)) {
            parent::getKatana()->console->katana("Disk caching " . Terminal::$COLOR_GREEN . "enabled");
            if (!file_exists(parent::getServer()->getDataPath() . "chunk_cache/")) {
                mkdir(parent::getServer()->getDataPath() . "chunk_cache/", 0777);
            }
        } else {
            parent::getKatana()->console->katana("Disk caching " . Terminal::$COLOR_RED . "disabled");
        }

        $this->onFull = intval(parent::getKatana()->getProperty("redirect.on-full", true));
        $this->onThreshold = intval(parent::getKatana()->getProperty("redirect.on-threshold", 18));
        $this->dnsTTL = intval(parent::getKatana()->getProperty("redirect.dns-ttl", 300));
    }
}
