<?php
/**
 * src/pocketmine/block/TNT.php
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

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\sound\TNTPrimeSound;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\utils\Random;
use pocketmine\item\FlintSteel;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

class TNT extends Solid implements RedstoneConsumer
{

    protected $id = self::TNT;

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
    public function getName()
    {
        return "TNT";
    }


    /**
     *
     * @return unknown
     */
    public function getHardness()
    {
        return 0;
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
     * @param Item    $item
     * @param Player  $player (optional)
     * @return unknown
     */
    public function onActivate(Item $item, Player $player = null)
    {
        if ($item->getId() === Item::FLINT_STEEL) {
            $item->useOn($this);
            $this->getLevel()->setBlock($this, new Air(), true);

            $mot = (new Random())->nextSignedFloat() * M_PI * 2;
            $tnt = Entity::createEntity("PrimedTNT", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), new CompoundTag("", [
                        "Pos" => new ListTag("Pos", [
                                new DoubleTag("", $this->x + 0.5),
                                new DoubleTag("", $this->y),
                                new DoubleTag("", $this->z + 0.5)
                            ]),
                        "Motion" => new ListTag("Motion", [
                                new DoubleTag("", -sin($mot) * 0.02),
                                new DoubleTag("", 0.2),
                                new DoubleTag("", -cos($mot) * 0.02)
                            ]),
                        "Rotation" => new ListTag("Rotation", [
                                new FloatTag("", 0),
                                new FloatTag("", 0)
                            ]),
                        "Fuse" => new ByteTag("Fuse", 80)
                    ]));

            $tnt->spawnToAll();

            $this->level->addSound(new TNTPrimeSound($this));

            return true;
        }

        return false;
    }


    /**
     *
     * @param unknown $type
     * @param unknown $power
     */
    public function onRedstoneUpdate($type, $power)
    {
        if ($type == Level::REDSTONE_UPDATE_BLOCK_UNCHARGE) {
            return;
        }
        if ($type == Level::REDSTONE_UPDATE_BLOCK_CHARGE or $this->isCharged()) {
            $this->onActivate(new FlintSteel());
            return;
        }
    }
}
