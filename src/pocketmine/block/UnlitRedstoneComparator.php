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

use pocketmine\item\Item;
use pocketmine\Player;

class UnlitRedstoneComparator extends Solid{

    protected $id = self::UNLIT_REDSTONE_COMPARATOR;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function isSolid(){
        return true;
    }

    public function getName(){
        return "Redstone Comparator";
    }

    public function canBeActivated(){
        return true;
    }

    public function getHardness(){
        return 0.1;
    }

    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
        $faces = [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
        ];

        $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

        $this->getLevel()->setBlock($block, $this, true);
        return true;
    }

    public function getDrops(Item $item){
        return [[Item::REDSTONE_COMPARATOR_ITEM, 0, 1]];
    }
}