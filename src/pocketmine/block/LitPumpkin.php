<?php
/**
 * src/pocketmine/block/LitPumpkin.php
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

use pocketmine\entity\IronGolem;
use pocketmine\entity\SnowGolem;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\Player;

class LitPumpkin extends Solid{

	protected $id = self::LIT_PUMPKIN;

	/**
	 *
	 * @return unknown
	 */
	public function getLightLevel() {
		return 15;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHardness() {
		return 1;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getToolType() {
		return Tool::TYPE_AXE;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return "Jack o'Lantern";
	}


	/**
	 *
	 * @param unknown $meta (optional)
	 */
	public function __construct($meta = 0) {
		$this->meta = $meta;
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
		if ($player instanceof Player) {
			$this->meta = ((int) $player->getDirection() + 5) % 4;
		}
		$this->getLevel()->setBlock($block, $this, true, true);

		if ($player != null) {
			// interesting for snow golem AND iron golem (this can be the body)
			$firstBlock = $this->getLevel()->getBlock($block->add(0, -1, 0));
			$secondBlock = $this->getLevel()->getBlock($block->add(0, -2, 0));
			// interesting for iron golem (checking arms and air beyond the feet)
			$armBlock1 = $this->getLevel()->getBlock($block->add(0, -1, -1)); // arm 1
			$armBlock2 = $this->getLevel()->getBlock($block->add(0, -1, 1)); // arm2
			$airBlock1 = $this->getLevel()->getBlock($block->add(0, -2, -1)); // beneath arms
			$airBlock2 = $this->getLevel()->getBlock($block->add(0, -2, 1)); // beneath arms
			// we've to test in all 3d!
			$armBlock3 = $this->getLevel()->getBlock($block->add(-1, -1, 0)); // arm 1
			$armBlock4 = $this->getLevel()->getBlock($block->add(1, -1, 0)); // arm2
			$airBlock3 = $this->getLevel()->getBlock($block->add(-1, -2, 0)); // beneath arms
			$airBlock4 = $this->getLevel()->getBlock($block->add(1, -2, 0)); // beneath arms

			// $player->getServer()->getLogger()->debug("[1st:$firstBlock] [2nd:$secondBlock] [arm1:$armBlock1] [arm2:$armBlock2] [arm3:$armBlock3] [arm4:$armBlock4] [air1:$airBlock1] [air2:$airBlock2] [air3:$airBlock3] [air4:$airBlock4]");


			if ($firstBlock->getId() === Item::SNOW_BLOCK && $secondBlock->getId() === Item::SNOW_BLOCK) { //Block match snowgolem
				$this->getLevel()->setBlock($block, new Air());
				$this->getLevel()->setBlock($firstBlock, new Air());
				$this->getLevel()->setBlock($secondBlock, new Air());

				$snowGolem = new SnowGolem($player->getLevel()->getChunk($this->getX() >> 4, $this->getZ() >> 4), new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
									new DoubleTag("", $this->x),
									new DoubleTag("", $this->y),
									new DoubleTag("", $this->z)
								]),
							"Motion" => new ListTag("Motion", [
									new DoubleTag("", 0),
									new DoubleTag("", 0),
									new DoubleTag("", 0)
								]),
							"Rotation" => new ListTag("Rotation", [
									new FloatTag("", 0),
									new FloatTag("", 0)
								]),
						]));
				$snowGolem->spawnToAll();


			} elseif ($firstBlock->getId() === Item::IRON_BLOCK && $secondBlock->getId() === Item::IRON_BLOCK) { // possible iron golem

				if (($armBlock1->getId() === Item::IRON_BLOCK && $armBlock2->getId() === Item::IRON_BLOCK && $airBlock1->getId() === Item::AIR && $airBlock2->getId() === Item::AIR) ||
					($armBlock3->getId() === Item::IRON_BLOCK && $armBlock4->getId() === Item::IRON_BLOCK && $airBlock3->getId() === Item::AIR && $airBlock4->getId() === Item::AIR)
				) {
					$this->getLevel()->setBlock($block, new Air());
					$this->getLevel()->setBlock($firstBlock, new Air());
					$this->getLevel()->setBlock($secondBlock, new Air());
					$this->getLevel()->setBlock($armBlock1, new Air());
					$this->getLevel()->setBlock($armBlock2, new Air());
					$this->getLevel()->setBlock($armBlock3, new Air());
					$this->getLevel()->setBlock($armBlock4, new Air());


					$ironGolem = new IronGolem($player->getLevel()->getChunk($this->getX() >> 4, $this->getZ() >> 4), new CompoundTag("", [
								"Pos" => new ListTag("Pos", [
										new DoubleTag("", $this->x),
										new DoubleTag("", $this->y),
										new DoubleTag("", $this->z)
									]),
								"Motion" => new ListTag("Motion", [
										new DoubleTag("", 0),
										new DoubleTag("", 0),
										new DoubleTag("", 0)
									]),
								"Rotation" => new ListTag("Rotation", [
										new FloatTag("", 0),
										new FloatTag("", 0)
									]),
							]));
					$ironGolem->spawnToAll();
				}
			}
		}
		return true;
	}


}
