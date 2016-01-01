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

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class Rabbit extends Animal{
    const NETWORK_ID = 18;

    public function getName(){
        return "Rabbit";
    }

    public function spawnTo(Player $player){
        $pk = new AddEntityPacket();
        $pk->eid = $this->getId();
        $pk->type = Rabbit::NETWORK_ID;
        $pk->x = $this->x;
        $pk->y = $this->y+2;
        $pk->z = $this->z;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->metadata = $this->dataProperties;

        parent::spawnTo($player);
    }

    public function getDrops(){
        $drops = [Item::get(Item::RABBIT_HIDE, 0, mt_rand(0, 2))];

        if($this->getLastDamageCause() === EntityDamageEvent::CAUSE_FIRE){
            $drops[] = Item::get(Item::COOKED_RABBIT, 0, mt_rand(1, 2));
        }else{
            $drops[] = Item::get(Item::RAW_RABBIT, 0, mt_rand(1, 2));
        }
        return $drops;
    }


}