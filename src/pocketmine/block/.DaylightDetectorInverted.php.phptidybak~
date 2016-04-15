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

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;

class DaylightDetectorInverted extends Transparent implements Redstone,RedstoneSwitch{

	protected $id = self::DAYLIGHT_DETECTOR_INVERTED;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Inverted Daylight Detector";
	}

	public function isRedstone(){
		return true;
	}
	
	public function canBeActivated(){
		return true;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_SCHEDULED || $type === Level::BLOCK_UPDATE_NORMAL){
			$this->power=15-$this->getLightLevel();
			$this->getLevel()->setBlock($this, $this, true);
			$this->getLevel()->scheduleUpdate($this, 50);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL, $this->getPower());
		}
		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		$this->id=self::DAYLIGHT_DETECTOR;
		$this->getLevel()->setBlock($this, $this, true);
		$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL,$this->getPower());
	}

	public function getDrops(Item $item){
		return [[self::DAYLIGHT_DETECTOR,0,1]];
	}
}
