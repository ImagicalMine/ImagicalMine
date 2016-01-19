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

use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Double;
use pocketmine\nbt\tag\Float;
use pocketmine\entity\Minecart as MinecartEntity;
use pocketmine\block\RailBlock;
use pocketmine\math\Vector3;

class Minecart extends Item{

	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::MINECART, $meta, $count, "Minecart");
	}

	public function getMaxStackSize(){
		return 1;
	}

	public function canBeActivated(){
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$realPos = $block->getSide(Vector3::SIDE_UP);
		if(!$block instanceof RailBlock) return false;
		$cart = new MinecartEntity($player->getLevel()->getChunk($realPos->getX() >> 4, $realPos->getZ() >> 4), new Compound("", ["Pos" => new Enum("Pos", [new Double("", $realPos->getX()),new Double("", $realPos->getY()),new Double("", $realPos->getZ())]),
				"Motion" => new Enum("Motion", [new Double("", 0),new Double("", 0),new Double("", 0)]),"Rotation" => new Enum("Rotation", [new Float("", 0),new Float("", 0)])]));
		$cart->spawnToAll();
		
		if($player->isSurvival()){
			$item = $player->getInventory()->getItemInHand();
			$count = $item->getCount();
			if(--$count <= 0){
				$player->getInventory()->setItemInHand(Item::get(Item::AIR));
				return;
			}
			
			$item->setCount($count);
			$player->getInventory()->setItemInHand($item);
		}
		
		return true;
	}
}

