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

use pocketmine\Player;

class Silverfish extends Monster{
    const NETWORK_ID = 39;

    public $height = 0.438;
    public $width = 0.609;
    public $lenght = 1.094;

    public function initEntity(){
        $this->setMaxHealth(8);
        parent::initEntity();
    }

 	public function getName(){
        return "Silverfish";
    }

    public function spawnTo(Player $player){
        $pk = $this->addEntityDataPacket($player);
        $pk->type = Silverfish::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function getDrops(){
        $drops = [];
        return $drops;
    }
}
