<?php

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
 * @link http://forums.imagicalmine.net/
 *
 *
*/

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\level\Position;

class EntityTeleportEvent extends EntityEvent implements Cancellable
{
    public static $handlerList = null;

    /** @var Position */
    private $from;
    /** @var Position */
    private $to;

    public function __construct(Entity $entity, Position $from, Position $to)
    {
        $this->entity = $entity;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return Position
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param Position $from
     */
    public function setFrom(Position $from)
    {
        $this->from = $from;
    }

    /**
     * @return Position
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param Position $to
     */
    public function setTo(Position $to)
    {
        $this->to = $to;
    }
}
