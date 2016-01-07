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

namespace pocketmine\event\player;

use pocketmine\entity\Entity;
use pocketmine\entity\FishingRodState;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerFishEvent extends PlayerEvent implements Cancellable{
    public static $handlerList = null;

    /** @var int */
    private $exp;

    public function __construct(Player $player, Entity $entity, Item $hookEntity, FishingRodState $state){
        $this->player = $player;
        $this->entity = $entity;
        $this->hookEntity = $hookEntity;
        $this->state = $state;
    }

    public function getCaught(){
        return $this->entity;
    }

    public function getHook(){
        return $this->hookEntity;
    }

    public function getExpToDrop(){
        return $this->exp;
    }

    public function setExpToDrop($amount){
        $this->exp = $amount;
    }

    public function getState(){
        return $this->state;

    }

}