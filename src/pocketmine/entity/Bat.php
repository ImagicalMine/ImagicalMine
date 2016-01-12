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

use pocketmine\item\Item as Dr;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\network\Network;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;


class Bat extends Animal{
	const NETWORK_ID = 19;

	public $width = 0.469;
	public $length = 0.484;
	public $height = 0.5;

	public static $range = 16;
	public static $speed = 0.25;
	public static $jump = 1.8;
	public static $mindist = 3;

	public function initEntity(){
		$this->setMaxHealth(6);
		parent::initEntity();
	}

	public function getName(){
		return "Bat";
	}

	 public function spawnTo(Player $player){
		$pk = $this->addEntityDataPacket($player);
		$pk->type = Bat::NETWORK_ID;

		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

}
