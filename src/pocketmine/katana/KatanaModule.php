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

use pocketmine\utils\Terminal;

/*
 * Abstraction layer for modules that implement Katana's modified functionality relative to PocketMine
 */

class KatanaModule
{
    /** @var Katana */
    private $katana;

    private $name = "";
    public $needsTicking = false;

    public function __construct($katana)
    {
        $this->katana = $katana;
    }

    public function getKatana()
    {
        return $this->katana;
    }

    public function getServer()
    {
        return $this->katana->getServer();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function writeLoaded()
    {
        $this->getKatana()->console->katana("Loaded " .Terminal::$COLOR_AQUA . $this->name . Terminal::$COLOR_GRAY . " module");
    }
}
