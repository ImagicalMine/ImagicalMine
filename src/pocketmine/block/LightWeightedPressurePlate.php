<?php
/**
 * src/pocketmine/block/LightWeightedPressurePlate.php
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

use pocketmine\item\Tool;
use pocketmine\item\Item;

class LightWeightedPressurePlate extends WoodenPressurePlate{

	protected $id = self::LIGHT_WEIGHTED_PRESSURE_PLATE;

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
	public function getToolType() {
		return Tool::TYPE_PICKAXE;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return "Light Weighted Pressure Plate";
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHardness() {
		return 0.5;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public function getDrops(Item $item) {
		if ($item->isPickaxe()) {
			return [
				[$this->id, 0, 1]
			];
		}
		return [];
	}


}
