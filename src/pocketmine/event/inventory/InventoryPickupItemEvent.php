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

use pocketmine\entity\Item;
use pocketmine\event\Cancellable;
use pocketmine\inventory\Inventory;

class InventoryPickupItemEvent extends InventoryEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Item */
	private $item;

	/**
	 * @param Inventory $inventory
	 * @param Item      $item
	 */
	public function __construct(Inventory $inventory, Item $item){
		$this->item = $item;
		parent::__construct($inventory);
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

}