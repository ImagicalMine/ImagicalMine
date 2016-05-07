<?php
/**
 * src/pocketmine/block/WoodenPressurePlate.php
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
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\item\Tool;

class WoodenPressurePlate extends Transparent implements Redstone, RedstoneSwitch
{

    protected $id = self::WOODEN_PRESSURE_PLATE;
    public $activationtime = 10; // how many redstoneticks they need
    public $deactivationtime = 5;

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
    public function hasEntityCollision()
    {
        return true;
    }



    /**
     *
     * @return unknown
     */
    public function getToolType()
    {
        return Tool::TYPE_AXE;
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return "Wooden Pressure Plate";
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
     * @return unknown
     */
    public function getPower()
    {
        return $this->isPowered()?16:0;
    }





    /**
     *
     * @param unknown $type
     * @return unknown
     */
    public function onUpdate($type)
    {
        $down = $this->getSide(0);
        if ($type === Level::BLOCK_UPDATE_SCHEDULED) {
            if ($this->isPowered() && !$this->isEntityCollided()) {
                $this->togglePowered();
            }
        } elseif ($type === Level::BLOCK_UPDATE_NORMAL) {
            if ($down->isTransparent() === true && !$down instanceof Fence/* && !$down instanceof Stair && !$down instanceof Slab*/) {
                $this->getLevel()->useBreakOn($this);
                return Level::BLOCK_UPDATE_NORMAL;
            }
        }
        return false;
    }


    /**
     *
     * @param Entity  $entity
     */
    public function onEntityCollide(Entity $entity)
    {
        if (!$this->isPowered()) {
            $this->togglePowered();
            $this->getLevel()->scheduleUpdate($this, 50);
        }
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
        if ($down->isTransparent() === false || $down instanceof Fence/* || $down instanceof Stair || $down instanceof Slab*/) {
            $this->getLevel()->setBlock($block, $this, true, true);
            return true;
        }

        return false;
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
        return ($this->meta & 0x01) === 0x01;
    }



    /**
     *
     * @return unknown
     */
    public function isEntityCollided()
    {
        foreach ($this->getLevel()->getChunk($this->x >> 4, $this->z >> 4)->getEntities() as $entity) {
            if ($this->getLevel()->getBlock($entity->getPosition()) === $this) {
                return true;
            }
        }
        return false;
    }


    /**
     * Toggles the current state of this plate
     */
    public function togglePowered()
    {
        $this->meta ^= 0x01;
        $this->isPowered()?$this->power=15:$this->power=0;
        if ($this->isPowered()) {
            $this->getLevel()->addSound(new ButtonClickSound($this));
            $type = Level::REDSTONE_UPDATE_PLACE;
        } else {
            $this->getLevel()->addSound(new ButtonReturnSound($this, 1000));
            $type = Level::REDSTONE_UPDATE_BREAK;
        }
        $this->getLevel()->setBlock($this, $this, true);
        $this->BroadcastRedstoneUpdate($type, 16);
        $this->getSide(0)->BroadcastRedstoneUpdate($type, 16);
    }
}
