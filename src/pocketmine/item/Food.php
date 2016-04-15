<?php
/**
 * src/pocketmine/item/Food.php
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

namespace pocketmine\item;

use pocketmine\Player;
abstract class Food extends Item{
	public $saturation = 0;

	/**
	 *
	 * @return unknown
	 */
	public function getSaturation() {
		return $this->saturation;
	}


	/**
	 *         saturation (float) $float
	 *
	 * @param unknown
	 * @param unknown $float
	 * @return unknown
	 */
	public function setSaturation($float) {
		return $this->saturation = $float;
	}


	/**
	 *         array([Effect, chance])
	 *
	 * @param unknown
	 * @return unknown
	 */
	public function getEffects() {
		return [];
	}


	/**
	 *         Effects (array) $effects
	 *
	 * @param unknown
	 * @param unknown $effects
	 * @return unknown
	 */
	public function setEffects($effects) {
		return $this->effects = $effects;
	}


	/**
	 *
	 * @param Player  $player
	 */
	public function giveEffects(Player $player) {
		$effects = $this->getEffects();
		foreach ($effects as $effect) {
			$player->addEffect($effect);
		}
	}


}
