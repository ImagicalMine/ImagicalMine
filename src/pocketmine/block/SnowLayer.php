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

class SnowLayer extends Flowable{

	protected $id = self::SNOW_LAYER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Snow Layer";
	}

	public function canBeReplaced(){
		return true;
	}

	public function getHardness(){
		return 0.1;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $block->getSide(0);
		// print "this" . $this->__toString() . "\ndown" . $down->__toString() . "\nblock" . $block->__toString() . "\ntarget" . $target->__toString() . "\n----\n";
		if($down->isTransparent() === false){
			if($target->getId() === Block::SNOW_LAYER && $face === 1){
				if($target->getDamage() < 7){
					// add layer
					$target->setDamage($target->getDamage() + 1);
					$this->getLevel()->setBlock($target, $this);
					// print "on layer, adding";
					return true;
				}
				elseif($target->canBeReplaced()){
					// on layers new layer
					$this->getLevel()->setBlock($target->getSide(1), $this);
					// print "on layer, set new";
				}
				return false;
			}
			elseif($target->getId() !== Block::SNOW_LAYER && $block->getId() === Block::SNOW_LAYER && $face !== 1){
				if($block->getDamage() < 7){
					// add layer
					$block->setDamage($block->getDamage() + 1);
					$this->getLevel()->setBlock($block, $this);
					// print "on layer block, adding";
					return true;
				}
				elseif($block->canBeReplaced()){
					// on layers new layer
					$this->getLevel()->setBlock($block->getSide(1), $this);
					// print "on layer block, set new";
					return true;
				}
				return false;
			}
			elseif($target->getId() === Block::SNOW_LAYER && $face !== 0 && $block->canBeReplaced()){
				// new layer
				$this->getLevel()->setBlock($block, $this);
				// print "empty slot, new";
				return true;
			}
			elseif($target->getId() !== Block::SNOW_LAYER && $block->getId() !== Block::SNOW_LAYER && $block->canBeReplaced()){
				// on layers new layer
				$this->getLevel()->setBlock($block, $this);
				// print "new layer";
				return true;
			}
		}
		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->getId() === self::AIR){ // Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), true);
				
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		elseif($type === Level::BLOCK_UPDATE_RANDOM){ // added melting
			if($this->getLevel()->getBlockLightAt($this->x, $this->y, $this->z) >= 10){
				$this->getLevel()->setBlock($this, new Air(), true);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		
		return false;
	}

	public function getDrops(Item $item){
		if($item->isShovel() !== false){
			return [[Item::SNOWBALL,0,$this->getDamage() + 1]]; // Amount in PC version is based on the number of layers
		}
		
		return [];
	}
}