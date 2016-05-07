<?php
/**
 * src/pocketmine/tile/BrewingStand.php
 *
 * @package default
 */


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

use pocketmine\inventory\BrewingInventory;
use pocketmine\inventory\BrewingRecipe;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\item\SplashPotion;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\ContainerSetDataPacket;
use pocketmine\Server;

class BrewingStand extends Spawnable implements InventoryHolder, Container, Nameable
{
    /** @var BrewingInventory */
    protected $inventory;

    const MAX_BREW_TIME = 400;

    public static $ingredients = [Item::NETHER_WART, Item::GOLD_NUGGET, Item::GHAST_TEAR, Item::GLOWSTONE_DUST, Item::REDSTONE_DUST, Item::GUNPOWDER, Item::MAGMA_CREAM, Item::BLAZE_POWDER, Item::GOLDEN_CARROT, Item::SPIDER_EYE, Item::FERMENTED_SPIDER_EYE, Item::GLISTERING_MELON, Item::SUGAR, Item::RAW_FISH];

    /**
     *
     * @param FullChunk   $chunk
     * @param CompoundTag $nbt
     */
    public function __construct(FullChunk $chunk, CompoundTag $nbt)
    {
        parent::__construct($chunk, $nbt);
        $this->inventory = new BrewingInventory($this);

        if (!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof ListTag)) {
            $this->namedtag->Items = new ListTag("Items", []);
            $this->namedtag->Items->setTagType(NBT::TAG_Compound);
        }

        for ($i = 0; $i < $this->getSize(); ++$i) {
            $this->inventory->setItem($i, $this->getItem($i));
        }

        if (!isset($this->namedtag->BrewTime) or $this->namedtag["BrewTime"] > self::MAX_BREW_TIME) {
            $this->namedtag->BrewTime = new ShortTag("BrewTime", self::MAX_BREW_TIME);
        }
        if ($this->namedtag["BrewTime"]  < self::MAX_BREW_TIME) {
            $this->scheduleUpdate();
        }
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Chest";
    }


    /**
     *
     * @return unknown
     */
    public function hasName()
    {
        return isset($this->namedtag->CustomName);
    }


    /**
     *
     * @param unknown $str
     */
    public function setName($str)
    {
        if ($str === "") {
            unset($this->namedtag->CustomName);
            return;
        }

        $this->namedtag->CustomName = new StringTag("CustomName", $str);
    }


    /**
     *
     */
    public function close()
    {
        if ($this->closed === false) {
            foreach ($this->getInventory()->getViewers() as $player) {
                $player->removeWindow($this->getInventory());
            }
            parent::close();
        }
    }


    /**
     *
     */
    public function saveNBT()
    {
        $this->namedtag->Items = new ListTag("Items", []);
        $this->namedtag->Items->setTagType(NBT::TAG_Compound);
        for ($index = 0; $index < $this->getSize(); ++$index) {
            $this->setItem($index, $this->inventory->getItem($index));
        }
    }


    /**
     *
     * @return int
     */
    public function getSize()
    {
        return 4;
    }


    /**
     *
     * @param unknown $index
     * @return int
     */
    protected function getSlotIndex($index)
    {
        foreach ($this->namedtag->Items as $i => $slot) {
            if ($slot["Slot"] === $index) {
                return $i;
            }
        }

        return -1;
    }


    /**
     * This method should not be used by plugins, use the Inventory
     *
     *
     * @param int     $index
     * @return Item
     */
    public function getItem($index)
    {
        $i = $this->getSlotIndex($index);
        if ($i < 0) {
            return Item::get(Item::AIR, 0, 0);
        } else {
            return NBT::getItemHelper($this->namedtag->Items[$i]);
        }
    }


    /**
     * This method should not be used by plugins, use the Inventory
     *
     *
     * @param int     $index
     * @param Item    $item
     * @return bool
     */
    public function setItem($index, Item $item)
    {
        $i = $this->getSlotIndex($index);

        $d = NBT::putItemHelper($item, $index);

        if ($item->getId() === Item::AIR or $item->getCount() <= 0) {
            if ($i >= 0) {
                unset($this->namedtag->Items[$i]);
            }
        } elseif ($i < 0) {
            for ($i = 0; $i <= $this->getSize(); ++$i) {
                if (!isset($this->namedtag->Items[$i])) {
                    break;
                }
            }
            $this->namedtag->Items[$i] = $d;
        } else {
            $this->namedtag->Items[$i] = $d;
        }

        return true;
    }


    /**
     *
     * @return BrewingInventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }


    /**
     *
     * @param Item    $ingredient
     * @return unknown
     */
    protected function checkIngredient(Item $ingredient)
    {
        if (in_array($ingredient->getId(), self::$ingredients)) {
            //$this->namedtag->BrewTime = new Short("BrewTime", self::MAX_BREW_TIME);
            return true;
        }

        return false;
    }


    /**
     *
     * @return unknown
     */
    public function onUpdate()
    {
        if ($this->closed === true) {
            return false;
        }

        $this->timings->startTiming();

        $ret = false;

        $ingredient = $this->inventory->getIngredient();
        $potions = $this->inventory->getPotions();
        $canBrew = false;

        foreach ($potions as $pot) {
            if ($pot->getId() === Item::POTION) {
                $canBrew = true;
            }
        }

        if ($this->namedtag["BrewTime"] <= self::MAX_BREW_TIME and $canBrew and $ingredient->getCount() > 0) {
            if (!$this->checkIngredient($ingredient)) {
                $canBrew = false;
            }
        } else {
            $canBrew = false;
        }

        if ($canBrew) {
            $this->namedtag->BrewTime = new ShortTag("BrewTime", $this->namedtag["BrewTime"] - 1);

            if ($this->namedtag["BrewTime"] <= 0) { //20 seconds
                foreach ($this->inventory->getPotions() as $slot => $potion) {
                    $recipe = Server::getInstance()->getCraftingManager()->matchBrewingRecipe($ingredient, $potion);

                    if ($recipe instanceof BrewingRecipe) {
                        $this->inventory->setPotion($slot, $recipe->getResult());
                    } elseif ($ingredient->getId() === Item::GUNPOWDER && $potion->getId() === Item::POTION) {
                        $this->inventory->setPotion($slot, new SplashPotion($potion->getDamage()));
                    }
                }

                $ingredient->count--;
                $this->inventory->setIngredient($ingredient);

                $this->namedtag->BrewTime = new ShortTag("BrewTime", $this->namedtag["BrewTime"] + 400);
            }

            foreach ($this->getInventory()->getViewers() as $player) {
                $windowId = $player->getWindowId($this->getInventory());
                if ($windowId > 0) {
                    $pk = new ContainerSetDataPacket();
                    $pk->windowid = $windowId;
                    $pk->property = 0; //Brewing
                    $pk->value = floor($this->namedtag["BrewTime"]);
                    $player->dataPacket($pk);
                }
            }

            $ret = true;
        } else {
            $this->namedtag->BrewTime = new ShortTag("BrewTime", self::MAX_BREW_TIME);
            $ret = false;
        }

        $this->lastUpdate = microtime(true);

        $this->timings->stopTiming();

        return $ret;
    }


    /**
     *
     * @return unknown
     */
    public function getSpawnCompound()
    {
        $nbt = new CompoundTag("", [
                new StringTag("id", Tile::BREWING_STAND),
                new IntTag("x", (int) $this->x),
                new IntTag("y", (int) $this->y),
                new IntTag("z", (int) $this->z),
                new ShortTag("BrewTime", self::MAX_BREW_TIME)
            ]);

        if ($this->hasName()) {
            $nbt->CustomName = $this->namedtag->CustomName;
        }
        return $nbt;
    }
}
