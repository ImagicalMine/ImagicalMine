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

class Lever extends Flowable implements Redstone{

	protected $id = self::LEVER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Lever";
	}

	public function isRedstone(){
		return true;
	}
	
	public function canBeActivated(){
		return true;
	}
	
	public function getPower(){
		if($this->meta >= 8){
			return 15;
		}
		return 0;
	}

	public function onUpdate($type){
		/*if($type === Level::BLOCK_UPDATE_NORMAL){
			$below = $this->getSide(0);
			$faces = [
				0 => 1,
				1 => 0,
				2 => 3,
				3 => 2,
				4 => 5,
				5 => 4,
			];
			if($this->getSide($faces[$this->meta])->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return true;*/
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){

		if($target->isTransparent() === false){
			$faces = [
				3 => 3,
				2 => 4,
				4 => 2,
				5 => 1,
			];
			if($face === 0){
				$to = $player instanceof Player?$player->getDirection():0;
				$this->meta = ($to ^ 0x01 === 0x01?0:7);
			}
			elseif($face === 1){
				$to = $player instanceof Player?$player->getDirection():0;
				$this->meta = ($to ^ 0x01 === 0x01?6:5);
			}
			else{
				$this->meta = $faces[$face];
			}
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		$this->meta ^= 0x08;
		$this->togglePowered();
		$this->getLevel()->setBlock($this, $this ,true ,true);
	}
	
	

	public function getDrops(Item $item){
		return [[$this->id,0,1]];
	}

	public function isPowered(){
		return (($this->meta & 0x08) === 0x08);
	}

	/**
	 * Toggles the current state of this button
	 *
	 * @param
	 *        	bool
	 *        	whether or not the button is powered
	 */
	public function togglePowered(){
		$this->isPowered()?$this->power=15:$this->power=0;
	}
}
