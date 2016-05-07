<?php
/**
 * src/pocketmine/block/ExtendedRailBlock.php
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
use pocketmine\Player;
use pocketmine\math\Vector3;

abstract class ExtendedRailBlock extends RailBlock
{

    /**
     *
     * @param unknown $face
     * @param unknown $isOnSlope (optional)
     */
    public function setDirection($face, $isOnSlope = false)
    {
        $extrabitset = (($this->meta & 0x08) === 0x08);
        if ($face !== Vector3::SIDE_WEST && $face !== Vector3::SIDE_EAST && $face !== Vector3::SIDE_NORTH && $face !== Vector3::SIDE_SOUTH) {
            throw new IllegalArgumentException("This rail variant can't be on a curve!");
        }
        $this->meta = ($extrabitset?($this->meta | 0x08):($this->meta & ~0x08));
        $this->getLevel()->setBlock($this, Block::get($this->id, $this->meta));
    }


    /**
     *
     * @return unknown
     */
    public function isCurve()
    {
        return false;
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
        $down = $block->getSide(Vector3::SIDE_DOWN);
        if ($down->isTransparent() === false) {
            $this->getLevel()->setBlock($this, Block::get($this->id, 0));
            $up = $block->getSide(Vector3::SIDE_UP);
            if ($block->getSide(Vector3::SIDE_EAST) instanceof RailBlock && $block->getSide(Vector3::SIDE_WEST) instanceof RailBlock) {
                if ($up->getSide(Vector3::SIDE_EAST) instanceof RailBlock) {
                    $this->setDirection(Vector3::SIDE_EAST, true);
                } elseif ($up->getSide(Vector3::SIDE_WEST) instanceof RailBlock) {
                    $this->setDirection(Vector3::SIDE_WEST, true);
                } else {
                    $this->setDirection(Vector3::SIDE_EAST);
                }
            } elseif ($block->getSide(Vector3::SIDE_SOUTH) instanceof RailBlock && $block->getSide(Vector3::SIDE_NORTH) instanceof RailBlock) {
                if ($up->getSide(Vector3::SIDE_SOUTH) instanceof RailBlock) {
                    $this->setDirection(Vector3::SIDE_SOUTH, true);
                } elseif ($up->getSide(Vector3::SIDE_NORTH) instanceof RailBlock) {
                    $this->setDirection(Vector3::SIDE_NORTH, true);
                } else {
                    $this->setDirection(Vector3::SIDE_SOUTH);
                }
            } else {
                $this->setDirection(Vector3::SIDE_NORTH);
            }
            return true;
        }
        return false;
    }
}
