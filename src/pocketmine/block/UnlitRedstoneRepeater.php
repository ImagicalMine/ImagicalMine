<?php
/**
 * src/pocketmine/block/UnlitRedstoneRepeater.php
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
class UnlitRedstoneRepeater extends PoweredRepeater{
	protected $id = self::UNLIT_REDSTONE_REPEATER;
	public function getName() : string{
		return "Unlit Redstone Repeater";
	}
	public function getStrength(){
		return 0;
	}
	public function isActivated(Block $from = null){
		return false;
	}
	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true);
	}
}
