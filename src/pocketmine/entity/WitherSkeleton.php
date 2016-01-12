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

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item as drp;
use pocketmine\Player;

class WitherSkeleton extends Skeleton{
    public $height = 2.39;
    public $width = 0.938;
    public $lenght = 1.312;

    public function initEntity(){
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getName(){
        return "Wither Skeleton";
    }

    public function spawnTo(Player $player){
        $pk = $this->addEntityDataPacket($player);
        $pk->type = Skeleton::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function getDrops(){
        $drops = [];
        if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player){
            $drops = [
                drp::get(drp::COAL, 0, mt_rand(0, 1)),
                drp::get(drp::BONE, 0, mt_rand(0, 2))
            ];
        }

        if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof ChargedCreeper){
            $drops = [
                drp::get(drp::SKULL, 1, 1)
            ];
        }

        return $drops;
    }


}