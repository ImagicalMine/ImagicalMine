<?php
/**
 * src/pocketmine/block/Quartz.php
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
use pocketmine\Player;

class Quartz extends Solid
{

    const QUARTZ_NORMAL = 0;
    const QUARTZ_CHISELED = 1;
    const QUARTZ_PILLAR = 2;
    const QUARTZ_PILLAR2 = 3;

    protected $id = self::QUARTZ_BLOCK;

    /**
     *
     * @param unknown $meta (optional)
     */
    public function __construct($meta = 0)
    {
        $this->meta = $meta;
    }


    /**
     *
     * @return unknown
     */
    public function getHardness()
    {
        return 0.8;
    }



    /**
     *
     * @return unknown
     */
    public function getName()
    {
        static $names = [
            self::QUARTZ_NORMAL => "Quartz Block",
            self::QUARTZ_CHISELED => "Chiseled Quartz Block",
            self::QUARTZ_PILLAR => "Quartz Pillar",
            self::QUARTZ_PILLAR2 => "Quartz Pillar",
        ];
        return $names[$this->meta & 0x03];
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
        $faces = [
            0 => 0,
            1 => 0,
            2 => 0b1000,
            3 => 0b1000,
            4 => 0b0100,
            5 => 0b0100,
        ];

        $this->meta = ($this->meta & 0x03) | $faces[$face];
        $this->getLevel()->setBlock($block, $this, true, true);

        return true;
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
     * @param Item    $item
     * @return unknown
     */
    public function getDrops(Item $item)
    {
        if ($item->isPickaxe() >= Tool::TIER_WOODEN) {
            return [
                [Item::QUARTZ_BLOCK, $this->meta & 0x03, 1],
            ];
        } else {
            return [];
        }
    }
}
