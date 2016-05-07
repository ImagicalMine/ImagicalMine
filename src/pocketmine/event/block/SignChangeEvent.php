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

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a sign is changed by a player.
 */
class SignChangeEvent extends BlockEvent implements Cancellable
{
    public static $handlerList = null;

    /** @var \pocketmine\Player */
    private $player;
    /** @var string[] */
    private $lines = [];

    /**
     * @param Block    $theBlock
     * @param Player   $thePlayer
     * @param string[] $theLines
     */
    public function __construct(Block $theBlock, Player $thePlayer, array $theLines)
    {
        parent::__construct($theBlock);
        $this->player = $thePlayer;
        $this->lines = $theLines;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return string[]
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param int $index 0-3
     *
     * @return string
     */
    public function getLine($index)
    {
        return $this->lines[$index];
    }

    /**
     * @param int    $index 0-3
     * @param string $line
     */
    public function setLine($index, $line)
    {
        $this->lines[$index] = $line;
    }
}
