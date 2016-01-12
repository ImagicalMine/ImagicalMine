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


class CraftingEventPacket extends DataPacket{
	const NETWORK_ID = Info::CRAFTING_EVENT_PACKET;

	public $windowId;
	public $type;
	public $id;
	public $input = [];
	public $output = [];

	public function clean(){
		$this->input = [];
		$this->output = [];
		return parent::clean();
	}

	public function decode(){
		$this->windowId = $this->getByte();
		$this->type = $this->getInt();
		$this->id = $this->getUUID();

		$size = $this->getInt();
		for($i = 0; $i < $size and $i < 128; ++$i){
			$this->input[] = $this->getSlot();
		}

		$size = $this->getInt();
		for($i = 0; $i < $size and $i < 128; ++$i){
			$this->output[] = $this->getSlot();
		}
	}

	public function encode(){

	}

}
