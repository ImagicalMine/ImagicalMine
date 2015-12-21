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

namespace pocketmine\block;

use pocketmine\inventory\AnvilInventory;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;

class AnvilBlock extends Fallable{
    const TYPE_ANVIL = 0;
    /*const TYPE_ANVIL_NORTH_SOUTH = 0;
    const TYPE_ANVIL_EAST_WEST = 1;
    const TYPE_ANVIL_SOUTH_NORTH = 2;
    const TYPE_ANVIL_WEST_EAST = 3;*/
    const TYPE_SLIGHTLY_DAMAGED_ANVIL = 4;
    /*const TYPE_SLIGHTLY_DAMAGED_ANVIL_NORTH_SOUTH = 5;
    const TYPE_SLIGHTLY_DAMAGED_ANVIL_EAST_WEST = 5;
    const TYPE_SLIGHTLY_DAMAGED_ANVIL_SOUTH_NORTH = 6;
    const TYPE_SLIGHTLY_DAMAGED_ANVIL_WEST_EAST = 7;*/
    const TYPE_VERY_DAMAGED_ANVIL = 8;
    /*const TYPE_VERY_DAMAGED_ANVIL_NORTH_SOUTH = 8;
    const TYPE_VERY_DAMAGED_ANVIL_EAST_WEST = 9;
    const TYPE_VERY_DAMAGED_ANVIL_SOUTH_NORTH = 10;
    const TYPE_VERY_DAMAGED_ANVIL_WEST_EAST = 11;*/

    protected $id = self::ANVIL_BLOCK;

    public function isSolid(){
        return false;
    }

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function canBeActivated(){
        return true;
    }

    public function getHardness(){
        return 5;
    }

    public function getResistance(){
        return 6000;
    }

    public function getName(){
        static $names = [
            self::TYPE_ANVIL => "Anvil",
            "",
            "",
            "",
            self::TYPE_SLIGHTLY_DAMAGED_ANVIL => "Slighty Damaged Anvil",
            "",
            "",
            "",
            self::TYPE_VERY_DAMAGED_ANVIL => "Very Damaged Anvil",
            "",
            "",
            "",
        ];
        return $names[$this->meta];
    }

    public function getToolType(){
        return Tool::TYPE_PICKAXE;
    }

    public function onActivate(Item $item, Player $player = null){
        if($player instanceof Player){
            if($player->isCreative()){
                return true;
            }

            $player->addWindow(new AnvilInventory($this));
        }

        return true;
    }

    public function getDrops(Item $item){
        if ($item->isPickaxe() >= Tool::TIER_WOODEN){
            return [[$this->id, 0, 1]]; // TODO break level
        }else{
            return [];
        }
    }

    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
        if ($target->isTransparent() === false) {
            $faces = [
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
            ];

            $damage = $this->getDamage();
            $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] & 0x04;

            if($damage >= 0 && $damage <= 3){
                $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
            }elseif($damage >= 4 && $damage <= 7){
                $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] | 0x04;

            }elseif($damage >= 8 && $damage <= 11){
                $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] | 0x08;

            }
            $this->getLevel()->setBlock($block, $this, true);
            return true;
        }
        return false;
    }
}