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
 * @link http://forums.imagicalcorp.ml/
 * 
 *
*/

namespace pocketmine\utils;


/**
 * Unsecure Random Number Noise, used for fast seeded values
 */
class Random{

	protected $seed;

	/**
	 * @param int $seed Integer to be used as seed.
	 */
	public function __construct($seed = -1){
		if($seed == -1){
			$seed = time();
		}

		$this->setSeed($seed);
	}

	/**
	 * @param int $seed Integer to be used as seed.
	 */
	public function setSeed($seed){
		$this->seed = crc32(pack("N", $seed));
	}

	/**
	 * Returns an 31-bit integer (not signed)
	 *
	 * @return int
	 */
	public function nextInt(){
		return $this->nextSignedInt() & 0x7fffffff;
	}

	/**
	 * Returns a 32-bit integer (signed)
	 *
	 * @return int
	 */
	public function nextSignedInt(){
		$t = ((($this->seed * 65535) + 31337) >> 8) + 1337;
		if(PHP_INT_SIZE === 8){
			$t = $t << 32 >> 32;
		}
		$this->seed ^= $t;

		return $t;
	}

	/**
	 * Returns a float between 0.0 and 1.0 (inclusive)
	 *
	 * @return float
	 */
	public function nextFloat(){
		return $this->nextInt() / 0x7fffffff;
	}

	/**
	 * Returns a float between -1.0 and 1.0 (inclusive)
	 *
	 * @return float
	 */
	public function nextSignedFloat(){
		return $this->nextSignedInt() / 0x7fffffff;
	}

	/**
	 * Returns a random boolean
	 *
	 * @return bool
	 */
	public function nextBoolean(){
		return ($this->nextSignedInt() & 0x01) === 0;
	}

	/**
	 * Returns a random integer between $start and $end
	 *
	 * @param int $start default 0
	 * @param int $end   default 0x7fffffff
	 *
	 * @return int
	 */
	public function nextRange($start = 0, $end = 0x7fffffff){
		return $start + ($this->nextInt() % ($end + 1 - $start));
	}

	public function nextBoundedInt($bound){
		return $this->nextInt() % $bound;
	}

}