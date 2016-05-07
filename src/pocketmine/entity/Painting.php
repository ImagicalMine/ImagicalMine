<?php
/**
 * src/pocketmine/entity/Painting.php
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

namespace pocketmine\entity;

use pocketmine\Player;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\protocol\AddPaintingPacket;

class Painting extends Hanging
{

    const NETWORK_ID = 83;
    public $height = 1;
    public $width = 1;
    public $lenght = 1;
    private $motive;

    /**
     *
     */
    public function initEntity()
    {
        $this->setMaxHealth(1);
        $this->setHealth($this->getMaxHealth());
        parent::initEntity();

        if (isset($this->namedtag->Motive)) {
            $this->motive = $this->namedtag["Motive"];
        } else {
            $this->close();
        }
    }


    /**
     *
     * @param Player  $player
     */
    public function spawnTo(Player $player)
    {
        $pk = new AddPaintingPacket();
        $pk->eid = $this->getId();
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->direction = $this->getDirection();
        $pk->title = $this->motive;
        $player->dataPacket($pk);
        parent::spawnTo($player);
    }


    /**
     *
     * @return unknown
     */
    public function getDrops()
    {
        return [ItemItem::get(ItemItem::PAINTING, 0, 1)];
    }
}
