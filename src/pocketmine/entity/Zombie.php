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
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\network\protocol\MoveEntityPacket;
use pocketmine\math\AxisAlignedBB;

 use pocketmine\item\Item as Dr;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\Network;

class Zombie extends Monster{
	const NETWORK_ID = 32;
	public static $range = 32;
	public static $speed = 0.2;
	public static $jump = 2.5;
	public static $attack = 1.5;
	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.8;
	public $stepHeight = 0.5;

	public function getName(){
		return "Zombie";
	}

	 	 public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Zombie::NETWORK_ID;
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
		$drops = [];
		$rnd = mt_rand(0,1);
		if ($rnd) {
			$drops[] = Dr::get(Dr::FEATHER, 0, $rnd);
		}
		if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player){
			if(mt_rand(0, 199) < 5){
				switch(mt_rand(0, 2)){
					case 0:
						$drops[] = Dr::get(Dr::IRON_INGOT, 0, 1);
						break;
					case 1:
						$drops[] = Dr::get(Dr::CARROT, 0, 1);
						break;
					case 2:
						$drops[] = Dr::get(Dr::POTATO, 0, 1);
						break;
				}
			}
		}

		return $drops;
	}

}