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

namespace pocketmine\block;

use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\Player;

class DeadBush extends Flowable{

	protected $id = self::DEAD_BUSH;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Dead Bush";
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){ 
 		$down = $this->getSide(0); 
 		if($down->getId() === self::SAND or $down->getId() === self::HARDENED_CLAY or $down->getId() === self::PODZOL){ 
 			$this->getLevel()->setBlock($block, $this, true, true); 
  
 			return true; 
 		} 
  
 		return false; 
 	} 

    public function getDrops(Item $item){
 		if($item->isShears()){ 
 			return [ 
 				[Item::DEAD_BUSH, 0, 1], 
 			]; 
 		}else{ 
 			return [Item::STICK, 0, mt_rand(0, 3)]; 
 		} 
 	} 

}
