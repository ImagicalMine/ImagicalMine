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

namespace pocketmine\event\player;

use pocketmine\Player;

/**
 * Called when a player leaves the server
 */
class PlayerQuitEvent extends PlayerEvent
{
    public static $handlerList = null;

    /** @var string */
    protected $quitMessage;
    protected $autoSave = true;

    public function __construct(Player $player, $quitMessage, $autoSave = true)
    {
        $this->player = $player;
        $this->quitMessage = $quitMessage;
        $this->autoSave = true;
    }

    public function setQuitMessage($quitMessage)
    {
        $this->quitMessage = $quitMessage;
    }

    public function getQuitMessage()
    {
        return $this->quitMessage;
    }

    public function getAutoSave()
    {
        return $this->autoSave;
    }

    public function setAutoSave($value = true)
    {
        $this->autoSave = (bool) $value;
    }
}
