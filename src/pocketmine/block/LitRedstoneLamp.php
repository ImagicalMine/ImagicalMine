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

use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;

class LitRedstoneLamp extends Solid{

	protected $id = self::LIT_REDSTONE_LAMP;

	public function __construct(){

	}
	
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}
	
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0);
			if($down instanceof Transparent){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
			if($this->fetchPower()==0){
				$this_old=$this;
				$this->id=123;
				$this->getLevel()->setBlock($this_old, $this, true, true);
			}
		return false;
		}
	}
	
	public function getName(){
		return "Lit Redstone Lamp";
	}

	public function getHardness(){
		return 0.3;
	}
	
	public function getLightLevel(){
		return 15;
	}

	public function getDrops(Item $item){
		$drops = [];
		$drops[] = [Item::REDSTONE_LAMP, 0, 1];
		return $drops;
	}
}
