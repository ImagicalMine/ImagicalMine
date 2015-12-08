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

class StoneButton extends Flowable{
	protected $id = self::STONE_BUTTON;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Stone Button";
	}

	public function getHardness(){
		return 0.5;
	}
	
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			/*$below = $this->getSide(0);
			$side = $this->getAttachedFace();*/
			$faces = [
				0 => 0,
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
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
			$this->setDamage($faces[$face]);
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

	/**
	 * Gets the face that this block is attached on
	 *
	 * @return BlockFace attached to
	 */
	public function getAttachedFace(){
		$data = $this->getDamage() & 0x7;
		
		switch($data){
			case 0x1:
				return "WEST";
			
			case 0x2:
				return "EAST";
			
			case 0x3:
				return "NORTH";
			
			case 0x4:
				return "SOUTH";
		}
		
		return null;
	}

	/**
	 * Sets the direction this button is pointing toward
	 */
	public function setFacingDirection($face){
		$data = ($this->meta & 0x8);
		
		switch($face){
			case "EAST":
				$data |= 0x1;
				break;
			
			case "WEST":
				$data |= 0x2;
				break;
			
			case "SOUTH":
				$data |= 0x3;
				break;
			
			case "NORTH":
				$data |= 0x4;
				break;
		}
		
		$this->setDamage($data);
	}
	
	public function onRun($currentTick){
		
	}

	public function __toString(){
		return $this->getName() . " " . (isPowered()?"":"NOT ") . "POWERED";
	}
}