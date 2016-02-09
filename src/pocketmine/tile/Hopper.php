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

namespace pocketmine\tile;

use pocketmine\inventory\HopperInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\NBT;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;

use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\ContainerSetDataPacket;

class Hopper extends Spawnable implements InventoryHolder, Container, Nameable{

    /** @var HopperInventory */
    protected $inventory;

    public function __construct(FullChunk $chunk, Compound $nbt){
        parent::__construct($chunk, $nbt);
        $this->inventory = new HopperInventory($this);

        if(!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof ListTag)){
            $this->namedtag->Items = new ListTag("Items", []);
            $this->namedtag->Items->setTagType(NBT::TAG_Compound);
        }

        for($i = 0; $i < $this->getSize(); ++$i){
            $this->inventory->setItem($i, $this->getItem($i));
        }

        if(!isset($this->namedtag->BurnTime) or $this->namedtag["TransferCooldown"] < 0){
            $this->namedtag->BurnTime = new IntTag("TransferCooldown", 0);
        }

        if($this->namedtag["TransferCooldown"] > 0){
            $this->scheduleUpdate();
        }
    }

    public function close(){
        if($this->closed === false){
            foreach($this->getInventory()->getViewers() as $player){
                $player->removeWindow($this->getInventory());
            }

            foreach($this->getRealInventory()->getViewers() as $player){
                $player->removeWindow($this->getRealInventory());
            }
            parent::close();
        }
    }

    public function saveNBT(){
        $this->namedtag->Items = new ListTag("Items", []);
        $this->namedtag->Items->setTagType(NBT::TAG_Compound);
        for($index = 0; $index < $this->getSize(); ++$index){
            $this->setItem($index, $this->inventory->getItem($index));
        }
    }

    /**
     * @return int
     */
    public function getSize(){
        return 5;
    }

    /**
     * @param $index
     *
     * @return int
     */
    protected function getSlotIndex($index){
        foreach($this->namedtag->Items as $i => $slot){
            if((int) $slot["Slot"] === (int) $index){
                return (int) $i;
            }
        }

        return -1;
    }

    /**
     * This method should not be used by plugins, use the Inventory
     *
     * @param int $index
     *
     * @return Item
     */
    public function getItem($index){
        $i = $this->getSlotIndex($index);
        if($i < 0){
            return Item::get(Item::AIR, 0, 0);
        }else{
            return NBT::getItemHelper($this->namedtag->Items[$i]);
        }
    }

    /**
     * This method should not be used by plugins, use the Inventory
     *
     * @param int  $index
     * @param Item $item
     *
     * @return bool
     */
    public function setItem($index, Item $item){
        $i = $this->getSlotIndex($index);

        $d = NBT::putItemHelper($item, $index);

        if($item->getId() === Item::AIR or $item->getCount() <= 0){
            if($i >= 0){
                unset($this->namedtag->Items[$i]);
            }
        }elseif($i < 0){
            for($i = 0; $i <= $this->getSize(); ++$i){
                if(!isset($this->namedtag->Items[$i])){
                    break;
                }
            }
            $this->namedtag->Items[$i] = $d;
        }else{
            $this->namedtag->Items[$i] = $d;
        }

        return true;
    }

    /**
     * @return HopperInventory
     */
    public function getInventory(){
        return $this->inventory;
    }

    /**
     * @return HopperInventory
     */
    public function getRealInventory(){
        return $this->inventory;
    }

    public function getSpawnCompound(){
        $nbt = new CompoundTag("", [
            new StringTag("id", Tile::HOPPER),
            new IntTag("x", (int) $this->x),
            new IntTag("y", (int) $this->y),
            new IntTag("z", (int) $this->z),
            new IntTag("TransferCooldown", $this->namedtag["TransferCooldown"]),
        ]);

        if($this->hasName()){
            $nbt->CustomName = $this->namedtag->CustomName;
        }

        return $nbt;
    }

    public function getName(){
        return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Hopper";
    }

    public function hasName(){
        return isset($this->namedtag->CustomName);
    }

    public function setName($str){
        if($str === ""){
            unset($this->namedtag->CustomName);
            return;
        }

        $this->namedtag->CustomName = new StringTag("CustomName", $str);
    }

    public function onUpdate(){
        if($this->closed === true){
            return false;
        }

        $this->timings->startTiming();
        $ret = false;

        if($this->namedtag["TransferCooldow"] > 0){
            $this->namedtag->BurnTime = new IntTag("TransferCooldown", $this->namedtag["TransferCooldown"] - 1);

            if($this->namedtag["TransferCooldown"] <= 0) {
                $this->namedtag->BurnTime = new IntTag("TransferCooldown", 0);
            }
            $ret = true;
        }else{
            $this->namedtag->BurnTime = new IntTag("TransferCooldown", 0);
        }

        foreach($this->getInventory()->getViewers() as $player){
            $windowId = $player->getWindowId($this->getInventory());
            if($windowId > 0){
                $pk = new ContainerSetDataPacket();
                $pk->windowid = $windowId;
                $pk->property = 0; //Smelting
                $pk->value = floor($this->namedtag["CookTime"]);
                $player->dataPacket($pk);

                $pk = new ContainerSetDataPacket();
                $pk->windowid = $windowId;
                $pk->property = 1; //Fire icon
                $pk->value = $this->namedtag["BurnTicks"];
                $player->dataPacket($pk);
            }

        }

        $this->lastUpdate = microtime(true);

        $this->timings->stopTiming();

        return $ret;
    }
}