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


use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\Compound;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class ExperienceOrb extends Entity{
    const NETWORK_ID = 69;

    public $collected = false;
    protected $amount = 0;

    public function __construct(FullChunk $chunk, Compound $nbt){
	parent::__construct($chunk, $nbt);
    }

    public function onUpdate($currentTick){
	if($this->closed){
		return false;
	}
		
	$this->timings->startTiming();
		
	$hasUpdate = parent::onUpdate($currentTick);
	$collector = null;

	foreach($this->getLevel()->getPlayers() as $p){
		if(!($this->collected)){
			if($this->distance($p) < 7){  //6 or less
				$collector = $p;
				$this->collected = true;
			}
		}
	}

	if($this->age > 1200 || $this->collected){
		$this->kill();
		$hasUpdate = true;
	}
		
	if($collector !== null){
		$collector->giveExp($this->getAmount());
        }

	$this->timings->stopTiming();
		
        return $hasUpdate;
    }

    public function spawnTo(Player $player) {
	$pk = new AddEntityPacket();
	$pk->type = ExperienceOrb::NETWORK_ID;
	$pk->eid = $this->getId();
	$pk->x = $this->x;
	$pk->y = $this->y;
	$pk->z = $this->z;
	$pk->speedX = $this->motionX;
	$pk->speedY = $this->motionY;
	$pk->speedZ = $this->motionZ;
	$pk->metadata = $this->dataProperties;
	$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));
	/*
	$pk = new SpawnExperienceOrbPacket();
	$pk->eid = $this->getId();
	$pk->x = $this->x;
	$pk->y = $this->y;
	$pk->z = $this->z;
	$pk->count = $this->getAmount();
	$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));
	*/
	
        parent::spawnTo($player);
    }

    public function getAmount(){
	return $this->amount;
    }

    public function setAmount($amount){
	$this->amount = $amount;
    }
}
