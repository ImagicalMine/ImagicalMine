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
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when a player places a block
 */
class BlockPlaceEvent extends BlockEvent implements Cancellable
{
    public static $handlerList = null;

    /** @var \pocketmine\Player */
    protected $player;

    /** @var \pocketmine\item\Item */
    protected $item;


    protected $blockReplace;
    protected $blockAgainst;

    public function __construct(Player $player, Block $blockPlace, Block $blockReplace, Block $blockAgainst, Item $item)
    {
        $this->block = $blockPlace;
        $this->blockReplace = $blockReplace;
        $this->blockAgainst = $blockAgainst;
        $this->item = $item;
        $this->player = $player;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Gets the item in hand
     *
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    public function getBlockReplaced()
    {
        return $this->blockReplace;
    }

    public function getBlockAgainst()
    {
        return $this->blockAgainst;
    }
}
