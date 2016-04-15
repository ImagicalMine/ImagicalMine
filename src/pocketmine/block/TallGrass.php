<?php
/**
 * src/pocketmine/block/TallGrass.php
 *
 * @package default
 */


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;


class TallGrass extends Flowable{

	protected $id = self::TALL_GRASS;

	/**
	 *
	 * @param unknown $meta (optional)
	 */
	public function __construct($meta = 1) {
		$this->meta = $meta;
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
	public function canBeReplaced() {
		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		static $names = [
			0 => "Dead Shrub",
			1 => "Tall Grass",
			2 => "Fern",
			3 => ""
		];
		return $names[$this->meta & 0x03];
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
		if ($down->getId() === self::GRASS or $down->getId() === self::DIRT or $down->getId() === self::PODZOL) {
			$this->getLevel()->setBlock($block, $this, true);

			return true;
		}

		return false;
	}



	/**
	 *
	 * @param Item    $item
	 * @param Player  $player (optional)
	 * @return unknown
	 */
	public function onActivate(Item $item, Player $player = null) {
		if ($item->getId() === Item::DYE and $item->getDamage() === 0x0F and ($this->getDamage() === 1 || $this->getDamage() === 2)) {
			$this->getLevel()->setBlock($this->getSide(1), new DoublePlant(($this->getDamage() + 1) ^ 0x08));
			$this->getLevel()->setBlock($this, new DoublePlant($this->getDamage() + 1));
			return true;
		}else {return false;}
	}


	/*	public function onActivate(Item $item, Player $player = null){
 		if($item->getId() === Item::DYE and $item->getDamage() === 0x0F){
 		$this->getLevel()->setBlock($this->getSide(1), new DoublePlant(2));
 		}
 	}
*/

	/**
	 *
	 * @param unknown $type
	 * @return unknown
	 */
	public function onUpdate($type) {
		if ($type === Level::BLOCK_UPDATE_NORMAL) {
			if ($this->getSide(0)->isTransparent() === true) { //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), false, false, true);

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
		if ($item->isShears()) {
			return [
				[$this->id, $this->meta, 1]
			];
		}elseif (mt_rand(0, 15) === 0) {
			return [
				[Item::WHEAT_SEEDS, 0, 1]
			];
		}

		return [];
	}


}
