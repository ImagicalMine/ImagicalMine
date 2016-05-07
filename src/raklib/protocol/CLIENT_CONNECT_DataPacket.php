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

class CLIENT_CONNECT_DataPacket extends Packet
{
    public static $ID = 0x09;

    public $clientID;
    public $sendPing;
    public $useSecurity = false;

    public function encode()
    {
        parent::encode();
        $this->buffer .= Binary::writeLong($this->clientID);
        $this->buffer .= Binary::writeLong($this->sendPing);
        $this->buffer .= chr($this->useSecurity ? 1 : 0);
    }

    public function decode()
    {
        parent::decode();
        $this->clientID = Binary::readLong($this->get(8));
        $this->sendPing = Binary::readLong($this->get(8));
        $this->useSecurity = ord($this->get(1)) > 0;
    }
}
