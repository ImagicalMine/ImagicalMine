<?php
/**
 * src/pocketmine/block/RedstoneBlock.php
 *
 * @package default
 */


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
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\Player;

class RedstoneBlock extends Solid implements Redstone, RedstoneSource
{

    protected $id = self::REDSTONE_BLOCK;

    /**
     *
     */
    public function __construct()
    {
    }


    /**
     *
     * @return unknown
     */
    public function getHardness()
    {
        return 5;
    }



    /**
     *
     * @return unknown
     */
    public function getPower()
    {
        return 16;
    }



    /**
     *
     * @param unknown $type
     * @param unknown $power
     */
    public function BroadcastRedstoneUpdate($type, $power)
    {
        for ($side = 0; $side <= 5; $side++) {
            $around=$this->getSide($side);
            $this->getLevel()->setRedstoneUpdate($around, Block::REDSTONEDELAY, $type, $power);
        }
    }



    /**
     *
     * @param unknown $type
     * @param unknown $power
     */
    public function onRedstoneUpdate($type, $power)
    {
        if ($type == Level::REDSTONE_UPDATE_PLACE or $type == Level::REDSTONE_UPDATE_LOSTPOWER) {
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE, $this->getPower());
            return;
        }
        return;
    }



    /**
     *
     * @param Item    $item
     * @param Block   $block
     * @param Block   $target
     * @param unknown $face
     * @param unknown $fx
     * @param unknown $fy
     * @param unknown $fz
     * @param Player  $player (optional)
     * @return unknown
     */
    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null)
    {
        $o = $this->getLevel()->setBlock($this, $this, true, true);
        $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE, $this->getPower());
        return $o;
    }



    /**
     *
     * @return unknown
     */
    public function getToolType()
    {
        return Tool::TYPE_PICKAXE;
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return "Redstone Block";
    }



    /**
     *
     * @param Item    $item
     * @return unknown
     */
    public function onBreak(Item $item)
    {
        $oBreturn = $this->getLevel()->setBlock($this, new Air(), true, true);
        $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_BREAK, $this->getPower());
        return $oBreturn;
    }



    /**
     *
     * @param Item    $item
     * @return unknown
     */
    public function getDrops(Item $item)
    {
        if ($item->isPickaxe() >= Tool::TIER_WOODEN) {
            return [
                [Item::REDSTONE_BLOCK, 0, 1],
            ];
        } else {
            return [];
        }
    }
}
