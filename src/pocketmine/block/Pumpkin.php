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

use pocketmine\entity\SnowGolem;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Double;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Float;
use pocketmine\Player;

class Pumpkin extends Solid{

	protected $id = self::PUMPKIN;

	public function __construct(){

	}

	public function getHardness(){
		return 1;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function getName(){
		return "Pumpkin";
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($player instanceof Player){
			$this->meta = ((int) $player->getDirection() + 5) % 4;
		}
		$this->getLevel()->setBlock($block, $this, true, true);

		if($player != null){
			$firstBlock = $this->getLevel()->getBlock($block->add(0, -1, 0));
			$secondBlock = $this->getLevel()->getBlock($block->add(0, -2, 0));

			if(($firstBlock && $secondBlock) === Item::SNOW_BLOCK){ //Snow Golem
				$this->getLevel()->setBlock($block, new Air());
				$this->getLevel()->setBlock($firstBlock, new Air());
				$this->getLevel()->setBlock($secondBlock, new Air());

				$snowGolem = new SnowGolem($player->getLevel()->getChunk($this->getX() >> 4, $this->getZ() >> 4), new Compound("", [
						"Pos" => new Enum("Pos", [
							new Double("", $this->x),
							new Double("", $this->y),
							new Double("", $this->z)
						]),
						"Motion" => new Enum("Motion", [
 							new Double("", 0),
							new Double("", 0),
 							new Double("", 0)
						]),
						"Rotation" => new Enum("Rotation", [
							new Float("", 0),
 							new Float("", 0)
 						]),
 					]));
 					$snowGolem->spawnToAll();

			}
			//TODO: Iron Golem

		}

		return true;
	}

}