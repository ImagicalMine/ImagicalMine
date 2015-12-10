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

class DaylightSensor extends Flowable implements Redstone{

	protected $id = self::DAYLIGHT_DETECTOR_INVERTED;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Inverted Daylight Sensor";
	}

	public function isRedstone(){
		return true;
	}
	
	public function canBeActivated(){
		return true;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getLightLevel()<=7){
				$this->togglePowered(true);
				return Level::BLOCK_UPDATE_NORMAL;
			}
			elseif($this->getLightLevel()>7){
				$this->togglePowered(false);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		$this->id=self::DAYLIGHT_DETECTOR;
	}

	public function getDrops(Item $item){
		return [[self::DAYLIGHT_DETECTOR_INVERTED,0,1]];
	}

	public function isPowered(){
		return (($this->meta & 0x08) === 0x08);
	}

	/**
	 * Toggles the current state
	 *
	 * @param
	 *        	bool
	 *        	whether or not the button is powered
	 */
	public function togglePowered($state){
		$state?$this->power=15:$this->power=0;
		$this->getLevel()->setBlock($this, $this);
	}
}
