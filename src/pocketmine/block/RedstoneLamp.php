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

class RedstoneLamp extends Solid implements Redstone,RedstoneTools{

	protected $id = self::REDSTONE_LAMP;

	public function __construct(){

	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$this->getLevel()->setBlock($block, $this, true, true);
		$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE,0,$this);
		return true;
	}
	
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function onRedstoneUpdate($type,$power){
		if($type==Level::REDSTONE_UPDATE_NORMAL){
			if($power>0){
				$this->id=124;
				$this->getLevel()->setBlock($this, $this, true, false);
			}
			return;
		}
	}

	public function getName(){
		return "Redstone Lamp";
	}

	public function getHardness(){
		return 0.3;
	}
}
