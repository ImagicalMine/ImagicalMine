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
use pocketmine\item\Item as drp;
use pocketmine\Player;

class Pig extends Animal implements Rideable{
    const NETWORK_ID = 12;

    public $width = 0.625;
    public $height = 1;
    public $lenght = 1.5;

    public function initEntity(){
        $this->setMaxHealth(10);
        parent::initEntity();
    }

    public function getName() {
        return "Pig";
    }

    public function spawnTo(Player $player){
        $pk = $this->addEntityDataPacket($player);
        $pk->type = Pig::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function isBaby(){
        return $this->getDataFlag(self::DATA_AGEABLE_FLAGS, self::DATA_FLAG_BABY);
    }

    public function getDrops(){
        $drops = [];
        if($this->getLastDamageCause() === EntityDamageEvent::CAUSE_FIRE){
            $drops[] = drp::get(drp::COOKED_PORKCHOP, 0, mt_rand(1, 3));
        }else{
            $drops[] = drp::get(drp::RAW_PORKCHOP, 0, mt_rand(1, 3));
        }
        return $drops;
    }
}
