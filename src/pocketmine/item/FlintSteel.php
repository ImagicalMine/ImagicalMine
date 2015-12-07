<?php

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

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\Solid;
use pocketmine\level\Level;
use pocketmine\Player;

class FlintSteel extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FLINT_STEEL, $meta, $count, "Flint and Steel");
	}

	public function canBeActivated(){
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		if(($player->gamemode & 0x01) === 0 and $this->useOn($block) and $this->getDamage() >= $this->getMaxDurability()){
			$player->getInventory()->setItemInHand(new Item(Item::AIR, 0, 0));
		}

		if($block->getId() === self::AIR and ($target instanceof Solid)){
			$level->setBlock($block, new Fire(), true);

			return true;
		}

		return false;
	}
}