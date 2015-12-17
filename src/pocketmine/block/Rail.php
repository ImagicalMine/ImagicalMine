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
use pocketmine\math\Vector3;

class Rail extends Flowable{
	protected $id = self::RAIL;
	const SIDE_NORTH_WEST = 6;
	const SIDE_NORTH_EAST = 7;
	const SIDE_SOUTH_EAST = 8;
	const SIDE_SOUTH_WEST = 9;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Rail";
	}

	public function getHardness(){
		return 0.1;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);
				
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}

	public function getDrops(Item $item){
		return [[Item::RAIL,0,1]];
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $block->getSide(Vector3::SIDE_DOWN);
		if($down->isTransparent() === false){
			$this->getLevel()->setBlock($this, Block::get($this->id, 0));
			$up = $block->getSide(Vector3::SIDE_UP);
			if($block->getSide(Vector3::SIDE_EAST) && $block->getSide(Vector3::SIDE_SOUTH)){
				$this->setDirection(self::SIDE_SOUTH_EAST);
			}
			elseif($block->getSide(Vector3::SIDE_EAST) && $block->getSide(Vector3::SIDE_NORTH)){
				$this->setDirection(self::SIDE_NORTH_EAST);
			}
			elseif($block->getSide(Vector3::SIDE_SOUTH) && $block->getSide(Vector3::SIDE_WEST)){
				$this->setDirection(self::SIDE_SOUTH_WEST);
			}
			elseif($block->getSide(Vector3::SIDE_NORTH) && $block->getSide(Vector3::SIDE_WEST)){
				$this->setDirection(self::SIDE_NORTH_WEST);
			}
			elseif($block->getSide(Vector3::SIDE_EAST) && $block->getSide(Vector3::SIDE_WEST)){
				if($up->getSide(Vector3::SIDE_EAST)){
					$this->setDirection(Vector3::SIDE_EAST, true);
				}
				elseif($up->getSide(Vector3::SIDE_WEST)){
					$this->setDirection(Vector3::SIDE_WEST, true);
				}
				else{
					$this->setDirection(Vector3::SIDE_EAST);
				}
			}
			elseif($block->getSide(Vector3::SIDE_SOUTH) && $block->getSide(Vector3::SIDE_NORTH)){
				if($up->getSide(Vector3::SIDE_SOUTH)){
					$this->setDirection(Vector3::SIDE_SOUTH, true);
				}
				elseif($up->getSide(Vector3::SIDE_NORTH)){
					$this->setDirection(Vector3::SIDE_NORTH, true);
				}
				else{
					$this->setDirection(Vector3::SIDE_SOUTH);
				}
			}
			else{
				$this->setDirection(Vector3::SIDE_NORTH);
			}
			return true;
		}
		return false;
	}

	public function getDirection(){
		switch($this->meta){
			case 0:
				{
					return Vector3::SIDE_SOUTH;
				}
			case 1:
				{
					return Vector3::SIDE_EAST;
				}
			case 2:
				{
					return Vector3::SIDE_EAST;
				}
			case 3:
				{
					return Vector3::SIDE_WEST;
				}
			case 4:
				{
					return Vector3::SIDE_NORTH;
				}
			case 5:
				{
					return Vector3::SIDE_SOUTH;
				}
			case 6:
				{
					return self::SIDE_NORTH_WEST;
				}
			case 7:
				{
					return self::SIDE_NORTH_EAST;
				}
			case 8:
				{
					return self::SIDE_SOUTH_EAST;
				}
			case 9:
				{
					return self::SIDE_SOUTH_WEST;
				}
			default:
				{
					return Vector3::SIDE_SOUTH;
				}
		}
	}

	public function __toString(){
		$this->getName() . " facing " . $this->getDirection() . ($this->isCurve()?" on a curve ":($this->isOnSlope()?" on a slope":""));
	}

	public function setDirection($face, $isOnSlope = false){
		switch($face){
			case Vector3::SIDE_EAST:
				{
					$this->meta = $isOnSlope?2:1;
				}
			case Vector3::SIDE_WEST:
				{
					$this->meta = $isOnSlope?3:1;
				}
			case Vector3::SIDE_NORTH:
				{
					$this->meta = $isOnSlope?4:0;
				}
			case Vector3::SIDE_SOUTH:
				{
					$this->meta = $isOnSlope?5:0;
				}
			case self::SIDE_NORTH_WEST:
				{
					$this->meta = 6;
				}
			case self::SIDE_NORTH_EAST:
				{
					$this->meta = 7;
				}
			case self::SIDE_SOUTH_EAST:
				{
					$this->meta = 8;
				}
			case self::SIDE_SOUTH_WEST:
				{
					$this->meta = 9;
				}
			default:
				{
					$this->meta = 0;
				}
		}
		$this->getLevel()->setBlock($this, Block::get($this->id, $this->meta));
	}

	public function isOnSlope(){
		$d = $this->meta;
		return ($d == 0x02 || $d == 0x03 || $d == 0x04 || $d == 0x05);
	}

	public function isCurve(){
		$d = $this->meta;
		return ($d == 0x06 || $d == 0x07 || $d == 0x08 || $d == 0x09);
	}
}
