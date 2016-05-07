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

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;

class EntityArmorChangeEvent extends EntityEvent implements Cancellable
{
    public static $handlerList = null;

    private $oldItem;
    private $newItem;
    private $slot;

    public function __construct(Entity $entity, Item $oldItem, Item $newItem, $slot)
    {
        $this->entity = $entity;
        $this->oldItem = $oldItem;
        $this->newItem = $newItem;
        $this->slot = (int) $slot;
    }

    public function getSlot()
    {
        return $this->slot;
    }

    public function getNewItem()
    {
        return $this->newItem;
    }

    public function setNewItem(Item $item)
    {
        $this->newItem = $item;
    }

    public function getOldItem()
    {
        return $this->oldItem;
    }
}
