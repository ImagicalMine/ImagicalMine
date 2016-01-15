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
 *  This file contains code for the player redirection engine.
 */

namespace pocketmine\katana;

use pocketmine\utils\Terminal;

/*
 * Replacement logger designed for readability instead of verbosity
 */

class Console extends KatanaModule {
	public function init() {
		parent::setName("console");
		parent::writeLoaded();
	}

	public function system($text, $level = "info") {
		parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_AQUA . "system> " . Terminal::$COLOR_GRAY . $text);
	}

	public function game($text, $level = "info") {
		parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_LIGHT_PURPLE . "game> " . Terminal::$COLOR_GRAY . $text);
	}

	public function plugin($text, $level = "info") {
		parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_GREEN . "plugin> " . Terminal::$COLOR_GRAY . $text);
	}

	public function katana($text, $level = "info") {
		parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_GOLD . "katana> " . Terminal::$COLOR_GRAY . $text);
	}
}