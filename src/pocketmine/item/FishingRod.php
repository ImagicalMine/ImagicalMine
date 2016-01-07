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
 * @link http://forums.imagicalcorp.me/
 * 
 *
*/

namespace pocketmine\item;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\entity\FishingHook;

class FishingRod extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FISHING_ROD, 0, $count, "Fishing Rod");
	}

	public function onActivate(Level $level, Player $player, $block, $target, $face, $fx, $fy, $fz){
		foreach($player->getLevel()->getEntities() as $entity){
			if($entity instanceof FishingHook){
				if($entity->shootingEntity === $player){
					$entity->reelLine();
				}
			}
		}
	}
}