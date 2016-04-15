<?php
/**
 * src/pocketmine/inventory/DropperInventory.php
 *
 * @package default
 */


namespace pocketmine\inventory;

use pocketmine\tile\Dropper;

class DropperInventory extends ContainerInventory{

	/**
	 *
	 * @param Dropper $tile
	 */
	public function __construct(Dropper $tile) {
		parent::__construct($tile, InventoryType::get(InventoryType::DROPPER));
	}


	/**
	 *
	 * @return Dropper
	 */
	public function getHolder() {
		return $this->holder;
	}


}
