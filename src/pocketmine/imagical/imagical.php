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
 *  This file contains configuration and code relating to Katana's modified functionality.
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

		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . " ");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_BLUE . ",_._._._._._._._._" . Terminal::$COLOR_DARK_BLUE . "|" . Terminal::$COLOR_GRAY . "_________________________________________________,");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_BLUE . "|_|_|_|_|_|_|_|_|_" . Terminal::$COLOR_DARK_BLUE . "|" . Terminal::$COLOR_GRAY . "________________________________________________/");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "     _           " . Terminal::$COLOR_DARK_BLUE . " l");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "    | | ____ _| |_ __ _ _ __   __ _");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "    | |/ / _` | __/ _` | '_ \\ / _` |    " . Terminal::$COLOR_AQUA . "MCPE " . $this->server->getVersion());
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "    |   < (_| | || (_| | | | | (_| |    " . Terminal::$COLOR_AQUA . "Katana " . $this->server->getPocketMineVersion());
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "    |_|\\_\\__,_|\\__\\__,_|_| |_|\\__,_|");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . " ");
		$this->getServer()->getLogger()->info(Terminal::$COLOR_GOLD . "Katana " . Terminal::$COLOR_WHITE . "is a fork of " . Terminal::$COLOR_AQUA . "PocketMine-MP" . Terminal::$COLOR_WHITE . ", distributed under the LGPL licence");

		$this->initConfig();
		$this->initLogger();
		$this->initModules();
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
		if(!file_exists($this->server->getDataPath() . "katana.yml")){
			$content = file_get_contents($this->server->getDataPath() . "src/pocketmine/resources/katana.yml");
			@file_put_contents($this->server->getDataPath() . "katana.yml", $content);
		}
		$this->properties = new Config($this->server->getDataPath() . "katana.yml", Config::YAML, []);
	}

	public function initLogger() {
		$this->server->getLogger()->setSettings([
			"level" => $this->getProperty("console.show-log-level"),
			"thread" => $this->getProperty("console.show-thread"),
			"timestamps" => $this->getProperty("console.show-timestamps")
		]);
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
