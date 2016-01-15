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

namespace pocketmine\network\protocol;

#include <rules/DataPacket.h>


class StartGamePacket extends DataPacket{
	const NETWORK_ID = Info::START_GAME_PACKET;

	public $seed;
	public $dimension;
	public $generator;
	public $gamemode;
	public $eid;
	public $spawnX;
	public $spawnY;
	public $spawnZ;
	public $x;
	public $y;
	public $z;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putInt($this->seed);
		$this->putByte($this->dimension);
		$this->putInt($this->generator);
		$this->putInt($this->gamemode);
		$this->putLong($this->eid);
		$this->putInt($this->spawnX);
		$this->putInt($this->spawnY);
		$this->putInt($this->spawnZ);
		$this->putFloat($this->x);
		$this->putFloat($this->y);
		$this->putFloat($this->z);
		$this->putByte(0);
	}

}
