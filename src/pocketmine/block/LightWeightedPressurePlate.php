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
use pocketmine\item\Tool;
use pocketmine\entity\Entity;

class LightWeightedPressurePlate extends WoodenPressurePlate{

	protected $id = self::LIGHT_WEIGHTED_PRESSURE_PLATE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Light Pressure Plate";
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_SCHEDULED){
			if($this->meta === 1 && !$this->isEntityCollided()){
				$this->meta = 0;
				$this->getLevel()->setBlock($this, Block::get($this->getId(), $this->meta), false, true, true);
				return Level::BLOCK_UPDATE_WEAK;
			}
		}
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$this->getLevel()->scheduleUpdate($this, 50);
		}
		return false;
	}

	public function onEntityCollide(Entity $entity){
		if($this->meta == 0){
			$this->meta = 1;
			$this->getLevel()->setBlock($this, $this, true , true);
		}
	}

	public function onEntityUnCollide(Entity $entity){
		if($this->meta === 1){
			$this->meta = 0;
			$this->getLevel()->setBlock($this, $this, true , true);
		}
	}

	public function isEntityCollided(Entity $entity = null){
		foreach ($this->getLevel()->getEntities() as $entity){
			if($entity->getPosition()===$this)
				return true;
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($target->isTransparent() === false || $target->getId() === self::FENCE){
			$this->getLevel()->setBlock($block, $this, true, true);
			
			return true;
		}
		
		return false;
	}

	public function getDrops(Item $item){
		return [[$this->id,0,1]];
	}
	
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}
}