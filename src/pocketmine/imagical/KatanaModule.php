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
 *  Defines ability for modules to communicate with the parent
 */

namespace pocketmine\katana;

use pocketmine\utils\Terminal;

/*
 * Abstraction layer for modules that implement Katana's modified functionality relative to PocketMine
 */

class KatanaModule {
	/** @var Katana */
	private $katana;

	private $name = "";
	public $needsTicking = false;

	public function __construct($katana) {
		$this->katana = $katana;
	}

	public function getKatana() {
		return $this->katana;
	}

	public function getServer() {
		return $this->katana->getServer();
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function writeLoaded() {
		$this->getKatana()->console->katana("Loaded " .Terminal::$COLOR_AQUA . $this->name . Terminal::$COLOR_GRAY . " module");
	}
}