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
use pocketmine\entity\Monster;
use pocketmine\network\Network;

class CavernSpider extends Monster{
	const NETWORK_ID = 40;

	public $width = 1.438;
	public $length = 1.188;
	public $height = 0.547;

	public function initEntity(){
		$this->setMaxHealth(12);
		parent::initEntity();
	}

	public function getName(){
		return "Cave Spider";
	}

	public function spawnTo(Player $player){
		$pk = $this->addEntityDataPacket($player);
		$pk->type = CavernSpider::NETWORK_ID;

		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

	public function getDrops(){
		$drops = [];
		$string = mt_rand(0,5);
		if($string){
			$drops = array(Dr::get(Dr::STRING,0,$string), Dr::get(375, 0, $string));
		}
		return $drops;
	 }
  	
}
