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

use pocketmine\item\Item;

class ContainerSetSlotPacket extends DataPacket{
	const NETWORK_ID = Info::CONTAINER_SET_SLOT_PACKET;

	public $windowid;
	public $slot;
	public $hotbarSlot;
	/** @var Item */
	public $item;

	public function decode(){
		$this->windowid = $this->getByte();
		$this->slot = $this->getShort();
		$this->hotbarSlot = $this->getShort();
		$this->item = $this->getSlot();
	}

	public function encode(){
		$this->reset();
		$this->putByte($this->windowid);
		$this->putShort($this->slot);
		$this->putShort($this->hotbarSlot);
		$this->putSlot($this->item);
	}

}
