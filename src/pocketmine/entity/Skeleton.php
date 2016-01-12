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
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\item\Item as drp;
use pocketmine\nbt\tag\Int;
use pocketmine\Player;

class Skeleton extends Monster implements ProjectileSource{
    const NETWORK_ID = 34;

    public $height = 2;
    public $width = 0.781;
    public $lenght = 0.875;

    public function initEntity(){
        $this->setMaxHealth(20);
        parent::initEntity();

        if(!isset($this->namedtag->Profession)){
            $this->setSkeletonType(1);
        }
    }

 	public function getName() {
        return "Skeleton";
    }

    public function spawnTo(Player $player){
        $pk = $this->addEntityDataPacket($player);
        $pk->type = Skeleton::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function setSkeletonType($type){
        $this->namedtag->SkeletonType = new Int("SkeletonType", $type);
    }

    public function getSkeletonType(){
        return $this->namedtag["SkeletonType"];
    }

    public function getDrops(){
        $drops = [
            drp::get(drp::ARROW, 0, mt_rand(0, 2)),
            drp::get(drp::BONE, 0, mt_rand(0, 2))
        ];

        if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player){
            if(mt_rand(0, 199) < 5){
                $drops[] = drp::get(drp::BOW, 0, 1);
            }
        }

        if($this->lastDamageCause instanceof EntityExplodeEvent and $this->lastDamageCause->getEntity() instanceof ChargedCreeper){
            drp::get(drp::SKULL, 0, 1);
        }

        return $drops;
    }
}
