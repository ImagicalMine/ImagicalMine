<?php
/**
 * src/pocketmine/block/SkullBlock.php
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
/*
 * THIS IS COPIED FROM THE PLUGIN FlowerPot MADE BY @beito123!!
 * https://github.com/beito123/PocketMine-MP-Plugins/blob/master/test%2FFlowerPot%2Fsrc%2Fbeito%2FFlowerPot%2Fomake%2FSkull.php
 *
 */

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\math\AxisAlignedBB;
use pocketmine\tile\Skull;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;

class SkullBlock extends Transparent
{

    protected $id = self::SKULL_BLOCK;

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
        return 1;
    }


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
     * @return unknown
     */
    public function getBoundingBox()
    {
        return new AxisAlignedBB(
            $this->x - 0.75,
            $this->y - 0.5,
            $this->z - 0.75,
            $this->x + 0.75,
            $this->y + 0.5,
            $this->z + 0.75
        );
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
        $down = $this->getSide(0);
        if ($face !== 0 && $fy > 0.5 && $target->getId() !== self::SKULL_BLOCK && !$down instanceof SkullBlock) {
            $this->getLevel()->setBlock($block, Block::get(Block::SKULL_BLOCK, 0), true, true);
            if ($face === 1) {
                $rot = new ByteTag("Rot", floor(($player->yaw * 16 / 360) + 0.5) & 0x0F);
            } else {
                $rot = new ByteTag("Rot", 0);
            }
            $nbt = new CompoundTag("", [
                    new StringTag("id", Tile::SKULL),
                    new IntTag("x", $block->x),
                    new IntTag("y", $block->y),
                    new IntTag("z", $block->z),
                    new ByteTag("SkullType", $item->getDamage()),
                    $rot
                ]);

            $chunk = $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4);
            $pot = Tile::createTile("Skull", $chunk, $nbt);
            $this->getLevel()->setBlock($block, Block::get(Block::SKULL_BLOCK, $face), true, true);
            return true;
        }
        return false;
    }


    /**
     *
     * @return unknown
     */
    public function getResistance()
    {
        return 5;
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        static $names = [
            0 => "Skeleton Skull",
            1 => "Wither Skeleton Skull",
            2 => "Zombie Head",
            3 => "Head",
            4 => "Creeper Head"
        ];
        return $names[$this->meta & 0x04];
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
    public function onBreak(Item $item)
    {
        $this->getLevel()->setBlock($this, new Air(), true, true, true);
        return true;
    }


    /**
     *
     * @param Item    $item
     * @return unknown
     */
    public function getDrops(Item $item)
    {
        if (($tile = $this->getLevel()->getTile($this)) instanceof Skull) {
            return [[Item::SKULL, $tile->getSkullType(), 1]];
        } else {
            return [[Item::SKULL, 0, 1]];
        }
    }
}
