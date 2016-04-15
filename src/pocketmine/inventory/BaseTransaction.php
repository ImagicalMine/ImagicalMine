<?php
/**
 * src/pocketmine/inventory/BaseTransaction.php
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

class BaseTransaction implements Transaction{
	/** @var Inventory */
	protected $inventory;
	/** @var int */
	protected $slot;
	/** @var Item */
	protected $sourceItem;
	/** @var Item */
	protected $targetItem;
	/** @var float */
	protected $creationTime;

	/**
	 *
	 * @param Inventory $inventory
	 * @param int       $slot
	 * @param Item      $sourceItem
	 * @param Item      $targetItem
	 */
	public function __construct(Inventory $inventory, $slot, Item $sourceItem, Item $targetItem) {
		$this->inventory = $inventory;
		$this->slot = (int) $slot;
		$this->sourceItem = clone $sourceItem;
		$this->targetItem = clone $targetItem;
		$this->creationTime = microtime(true);
	}


	/**
	 *
	 * @return unknown
	 */
	public function getCreationTime() {
		return $this->creationTime;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getInventory() {
		return $this->inventory;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getSlot() {
		return $this->slot;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getSourceItem() {
		return clone $this->sourceItem;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getTargetItem() {
		return clone $this->targetItem;
	}


}
