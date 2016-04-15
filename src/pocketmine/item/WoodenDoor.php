<?php
/**
 * src/pocketmine/item/WoodenDoor.php
 *
 * @package default
 */


namespace pocketmine\item;

use pocketmine\block\Block;

class WoodenDoor extends Item{

	/**
	 *
	 * @param unknown $meta  (optional)
	 * @param unknown $count (optional)
	 */
	public function __construct($meta = 0, $count = 1) {
		$this->block = Block::get(Item::WOODEN_DOOR_BLOCK);
		parent::__construct(self::WOODEN_DOOR, 0, $count, "Wooden Door");
	}


	/**
	 *
	 * @return unknown
	 */
	public function getMaxStackSize() : int{
		return 1;
	}


}
