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

class EntityDamageByChildEntityEvent extends EntityDamageByEntityEvent
{
    /** @var Entity */
    private $childEntity;


    /**
     * @param Entity    $damager
     * @param Entity    $childEntity
     * @param Entity    $entity
     * @param int       $cause
     * @param int|int[] $damage
     */
    public function __construct(Entity $damager, Entity $childEntity, Entity $entity, $cause, $damage)
    {
        $this->childEntity = $childEntity;
        parent::__construct($damager, $entity, $cause, $damage);
    }

    /**
     * @return Entity
     */
    public function getChild()
    {
        return $this->childEntity;
    }
}
