<?php

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