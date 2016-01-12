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

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item as drp;
use pocketmine\Player;

class PigZombie extends Monster{
    const NETWORK_ID = 36;

    public $height = 2.03;
    public $width = 1.031;
    public $lenght = 1.125;

    public function initEntity(){
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getName(){
        return "Zombie Pigman";
    }

    public function spawnTo(Player $player){
        $pk = $this->addEntityDataPacket($player);
        $pk->type = PigZombie::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function getDrops(){
        $drops = [
            drp::get(drp::ROTTEN_FLESH, 0, mt_rand(0, 1)),
        ];

        if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player){
            if(mt_rand(0, 199) < 5){
                switch(mt_rand(0, 2)){
                    case 0:
                        $drops[] = drp::get(drp::GOLD_INGOT, 0, 1);
                        break;
                    case 1:
                        $drops[] = drp::get(drp::GOLDEN_SWORD, 0, 1);
                        break;
                    case 2:
                        $drops[] = drp::get(drp::GOLD_NUGGET, 0, 1);
                        break;
                }
            }
        }
        return $drops;

    }
}