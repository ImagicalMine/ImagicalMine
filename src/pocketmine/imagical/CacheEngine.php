<?php
/*
 *   _         _
 *  | | ____ _| |_ __ _ _ __   __ _
 *  | |/ / _` | __/ _` | '_ \ / _` |
 *  |   < (_| | || (_| | | | | (_| |
 *  |_|\_\__,_|\__\__,_|_| |_|\__,_|
 *
 *  http://github.com/williamtdr/Katana
 *
 *  This file contains code for the caching system.
 */

namespace pocketmine\katana;

use pocketmine\utils\Terminal;

/*
 * For now this just reads the config, for actual implementation see Level.php
 */

class CacheEngine extends KatanaModule {
	public $cacheDisk = true;

	public function init() {
		parent::setName("cache");
		parent::writeLoaded();

		if(parent::getKatana()->getProperty("caching.save-to-disk", true)) {
			parent::getKatana()->console->katana("Disk caching " . Terminal::$COLOR_GREEN . "enabled");
			if(!file_exists(parent::getServer()->getDataPath() . "chunk_cache/")) mkdir(parent::getServer()->getDataPath() . "chunk_cache/", 0777);
		} else {
			parent::getKatana()->console->katana("Disk caching " . Terminal::$COLOR_RED . "disabled");
		}

		$this->onFull = intval(parent::getKatana()->getProperty("redirect.on-full", true));
		$this->onThreshold = intval(parent::getKatana()->getProperty("redirect.on-threshold", 18));
		$this->dnsTTL = intval(parent::getKatana()->getProperty("redirect.dns-ttl", 300));
	}
}