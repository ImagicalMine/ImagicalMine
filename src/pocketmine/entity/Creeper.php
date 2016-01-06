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


use pocketmine\item\Item;
use pocketmine\Player;

class Creeper extends Monster implements Explosive{
    const NETWORK_ID = 33;

    public function explode(){
        //TODO: CreeperExplodeEvent
    }

    public function initEntity(){
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getName() {
        return "Creeper";
    }

    public function spawnTo(Player $player){
        $pk = $this->addEntityDataPacket($player);
        $pk->type = Creeper::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function getDrops(){
        $drops = [Item::get(Item::GUNPOWDER, 0, mt_rand(1,2))];

        //TODO Add chance of drops head

        return $drops;
    }
}
