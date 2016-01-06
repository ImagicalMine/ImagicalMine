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

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\Player;

class Chicken extends Animal{
	const NETWORK_ID = 10;

	public $width = 1;
	public $length = 0.5;
	public $height = 0.8;

	public function initEntity(){
		$this->setMaxHealth(4);
		parent::initEntity();
	}

	public function getName() {
		return "Chicken";
	}

	public function spawnTo(Player $player){
		$pk = $this->addEntityDataPacket($player);
		$pk->type = Chicken::NETWORK_ID;

		$player->dataPacket($pk);
		parent::spawnTo($player);
	}
	
	public function getDrops(){
		$drops = [Item::get(Item::FEATHER, 0, mt_rand(0, 2))];

		if($this->getLastDamageCause() === EntityDamageEvent::CAUSE_FIRE){
			$drops[] = Item::get(Item::COOKED_CHICKEN, 0, mt_rand(1, 2));
		}else{
			$drops[] = Item::get(Item::RAW_CHICKEN, 0, mt_rand(1, 2));
		}
		return $drops;
	}
}
