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

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player joins, after things have been correctly set up (you can change anything now)
 */
class PlayerLoginEvent extends PlayerEvent implements Cancellable
{
    public static $handlerList = null;

    /** @var string */
    protected $kickMessage;

    public function __construct(Player $player, $kickMessage)
    {
        $this->player = $player;
        $this->kickMessage = $kickMessage;
    }

    public function setKickMessage($kickMessage)
    {
        $this->kickMessage = $kickMessage;
    }

    public function getKickMessage()
    {
        return $this->kickMessage;
    }
}
