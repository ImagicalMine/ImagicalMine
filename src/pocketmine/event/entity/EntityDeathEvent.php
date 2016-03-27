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

use pocketmine\entity\Living;
use pocketmine\item\Item;


class EntityDeathEvent extends EntityEvent{
	public static $handlerList = null;

	/** @var Item[] */
	private $drops = [];


	/**
	 * @param Living $entity
	 * @param Item[] $drops
	 */
	public function __construct(Living $entity, array $drops = []){
		$this->entity = $entity;
		$this->drops = $drops;
		if($entity->getLastDamageCause() instanceof EntityDamageByEntityEvent){
				$entity->getLevel()->addExperienceOrb($entity->add(0,1,0), mt_rand(3,11));
		}
	}

	/**
	 * @return Living
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * @return \pocketmine\item\Item[]
	 */
	public function getDrops(){
		return $this->drops;
	}

	/**
	 * @param Item[] $drops
	 */
	public function setDrops(array $drops){
		$this->drops = $drops;
	}

}
