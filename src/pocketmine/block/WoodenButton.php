<?php
/**
 * src/pocketmine/block/WoodenButton.php
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
use pocketmine\level\Level;
use pocketmine\level\sound\ButtonClickSound;
use pocketmine\level\sound\ButtonReturnSound;
use pocketmine\Player;

class WoodenButton extends Flowable implements Redstone, RedstoneSwitch
{

    protected $id = self::WOODEN_BUTTON;

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
    public function getPower()
    {
        if ($this->meta < 7) {
            return 0;
        }
        return 16;
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
    public function getName()
    {
        return "Wooden Button";
    }


    /**
     *
     * @return unknown
     */
    public function getHardness()
    {
        return 0.5;
    }



    /**
     *
     * @param unknown $type
     * @param unknown $power
     */
    public function BroadcastRedstoneUpdate($type, $power)
    {
        if ($this->meta > 7) {
            $pb = $this->meta ^ 0x08;
        } else {
            $pb = $this->meta;
        }
        if ($pb%2==0) {
            $pb++;
        } else {
            $pb--;
        }
        for ($side = 0; $side <= 5; $side++) {
            $around=$this->getSide($side);
            $this->getLevel()->setRedstoneUpdate($around, Block::REDSTONEDELAY, $type, $power);
            if ($side == $pb) {
                for ($side2 = 0; $side2 <= 5; $side2++) {
                    $around2=$around->getSide($side2);
                    $this->getLevel()->setRedstoneUpdate($around2, Block::REDSTONEDELAY, $type, $power);
                }
            }
        }
    }


    /**
     *
     * @param unknown $type
     * @return unknown
     */
    public function onUpdate($type)
    {
        if ($type === Level::BLOCK_UPDATE_SCHEDULED) {
            $this->togglePowered();
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_BREAK, 16);
            return;
        } elseif ($type === Level::BLOCK_UPDATE_NORMAL) {
            $lookDirection = [
                0 => 4,
                1 => 1,
                2 => 2,
                3 => 5,
                4 => 0,
                5 => 3
            ];

            if ($this->getSide($lookDirection[$this->getAttachedFace()])->isTransparent() === true) {
                $this->getLevel()->useBreakOn($this);
                return Level::BLOCK_UPDATE_NORMAL;
            }
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
        if ($target->isTransparent() === false) {
            $this->meta=$face;
            $this->getLevel()->setBlock($block, $this, true, true);

            return true;
        }

        return false;
    }


    /**
     *
     * @param Item    $item
     * @param Player  $player (optional)
     * @return unknown
     */
    public function onActivate(Item $item, Player $player = null)
    {
        if ($this->getPower()>0) {
            return true;
        }
        if (($player instanceof Player && !$player->isSneaking())||$player===null) {
            $this->togglePowered();
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE, $this->getPower());
            $this->getLevel()->scheduleUpdate($this, 50);
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
        return [[$this->id, 0, 1]];
    }


    /**
     *
     * @return unknown
     */
    public function isPowered()
    {
        return ($this->meta & 0x08) === 0x08;
    }


    /**
     * Toggles the current state of this button
     *
     *         bool
     *         whether or not the button is powered
     *
     * @param unknown
     */
    public function togglePowered()
    {
        $this->meta ^= 0x08;
        if ($this->isPowered()) {
            $this->getLevel()->addSound(new ButtonClickSound($this));
        } else {
            $this->getLevel()->addSound(new ButtonReturnSound($this, 1000));
        }
        $this->getLevel()->setBlock($this, $this);
    }


    /**
     * Gets the face that this block is attached on
     *
     * @return BlockFace attached to
     */
    public function getAttachedFace()
    {
        $data = intval($this->meta);
        if (($data & 0x08) === 0x08) { // remove power byte if powered
            $data ^= 0x08;
        }

        $faces = [
            5 => 0,
            0 => 1,
            3 => 2,
            4 => 3,
            1 => 4,
            2 => 5,
        ];
        return $faces[$data];
    }


    /**
     * Sets the direction this button is pointing toward
     *
     * @param unknown $face
     */
    public function setFacingDirection($face)
    {
        $data = ($this->meta ^ 0x08);
        $faces = [
            0 => 5,
            1 => 0,
            2 => 3,
            3 => 4,
            4 => 1,
            5 => 2,
        ];
        $face-=1;
        if ($face<0) {
            $face=5;
        }
        $this->setDamage($data |= $faces[$face]);
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
     * @return unknown
     */
    public function __toString()
    {
        return $this->getName() . " " . ($this->isPowered()?"":"NOT ") . "POWERED";
    }
}
