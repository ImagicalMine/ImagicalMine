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

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\Terminal;


/*
 * Handles Katana's modified functionality and provides an abstraction layer for its modules.
 */

class Katana {
	/** @var Server */
	private $server;

	/** @var Config */
	private $properties;

	private $propertyCache = [];

	/** @var bool|RedirectEngine */
	public $redirect;

	/** @var CacheEngine */
	public $cache;

	/** @var Console */
	public $console;

	private $modules = [];

	public function __construct($server) {
		$this->server = $server;
		
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "-------------------------------------------------------------------------------------------");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . "  _                       _           _" . Terminal::$COLOR_AQUA . " __  __ _ " . Terminal::$COLOR_GOLD . "                                        |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . " (_)                     (_)         | |" . Terminal::$COLOR_AQUA . "  \/  (_) " . Terminal::$COLOR_GOLD . "                                       |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . "  _ _ __ ___   __ _  __ _ _  ___ __ _| |" . Terminal::$COLOR_AQUA . " \  / |_ _ __   ___ " . Terminal::$COLOR_GOLD . "                             |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . " | | '_ ` _ \ / _` |/ _` | |/ __/ _` | |" . Terminal::$COLOR_AQUA . " |\/| | | '_ \ / _ \ "   . Terminal::$COLOR_RED . "     ImagicalMine " . $this->server->getPocketMineVersion() . Terminal::$COLOR_GOLD . "    |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . " | | | | | | | (_| | (_| | | (_| (_| | |" . Terminal::$COLOR_AQUA . " |  | | | | | |  __/ " . Terminal::$COLOR_GOLD . "                            |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . " |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|" . Terminal::$COLOR_AQUA . "_|  |_|_|_| |_|\___| " . Terminal::$COLOR_WHITE . "   for MCPE " . $this->server->getVersion() . Terminal::$COLOR_GOLD . "   |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . "                     __/ |" . Terminal::$COLOR_GOLD . "                                                               |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "|" . Terminal::$COLOR_PURPLE . "                    |___/" . Terminal::$COLOR_GOLD . "                                                                |");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "------------------------------------------------------------------------------------------- ");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_PURPLE . "                                                                                                                        ");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_PURPLE . "Imagical" . Terminal::$COLOR_AQUA . "Mine " . Terminal::$COLOR_WHITE . "is a third-party build of " . Terminal::$COLOR_AQUA . "PocketMine-MP" . Terminal::$COLOR_WHITE . ", distributed under the LGPL licence");

		$this->initConfig();
	}

	public function getServer() {
		return $this->server;
	}

	public function getProperty($variable, $defaultValue = null){
		if(!array_key_exists($variable, $this->propertyCache)){
			$v = getopt("", ["$variable::"]);
			if(isset($v[$variable])){
				$this->propertyCache[$variable] = $v[$variable];
			}else{
				$this->propertyCache[$variable] = $this->properties->getNested($variable);
			}
		}

		return $this->propertyCache[$variable] === null ? $defaultValue : $this->propertyCache[$variable];
	}

	public function initConfig() {
		if(!file_exists($this->server->getDataPath() . "imagicalmine.yml")){
			$content = file_get_contents($this->server->getDataPath() . "src/pocketmine/resources/imagicalmine.yml");
			@file_put_contents($this->server->getDataPath() . "imagicalmine.yml", $content);
		}
		$this->properties = new Config($this->server->getDataPath() . "imagicalmine.yml", Config::YAML, []);
	}

	public function initModules() {
		//Load console first, as other modules use it.
		$this->console = new Console($this);
		$this->console->init();
		$this->modules[] = $this->console;

		if($this->getProperty("redirect.enable")) {
			$this->redirect = new RedirectEngine($this);
			$this->redirect->init();
			$this->modules[] = $this->redirect;
		}
    if($this->getProperty('caching.enable')){
        $this->cache = new CacheEngine($this);
        $this->cache->init();
        $this->modules[] = $this->cache;
    }
	}

	public function tickModules() {

	}
}
