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


use pocketmine\entity\Attribute;

class UpdateAttributesPacket extends DataPacket{
	const NETWORK_ID = Info::UPDATE_ATTRIBUTES_PACKET;


	public $entityId;
        public $minValue;
        public $maxValue;
        public $value;
        public $name;
	/** @var Attribute[] */
	public $entries = [];

	public function decode(){

	}

	public function encode(){
            $this->reset();

            $this->putLong($this->entityId);

            $this->putShort(1);

            $this->putFloat($this->minValue);
            $this->putFloat($this->maxValue);
            $this->putFloat($this->value);
            $this->putString($this->name);
	}

}
