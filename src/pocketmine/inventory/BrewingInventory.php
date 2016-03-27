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
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/
namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\tile\BrewingStand;

class BrewingInventory extends ContainerInventory{
    public function __construct(BrewingStand $tile){
        parent::__construct($tile, InventoryType::get(InventoryType::BREWING_STAND));
    }

    /**
     * @return BrewingStand
     */
    public function getHolder(){
        return $this->holder;
    }

    public function getIngredient(){
        return $this->getItem(0);
    }

    public function setIngredient(Item $item){
        $this->setItem(0, $item);
    }

    /**
     * @return Item[]
     */
    public function getPotions(){
        return [1 => $this->getItem(1), 2 => $this->getItem(2), 3 => $this->getItem(3)];
    }

    public function setPotion($slot, Item $potion){
        ($slot < 1 || $slot > 3) ? false : $this->setItem($slot, $potion);
    }

    public function onSlotChange($index, $before){
        parent::onSlotChange($index, $before);

        $this->getHolder()->scheduleUpdate();
    }
}