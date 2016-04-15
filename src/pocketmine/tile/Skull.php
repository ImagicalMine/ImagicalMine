<?php
/**
 * src/pocketmine/tile/Skull.php
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
/*
 * THIS IS COPIED FROM THE PLUGIN FlowerPot MADE BY @beito123!!
 * https://github.com/beito123/PocketMine-MP-Plugins/blob/master/test%2FFlowerPot%2Fsrc%2Fbeito%2FFlowerPot%2Fomake%2FSkull.php
 *
 */

namespace pocketmine\tile;

use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\{CompoundTag, IntTag, StringTag};

class Skull extends Spawnable{

	/**
	 *
	 * @param FullChunk   $chunk
	 * @param CompoundTag $nbt
	 */
	public function __construct(FullChunk $chunk, CompoundTag $nbt) {
		if (!isset($nbt->SkullType)) {
			$nbt->SkullType = new StringTag("SkullType", 0);
		}

		parent::__construct($chunk, $nbt);
	}


	/**
	 *
	 */
	public function saveNBT() {
		parent::saveNBT();
		unset($this->namedtag->Creator);
	}



	/**
	 *
	 * @return unknown
	 */
	public function getSpawnCompound() {
		return new CompoundTag("", [
				new StringTag("id", Tile::SKULL),
				$this->namedtag->SkullType,
				new IntTag("x", (int) $this->x),
				new IntTag("y", (int) $this->y),
				new IntTag("z", (int) $this->z),
				$this->namedtag->Rot
			]);
	}



	/**
	 *
	 * @return unknown
	 */
	public function getSkullType() {
		return $this->namedtag["SkullType"];
	}


}
