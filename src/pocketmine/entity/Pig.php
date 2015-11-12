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

namespace pocketmine\entity;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\network\Network;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\item\Item as Dr;

class Pig extends Animal implements Rideable{
	const NETWORK_ID=12;
	public $width = 0.650;
	public $length = 1.3;
	public $height = 0.875;
	public $stepHeight = 0.2;

	public function getName(){
		return "Pig";
	}
	public static $range = 16;
	public static $speed = 0.5;
	public static $jump = 2.5;
	public static $mindist = 3;

	 public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Pig::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y+2;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));
		$player->addEntityMotion($this->getId(), $this->motionX, $this->motionY, $this->motionZ);
		parent::spawnTo($player);
	}

	public function getDrops(){
		return  [Dr::get($this->fireTicks > 0 ? Dr::COOKED_PORKCHOP : Dr::RAW_PORKCHOP, 0, mt_rand(1, 5))];
	}

	 

}
