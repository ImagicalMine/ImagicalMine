<?php
/**
 * src/pocketmine/inventory/BrewingRecipe.php
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
namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\UUID;

class BrewingRecipe implements Recipe
{
    private $id = null;

    /** @var Item */
    private $output;

    /** @var  Item|Item[] */
    private $potion;

    /** @var Item */
    private $ingredient;

    /**
     *
     * @param Item    $result
     * @param Item    $ingredient
     * @param Item    $potion
     */
    public function __construct(Item $result, Item $ingredient, Item $potion)
    {
        $this->output = clone $result;
        $this->ingredient = clone $ingredient;
        $this->potion = clone $potion;
    }


    /**
     *
     * @return unknown
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     *
     * @param UUID    $id
     */
    public function setId(UUID $id)
    {
        if ($this->id !== null) {
            throw new \InvalidStateException("Id is already set");
        }

        $this->id = $id;
    }


    /**
     *
     * @param Item    $item
     */
    public function setInput(Item $item)
    {
        $this->ingredient = clone $item;
    }


    /**
     *
     * @return Item
     */
    public function getInput()
    {
        return clone $this->ingredient;
    }


    /**
     *
     * @return unknown
     */
    public function getPotion()
    {
        return clone $this->potion;
    }


    /**
     *
     * @return Item
     */
    public function getResult()
    {
        return clone $this->output;
    }


    /**
     *
     */
    public function registerToCraftingManager()
    {
        Server::getInstance()->getCraftingManager()->registerBrewingRecipe($this);
    }
}
