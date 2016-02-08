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

namespace pocketmine\item;

use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\entity\Minecart as MinecartEntity;
use pocketmine\block\Rail;
use pocketmine\block\RailBlock;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;

class Minecart extends Item{

    public function __construct($meta = 0, $count = 1){
        parent::__construct(self::MINECART, $meta, $count, "Minecart");
    }

    public function getMaxStackSize(){
        return 1;
    }

    public function canBeActivated(): bool{
        return true;
    }

    public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
        $blockTemp = $level->getBlock($block->add(0, -1, 0));
        //if(!$block instanceof RailBlock || !$block instanceof Rail) return false; in previuos version IM
        //if($blockTemp->getId() != self::RAIL and $blockTemp->getId() != self::POWERED_RAIL) return; in previuos version Genisys

        $minecart = new MinecartEntity($player->getLevel()->getChunk($block->getX() >> 4, $block->getZ() >> 4), new Compound("", array(
                "Pos" => new Enum("Pos", array(
                        new Double("", $block->getX()),
                        new Double("", $block->getY() + 1),
                        new Double("", $block->getZ())
                        )),
                "Motion" => new Enum("Motion", array(
                        new Double("", 0),
                        new Double("", 0),
                        new Double("", 0)
                        )),
                "Rotation" => new Enum("Rotation", array(
                        new Float("", 0),
                        new Float("", 0)
                        )),
                )));
        $minecart->spawnToAll();
        if($player->isSurvival()){
            $item = $player->getInventory()->getItemInHand();
            $count = $item->getCount();
            if(--$count <= 0){
                $player->getInventory()->setItemInHand(Item::get(Item::AIR));
                return;
            }

            $item->setCount($count);
            $player->getInventory()->setItemInHand($item);
        }

        return true;
    }
}
