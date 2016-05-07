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

use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Item;
use pocketmine\entity\Projectile;
use pocketmine\entity\Vehicle;

/**
 * Called when a entity is despawned
 */
class EntityDespawnEvent extends EntityEvent
{
    public static $handlerList = null;

    private $entityType;

    /**
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->entityType = $entity::NETWORK_ID;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->entityType;
    }

    /**
     * @return bool
     */
    public function isCreature()
    {
        return $this->entity instanceof Creature;
    }

    /**
     * @return bool
     */
    public function isHuman()
    {
        return $this->entity instanceof Human;
    }

    /**
     * @return bool
     */
    public function isProjectile()
    {
        return $this->entity instanceof Projectile;
    }

    /**
     * @return bool
     */
    public function isVehicle()
    {
        return $this->entity instanceof Vehicle;
    }

    /**
     * @return bool
     */
    public function isItem()
    {
        return $this->entity instanceof Item;
    }
}
