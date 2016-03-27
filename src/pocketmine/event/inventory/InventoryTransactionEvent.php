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

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\inventory\TransactionGroup;

/**
 * Called when there is a transaction between two Inventory objects.
 * The source of this can be a Player, entities, mobs, or even hoppers in the future!
 */
class InventoryTransactionEvent extends Event implements Cancellable{
	public static $handlerList = null;

	/** @var TransactionGroup */
	private $ts;

	/**
	 * @param TransactionGroup $ts
	 */
	public function __construct(TransactionGroup $ts){
		$this->ts = $ts;
	}

	/**
	 * @return TransactionGroup
	 */
	public function getTransaction(){
		return $this->ts;
	}

}
