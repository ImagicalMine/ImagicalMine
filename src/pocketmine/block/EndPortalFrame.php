<?php
/**
 * src/pocketmine/block/EndPortalFrame.php
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
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;

class EndPortalFrame extends Solid{

	protected $id = self::END_PORTAL_FRAME;

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
	public function getLightLevel() {
		return 1;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return "End Portal Frame";
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHardness() {
		return -1;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getResistance() {
		return 18000000;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public function isBreakable(Item $item) {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	protected function recalculateBoundingBox() {
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + (($this->getDamage() & 0x04) > 0 ? 1 : 0.8125),
			$this->z + 1
		);
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
			0 => 3,
			1 => 2,
			2 => 1,
			3 => 0
		];
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] & 0x01;
		$this->getLevel()->setBlock($block, $this, true, true);

		return true;
	}


	//TODO Implement ender portal when implemented on client
}
