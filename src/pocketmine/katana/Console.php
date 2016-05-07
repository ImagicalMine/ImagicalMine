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
 * Replacement logger designed for readability instead of verbosity
 */

class Console extends KatanaModule
{
    public function init()
    {
        parent::setName("console");
        parent::writeLoaded();
    }

    public function system($text, $level = "info")
    {
        parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_AQUA . "system> " . Terminal::$COLOR_GRAY . $text);
    }

    public function game($text, $level = "info")
    {
        parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_LIGHT_PURPLE . "game> " . Terminal::$COLOR_GRAY . $text);
    }

    public function plugin($text, $level = "info")
    {
        parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_GREEN . "plugin> " . Terminal::$COLOR_GRAY . $text);
    }

    public function katana($text, $level = "info")
    {
        parent::getServer()->getLogger()->{$level}(Terminal::$COLOR_GOLD . "imagicalmine> " . Terminal::$COLOR_GRAY . $text);
    }
}
