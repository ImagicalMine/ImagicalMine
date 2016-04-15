<?php
/**
 * src/pocketmine/block/UnlitRedstoneRepeater.php
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
use pocketmine\level\Level;
use pocketmine\Player;

class UnlitRedstoneRepeater extends Solid{

	protected $id = self::UNLIT_REDSTONE_REPEATER;

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
	public function isSolid() {
		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return "Redstone Repeater";
	}


	/**
	 *
	 * @return unknown
	 */
	public function canBeActivated() {
		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHardness() {
		return 0.1;
	}


	/**
	 *
	 * @param unknown $type
	 * @return unknown
	 */
	public function onUpdate($type) {
		if ($type === Level::BLOCK_UPDATE_NORMAL) {
			$side = $this->getDamage();
			$faces = [
				0 => 0,
				1 => 1,
				2 => 2,
				3 => 3,
			];
			if ($this->getSide($faces[$side])->isTransparent() === true) {
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}

		}

		return false;
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
		if (!$target->isTransparent()) {
			$faces = [
				0 => 0,
				1 => 1,
				2 => 2,
				3 => 3,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);
			return true;
		}
		return false;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public function getDrops(Item $item) {
		return [[Item::REDSTONE_REPEATER_ITEM, 0, 1]];
	}


}
