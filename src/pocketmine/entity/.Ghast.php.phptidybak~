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

namespace pocketmine\entity;

use pocketmine\item\Item as drp;
use pocketmine\Player;

class Ghast extends Monster{
	const NETWORK_ID = 41;

	public $width = 4.5;
	public $length = 4.5;
	public $height = 4.5;

 	public static $range = 16;
	public static $speed = 0.25;
	public static $jump = 1.8;
	public static $mindist = 3;

	public function initEntity(){
		$this->setMaxHealth(10);
		parent::initEntity();
	}

	public function getName(){
		return "Ghast";
	}

	 public function spawnTo(Player $player){
		$pk = $this->addEntityDataPacket($player);
		$pk->type = Ghast::NETWORK_ID;

		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

	public function getDrops(){
		return [
			drp::get(drp::GHAST_TEAR, 0, mt_rand(0, 1)),
			drp::get(drp::GUNPOWDER, 0, mt_rand(0, 2))
		];
	}


}
