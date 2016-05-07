<?php
/**
 * src/pocketmine/block/LitRedstoneLamp.php
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

use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;

class LitRedstoneLamp extends Solid implements Redstone, RedstoneConsumer
{

    protected $id = self::LIT_REDSTONE_LAMP;

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
    public function getToolType()
    {
        return Tool::TYPE_PICKAXE;
    }



    /**
     *
     * @param unknown $type
     */
    public function onUpdate($type)
    {
        if (!$this->isActivitedByRedstone() and !$this->isCharged() and !$this->isPoweredbyBlock()) {
            $this->id=123;
            $this->getLevel()->setBlock($this, $this, true, false);
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_BLOCK_UNCHARGE, 0);
        }
    }


    /**
     *
     * @param unknown $type
     * @param unknown $power
     */
    public function onRedstoneUpdate($type, $power)
    {
        if ($type == Level::REDSTONE_UPDATE_BLOCK_CHARGE) {
            return;
        }
        $isC=$this->isCharged();
        if ($isC) {
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_BLOCK_CHARGE, 1);
            return;
        }
        if (!$this->isActivitedByRedstone() and !$isC and !$this->isPoweredbyBlock()) {
            $this->id=123;
            $this->getLevel()->setBlock($this, $this, true, true);
            return;
        }
        return;
    }



    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return "Lit Redstone Lamp";
    }


    /**
     *
     * @return unknown
     */
    public function getHardness()
    {
        return 0.3;
    }



    /**
     *
     * @return unknown
     */
    public function getLightLevel()
    {
        return 15;
    }


    /**
     *
     * @param Item    $item
     * @return unknown
     */
    public function getDrops(Item $item)
    {
        $drops = [];
        $drops[] = [Item::REDSTONE_LAMP, 0, 1];
        return $drops;
    }
}
