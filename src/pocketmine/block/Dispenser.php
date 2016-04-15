<?php
/**
 * src/pocketmine/block/Dispenser.php
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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\tile\Dispenser as TileDispenser;
use pocketmine\item\Bucket;


class Dispenser extends Solid implements RedstoneConsumer
{

	protected $id = self::DISPENSER;

	/**
	 *
	 * @param unknown $meta (optional)
	 */
	public function __construct($meta = 0) {
		$this->meta = $meta;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return "Dispenser";
	}


	/**
	 *
	 * @return unknown
	 */
	public function canBeActivated() {//At the moment disable, prevent servers crash (For devs, put true if you want check error)
		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHardness() {
		return 3.5;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getToolType() {
		return Tool::TYPE_PICKAXE;
	}


	/**
	 *
	 * @param Item    $item
	 * @param Block   $block
	 * @param Block   $target
	 * @param unknown $face
	 * @param unknown $fx
	 * @param unknown $fy
	 * @param unknown $fz
	 * @param Player  $player (optional)
	 * @return unknown
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) {
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3
		];

		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
		$this->getLevel()->setBlock($block, $this, true, true);

		$nbt = new CompoundTag("", [
				new ListTag("Items", []),
				new StringTag("id", Tile::DISPENSER),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z)
			]);
		$nbt->Items->setTagType(NBT::TAG_Compound);

		if ($item->hasCustomName()) {
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}

		if ($item->hasCustomBlockData()) {
			foreach ($item->getCustomBlockData() as $key => $v) {
				$nbt->{$key} = $v;
			}
		}

		Tile::createTile("Dispenser", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getDirection() {
		return $this->meta & 0x07;
	}


	/**
	 *
	 * @param Item    $item
	 * @param Player  $player (optional)
	 * @return unknown
	 */
	public function onActivate(Item $item, Player $player = null) {
		if ($player instanceof Player) {
			$t = $this->getLevel()->getTile($this);
			$dispenser = null;
			if ($t instanceof TileDispenser) {
				$dispenser = $t;
			}else {
				$nbt = new CompoundTag("", [
						new ListTag("Items", []),
						new StringTag("id", Tile::DISPENSER),
						new IntTag("x", $this->x),
						new IntTag("y", $this->y),
						new IntTag("z", $this->z)
					]);
				$nbt->Items->setTagType(NBT::TAG_Compound);
				$dispenser = Tile::createTile("Dispenser", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}

			if (isset($dispenser->namedtag->Lock) and $dispenser->namedtag->Lock instanceof StringTag) {
				if ($dispenser->namedtag->Lock->getValue() !== $item->getCustomName()) {
					return true;
				}
			}

			$player->addWindow($dispenser->getInventory());
		}
		return true;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public function getDrops(Item $item) {
		$drops = [];
		if ($item->isPickaxe() >= Tool::TIER_WOODEN) {
			$drops[] = [$this->id, 3, 1];
		}
		return $drops;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isPowered() {
		return ($this->meta & 0x08) === 0x08;
	}


	/**
	 * Toggles the current state of this plate
	 */
	public function togglePowered() {
		$this->meta ^= 0x08;
		$this->isPowered()?$this->power=15:$this->power=0;
		$this->getLevel()->setBlock($this, $this, true, true);
	}


	/**
	 *
	 * @param unknown $type
	 * @param unknown $power
	 */
	public function onRedstoneUpdate($type, $power) {

		if (!$this->isPowered() and $this->isCharged()) {
			// Power Up
			$this->togglePowered();

			// Check if Empty
			$dispenserTile = $this->getLevel()->getTile($this);
			$inventory = $dispenserTile->getInventory();
			$filledSlots = [];

			for ($i = 0; $i < $inventory->getSize(); ++$i) {
				if (!($inventory->getItem($i)->getId() === Item::AIR or $inventory->getItem($i)->getCount() <= 0)) {
					$filledSlots[] = $i;
				}
			}

			if (count($filledSlots) === 0) {
				// Dispenser is empty so make sound of being empty - Need to work out the sound emmited
				//$this->getLevel()->addSound(new ClickSound($this, 500));
				//Server::getInstance()->getLogger()->debug("!EMPTY!");
			} else {
				// Not empty so need to randomly deploy an item
				$chosenSlot = $filledSlots[mt_rand(0, count($filledSlots)-1)];

				// Get Item from Inventory
				$item = $inventory->getItem($chosenSlot);


				// Depending on Item Type do different actions
				if ($item instanceof Bucket) {
					if ($item->getDamage() === 0) {
						// Bucket Empty

						// Update Inventory Count
						$item->setCount($item->getCount() - 1);
						$inventory->setItem($chosenSlot, $item);

						if ($this->getSide($this->getDirection()) instanceof StillWater) {
							// Water on Side, so fill bucket
							// Remove Water
							$this->getLevel()->setBlock($this->getSide($this->getDirection()), new Air());

							// Create Bucket
							$filledBucket = Item::get(Item::BUCKET, Block::WATER, 1);
							if ($inventory->canAddItem($filledBucket)) {
								$inventory->addItem($filledBucket);
							} else {
								$this->getLevel()->dropItem($this->getSide($this->getDirection()), $filledBucket);
							}

						} elseif ($this->getSide($this->getDirection()) instanceof StillLava) {
							// Lava on Side, so fill bucket
							// Remove Lava
							$this->getLevel()->setBlock($this->getSide($this->getDirection()), new Air());

							// Create Bucket
							$filledBucket = Item::get(Item::BUCKET, Block::LAVA, 1);
							if ($inventory->canAddItem($filledBucket)) {
								$inventory->addItem($filledBucket);
							} else {
								$this->getLevel()->dropItem($this->getSide($this->getDirection()), $filledBucket);
							}
						} else {
							// Drop Item
							$item->setCount(1);
							$this->getLevel()->dropItem($this->getSide($this->getDirection()), $item);
						}
					} elseif (Block::get($item->getDamage()) instanceof Water) {
						// Water Bucket
						$this->getLevel()->setBlock($this->getSide($this->getDirection()), new StillWater());
						$inventory->clear($chosenSlot);
						$inventory->addItem(Item::get(Item::BUCKET));
					} elseif (Block::get($item->getDamage()) instanceof Lava) {
						// Lava Bucket
						$this->getLevel()->setBlock($this->getSide($this->getDirection()), new StillLava());
						$inventory->clear($chosenSlot);
						$inventory->addItem(Item::get(Item::BUCKET));
					}
				} else {
					// Update Inventory Count
					$item->setCount($item->getCount() - 1);
					$inventory->setItem($chosenSlot, $item);
					// Drop Item
					$item->setCount(1);
					$this->getLevel()->dropItem($this->getSide($this->getDirection()), $item);
				}

			}
		} else if ($this->isPowered() and !$this->isCharged()) {
			// Power Down

			$this->togglePowered();
		}
	}


}
