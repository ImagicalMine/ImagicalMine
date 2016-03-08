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

namespace raklib\protocol;

use raklib\Binary;









class CLIENT_HANDSHAKE_DataPacket extends Packet{
    public static $ID = 0x13;

    public $address;
    public $port;
    
    public $systemAddresses = [];
    
    public $sendPing;
    public $sendPong;

    public function encode(){
        
    }

    public function decode(){
        parent::decode();
        $this->getAddress($this->address, $this->port);
         for($i = 0; $i < 10; ++$i){
			$this->getAddress($addr, $port, $version);
			$this->systemAddresses[$i] = [$addr, $port, $version];
		}
		
        $this->sendPing = Binary::readLong($this->get(8));
        $this->sendPong = Binary::readLong($this->get(8));
    }
}
