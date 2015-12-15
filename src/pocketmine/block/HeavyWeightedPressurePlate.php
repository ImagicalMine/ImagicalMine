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
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\level\sound\GenericSound;
use pocketmine\item\Tool;

class HeavyWeightedPressurePlate extends Transparent implements Redstone{

	protected $id = self::HEAVY_WEIGHTED_PRESSURE_PLATE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}
	
	public function hasEntityCollision(){
		return true;
	}
	
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getName(){
		return "Heavy Weighted Pressure Plate";
	}

	public function getHardness(){
		return 0.5;
	}

	public function getPower(){
		return $this->isPowered()?15:0;
	}
	
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_SCHEDULED){
			if($this->isPowered() && !$this->isEntityCollided()){
				$this->togglePowered();
			}
		}
		return false;
	}

	public function onEntityCollide(Entity $entity){
		if(!$this->isPowered()){
			$this->togglePowered();
			$this->getLevel()->scheduleUpdate($this, 50);
		}
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $block->getSide(Vector3::SIDE_DOWN);
		if($down->isTransparent() === false || $down instanceof Fence/* || $down instanceof Stair || $down instanceof Slab*/){
			$this->getLevel()->setBlock($block, $this, true, true);
			return true;
		}
		
		return false;
	}

	public function getDrops(Item $item){
		return [[$this->id,0,1]];
	}

	public function isPowered(){
		return (($this->meta & 0x01) === 0x01);
	}
	
	public function isEntityCollided(){
		foreach ($this->getLevel()->getEntities() as $entity){
			if($this->getLevel()->getBlock($entity->getPosition()) === $this)
				return true;
		}
		return false;
	}

	/**
	 * Toggles the current state of this plate
	 */
	public function togglePowered(){
		$this->meta ^= 0x01;
		$this->isPowered()?$this->power=15:$this->power=0;
		$this->getLevel()->addSound(new GenericSound($this, 1000));
		$this->getLevel()->setBlock($this, $this, true, true);
	}
}