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

#ifndef COMPILE
use pocketmine\utils\Binary;

#endif

class AddPlayerPacket extends DataPacket
{
    const NETWORK_ID = Info::ADD_PLAYER_PACKET;

    public $uuid;
    public $username;
    public $eid;
    public $x;
    public $y;
    public $z;
    public $speedX;
    public $speedY;
    public $speedZ;
    public $pitch;
    public $yaw;
    public $item;
    public $metadata;

    public function decode()
    {
    }

    public function encode()
    {
        $this->reset();
        $this->putUUID($this->uuid);
        $this->putString($this->username);
        $this->putLong($this->eid);
        $this->putFloat($this->x);
        $this->putFloat($this->y);
        $this->putFloat($this->z);
        $this->putFloat($this->speedX);
        $this->putFloat($this->speedY);
        $this->putFloat($this->speedZ);
        $this->putFloat($this->yaw);
        $this->putFloat($this->yaw += $this->pitch);
        $this->putFloat($this->pitch);
        $this->putSlot($this->item);

        $meta = Binary::writeMetadata($this->metadata);
        $this->put($meta);
    }
}
