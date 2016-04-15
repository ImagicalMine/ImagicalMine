<?php
/**
 * src/pocketmine/block/SnowLayer.php
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
use pocketmine\level\Level;
use pocketmine\Player;

class SnowLayer extends Flowable{

	protected $id = self::SNOW_LAYER;

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
		return "Snow Layer";
	}


	/**
	 *
	 * @return unknown
	 */
	public function canBeReplaced() {
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
	 * @return unknown
	 */
	public function getToolType() {
		return Tool::TYPE_SHOVEL;
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
		$down = $this->getSide(0);
		if ($down->isSolid()) {
			if ($down->getId() === $this->getId() && $down->getDamage() <= 7) {
				if ($down->getDamage() === 7) {
					$this->getLevel()->setBlock($down, new Snow(), true);
				} else {
					$down->setDamage($down->getDamage() + 1);
					$this->getLevel()->setBlock($down, $down, true);
				}
				return true;
			}else {
				$this->getLevel()->setBlock($block, $this, true);

				return true;
			}
		}

		return false;
	}


	/**
	 *
	 * @param unknown $type
	 * @return unknown
	 */
	public function onUpdate($type) {
		if ($type === Level::BLOCK_UPDATE_NORMAL) {
			if ($this->getSide(0)->getId() === self::AIR) { // Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), true);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		elseif ($type === Level::BLOCK_UPDATE_RANDOM) { // added melting
			if ($this->getLevel()->getBlockLightAt($this->x, $this->y, $this->z) >= 10) {
				$this->getLevel()->setBlock($this, new Air(), true);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public function getDrops(Item $item) {
		if ($item->isShovel() !== false) {
			return [[Item::SNOWBALL, 0, $this->getDamage() + 1]]; // Amount in PC version is based on the number of layers
		}

		return [];
	}


}
