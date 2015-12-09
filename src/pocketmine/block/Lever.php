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
use pocketmine\event\block\BlockUpdateEvent;

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

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
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

		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){

		if($target->isTransparent() === false){
			$faces = [
				0 => 0,
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		$this->togglePowered();
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
		$this->meta ^= 0x08;
		$this->isPowered()?$this->setPower(15):$this->setPower(0);
		$this->getLevel()->setBlock($this, $this);
	}
}
