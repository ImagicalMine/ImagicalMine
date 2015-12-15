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
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\entity\Minecart;

class ActivatorRail extends RailBlock{

	protected $id = self::ACTIVATOR_RAIL;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Activator Rail";
	}

	public function getHardness(){
		return 0.1;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->isTransparent() === false){
			if($face === 0 || $face === 1){
				$to = $player instanceof Player?$player->getDirection():0;
				$this->meta = ($to ^ 0x01 === 0x01?0:1);
			}
			$this->getLevel()->setBlock($block, Block::get(Item::POWERED_RAIL, $this->meta), true, true);
			return true;
		}
		return false;
	}
	
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);
			}
		}
		return false;
	}

	public function onRedstoneUpdate($type){
		if($this->isActivitedByRedstone() && !$this->isPowered()){
			$this->togglePowered();
		}
		elseif(!$this->isActivitedByRedstone() && $this->isPowered()){
			$this->togglePowered();
		}
	}

	public function getDrops(Item $item){
		return [[Item::ACTIVATOR_RAIL, 0, 1]];
	}

	public function isPowered(){
		return (($this->meta & 0x01) === 0x01);
	}
	
	/*public function isEntityCollided(Entity $entity = null){
		foreach ($this->getLevel()->getEntities() as $entity){
			if($entity instanceof Minecart && $entity->getPosition() === $this)
				return true;
		}
		return false;
	}*/

	/**
	 * Toggles the current state of this plate
	 */
	public function togglePowered(){
		$this->meta ^= 0x08;
		$this->isPowered()?$this->power=15:$this->power=0;
		$this->getLevel()->setBlock($this, $this, true, true);
	}
}
