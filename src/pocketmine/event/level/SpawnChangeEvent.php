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

namespace pocketmine\event\level;

use pocketmine\level\Level;
use pocketmine\level\Position;

/**
 * An event that is called when a level spawn changes.
 * The previous spawn is included
 */
class SpawnChangeEvent extends LevelEvent{
	public static $handlerList = null;

	/** @var Position */
	private $previousSpawn;

	/**
	 * @param Level    $level
	 * @param Position $previousSpawn
	 */
	public function __construct(Level $level, Position $previousSpawn){
		parent::__construct($level);
		$this->previousSpawn = $previousSpawn;
	}

	/**
	 * @return Position
	 */
	public function getPreviousSpawn(){
		return $this->previousSpawn;
	}
}