<?php
/**
 * src/pocketmine/block/AnvilBlock.php
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

use pocketmine\inventory\AnvilInventory;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;

class AnvilBlock extends Fallable
{
    const TYPE_ANVIL = 0;
    const TYPE_SLIGHTLY_DAMAGED_ANVIL = 4;
    const TYPE_VERY_DAMAGED_ANVIL = 8;

    protected $id = self::ANVIL_BLOCK;

    /**
     *
     * @return unknown
     */
    public function isSolid()
    {
        return false;
    }


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
    public function canBeActivated()
    {
        return true;
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
    public function getResistance()
    {
        return 6000;
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        static $names = [
            self::TYPE_ANVIL => "Anvil",
                1 => "Anvil",
                2 => "Anvil",
                3 => "Anvil",
            self::TYPE_SLIGHTLY_DAMAGED_ANVIL => "Slighty Damaged Anvil",
                5 => "Slighty Damaged Anvil",
                6 => "Slighty Damaged Anvil",
                7 => "Slighty Damaged Anvil",
            self::TYPE_VERY_DAMAGED_ANVIL => "Very Damaged Anvil",
                9 => "Very Damaged Anvil",
                10 => "Very Damaged Anvil",
                11 => "Very Damaged Anvil"
        ];
        return $names[$this->meta];
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
     * @param Player  $player (optional)
     * @return unknown
     */
    public function onActivate(Item $item, Player $player = null)
    {
        if ($player instanceof Player) {
            if ($player->isCreative()) {
                return true;
            }

            $player->addWindow(new AnvilInventory($this));
        }

        return true;
    }


    /**
     *
     * @param Item    $item
     * @return unknown
     */
    public function getDrops(Item $item)
    {
        $damage = $this->getDamage();
        if ($item->isPickaxe() >= Tool::TIER_WOODEN) {
            if ($damage >= 0 && $damage <= 3) { //Anvil
                return [[$this->id, 0, 1]];
            } elseif ($damage >= 4 && $damage <= 7) {
                //Slightly Anvil
                return [[$this->id, $this->meta & 0x04, 1]];
            } elseif ($damage >= 8 && $damage <= 11) { //Very Damaged Anvil
                return [[$this->id, $this->meta & 0x08, 1]];
            }
        }
        return [];
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
        if ($target->isTransparent() === false) {
            $faces = [
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
            ];

            $damage = $this->getDamage();
            $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] & 0x04;

            if ($damage >= 0 && $damage <= 3) {
                $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
            } elseif ($damage >= 4 && $damage <= 7) {
                $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] | 0x04;
            } elseif ($damage >= 8 && $damage <= 11) {
                $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] | 0x08;
            }
            $this->getLevel()->setBlock($block, $this, true);
            return true;
        }
        return false;
    }
}
