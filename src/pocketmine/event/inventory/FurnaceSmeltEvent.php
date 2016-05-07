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

namespace pocketmine\event\inventory;

use pocketmine\event\block\BlockEvent;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\tile\Furnace;

class FurnaceSmeltEvent extends BlockEvent implements Cancellable
{
    public static $handlerList = null;

    private $furnace;
    private $source;
    private $result;

    public function __construct(Furnace $furnace, Item $source, Item $result)
    {
        parent::__construct($furnace->getBlock());
        $this->source = clone $source;
        $this->source->setCount(1);
        $this->result = $result;
        $this->furnace = $furnace;
    }

    /**
     * @return Furnace
     */
    public function getFurnace()
    {
        return $this->furnace;
    }

    /**
     * @return Item
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return Item
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param Item $result
     */
    public function setResult(Item $result)
    {
        $this->result = $result;
    }
}
