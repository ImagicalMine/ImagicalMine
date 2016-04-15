<?php
/**
 * src/pocketmine/tile/Dispenser.php
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
 * @link http://forums.imagicalmine.net/
 *
 *
*/
namespace pocketmine\tile;

use pocketmine\inventory\DispenserInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\ContainerSetDataPacket;
//Bug fixed by MagicDroidX, Genisys and Nukkit Project

class Dispenser extends Spawnable implements InventoryHolder, Container, Nameable{
	/** @var DispenserInventory */
	protected $inventory;

	/**
	 *
	 * @param FullChunk   $chunk
	 * @param CompoundTag $nbt
	 */
	public function __construct(FullChunk $chunk, CompoundTag $nbt) {
		parent::__construct($chunk, $nbt);
		$this->inventory = new DispenserInventory($this);

		if (!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof ListTag)) {
			$this->namedtag->Items = new ListTag("Items", []);
			$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		}

		for ($i = 0; $i < $this->getSize(); ++$i) {
			$this->inventory->setItem($i, $this->getItem($i));
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getSize() {
		return 9;
	}


	/**
	 *
	 * @return DispenserInventory
	 */
	public function getInventory() {
		return $this->inventory;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Dispenser";
	}


	/**
	 *
	 * @return unknown
	 */
	public function hasName() {
		return isset($this->namedtag->CustomName);
	}


	/**
	 *
	 * @param unknown $str
	 */
	public function setName($str) {
		if ($str === "") {
			unset($this->namedtag->CustomName);
			return;
		}
		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}


	/**
	 *
	 * @return unknown
	 */
	public function getSpawnCompound() {
		$nbt = new CompoundTag("", [
				new StringTag("id", Tile::DISPENSER),
				new IntTag("x", (int) $this->x),
				new IntTag("y", (int) $this->y),
				new IntTag("z", (int) $this->z),
			]);

		if ($this->hasName()) {
			$nbt->CustomName = $this->namedtag->CustomName;
		}

		return $nbt;
	}


	/**
	 *
	 */
	public function close() {
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
	public function saveNBT() {
		$this->namedtag->Items = new ListTag("Items", []);
		$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		for ($index = 0; $index < $this->getSize(); ++$index) {
			$this->setItem($index, $this->inventory->getItem($index));
		}
	}


	/**
	 *
	 * @param unknown $index
	 * @return unknown
	 */
	protected function getSlotIndex($index) {
		foreach ($this->namedtag->Items as $i => $slot) {
			if ($slot["Slot"] === $index) {
				return $i;
			}
		}
		return -1;
	}


	/**
	 *
	 * @param unknown $index
	 * @return unknown
	 */
	public function getItem($index) {
		$i = $this->getSlotIndex($index);
		if ($i < 0) {
			return Item::get(Item::AIR, 0, 0);
		}else {
			return NBT::getItemHelper($this->namedtag->Items[$i]);
		}
	}


	/**
	 *
	 * @param unknown $index
	 * @param Item    $item
	 * @return unknown
	 */
	public function setItem($index, Item $item) {
		$i = $this->getSlotIndex($index);
		$d = NBT::putItemHelper($item, $index);
		if ($item->getId() === Item::AIR or $item->getCount() <= 0) {
			if ($i >= 0) {
				unset($this->namedtag->Items[$i]);
			}
		}elseif ($i < 0) {
			for ($i = 0; $i <= $this->getSize(); ++$i) {
				if (!isset($this->namedtag->Items[$i])) {
					break;
				}
			}
			$this->namedtag->Items[$i] = $d;
		}else {
			$this->namedtag->Items[$i] = $d;
		}
		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function onUpdate() {
		if ($this->closed === true) {
			return false;
		}
		$this->timings->startTiming();
		foreach ($this->getInventory()->getViewers() as $player) {
			$windowId = $player->getWindowId($this->getInventory());
			if ($windowId > 0) {
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$player->dataPacket($pk);
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$player->dataPacket($pk);
			}
		}
		$this->lastUpdate = microtime(true);
		$this->timings->stopTiming();
		return true;
	}


}
