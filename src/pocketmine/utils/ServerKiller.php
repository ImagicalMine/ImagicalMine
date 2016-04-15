<?php
/**
 * src/pocketmine/utils/ServerKiller.php
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

namespace pocketmine\utils;

use pocketmine\Thread;

class ServerKiller extends Thread{

	public $time;

	/**
	 *
	 * @param unknown $time (optional)
	 */
	public function __construct($time = 15) {
		$this->time = $time;
	}


	/**
	 *
	 */
	public function run() {
		$start = time() + 1;
		$this->synchronized(function() {
				$this->wait($this->time * 1000000);
			});
		if (time() - $start >= $this->time) {
			echo "\nTook too long to stop, server was killed forcefully!\n";
			@\pocketmine\kill(getmypid());
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getThreadName() {
		return "Server Killer";
	}


}
