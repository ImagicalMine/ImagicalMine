<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;

class RedstoneWire extends Flowable{

	protected $id = self::REDSTONE_WIRE;
	
	public function __construct($meta = 0){
		$this->meta = $meta;
	}
		
	public function getHardness(){
		return 0;
	}

	public function isSolid(){
		return true;
	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0)->getId();
		switch($down){
			case self::AIR:
			case self::REDSTONE_WIRE:
			case self::LEAVE:
			case self::LEAVE2:
				return false;
			default :
				$this->getLevel()->setBlock($block, $this, true, true);
				return true;
		}
	}
	
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0)->getId();
			if($down === self::AIR){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		return false;
		}
	}
	
	public function getName(){
		return "Redstone Wire";
	}
		
	public function getDrops(Item $item){
			return [[Item::REDSTONE_DUST, 0, 1],];
	}
}