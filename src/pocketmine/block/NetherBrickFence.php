<?php
/**
 * src/pocketmine/block/NetherBrickFence.php
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

class NetherBrickFence extends Transparent {

	protected $id = self::NETHER_BRICK_FENCE;

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
	 * @return unknown
	 */
	public function getBreakTime(Item $item) {
		if ($item instanceof Air) {
			//Breaking by hand
			return 10;
		}
		else {
			// Other breaktimes are equal to woodfences.
			return parent::getBreakTime($item);
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHardness() {
		return 2;
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
	 * @return unknown
	 */
	public function getName() {
		return "Nether Brick Fence";
	}



	/**
	 *
	 * @param Block   $block
	 * @return unknown
	 */
	public function canConnect(Block $block) {
		//TODO: activate comments when the NetherBrickFenceGate class has been created.
		return ($block instanceof NetherBrickFence /* or $block instanceof NetherBrickFenceGate */) ? true : $block->isSolid() and !$block->isTransparent();
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public function getDrops(Item $item) {
		if ($item->isPickaxe() >= Tool::TIER_WOODEN) {
			return [
				[$this->id, $this->meta, 1],
			];
		}else {
			return [];
		}
	}


}
