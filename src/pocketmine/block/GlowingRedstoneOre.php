<?php
/**
 * src/pocketmine/block/GlowingRedstoneOre.php
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

class GlowingRedstoneOre extends RedstoneOre{

	protected $id = self::GLOWING_REDSTONE_ORE;

	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return "Glowing Redstone Ore";
	}


	/**
	 *
	 * @return unknown
	 */
	public function getLightLevel() {
		return 9;
	}


	/**
	 *
	 * @param unknown $type
	 * @return unknown
	 */
	public function onUpdate($type) {
		if ($type === Level::BLOCK_UPDATE_SCHEDULED or $type === Level::BLOCK_UPDATE_RANDOM) {
			$this->getLevel()->setBlock($this, Block::get(Item::REDSTONE_ORE, $this->meta), false, false);

			return Level::BLOCK_UPDATE_WEAK;
		}

		return false;
	}


}
