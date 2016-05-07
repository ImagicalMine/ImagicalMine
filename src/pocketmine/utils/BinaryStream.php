<?php
/**
 * src/pocketmine/utils/BinaryStream.php
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

//include <rules/DataPacket.h>

//ifndef COMPILE

//endif

use pocketmine\item\Item;

class BinaryStream extends \stdClass
{

    public $offset;
    public $buffer;



    /**
     *
     * @param unknown $buffer (optional)
     * @param unknown $offset (optional)
     */
    public function __construct($buffer = "", $offset = 0)
    {
        $this->buffer = $buffer;
        $this->offset = $offset;
    }


    /**
     *
     */
    public function reset()
    {
        $this->buffer = "";
        $this->offset = 0;
    }


    /**
     *
     * @param unknown $buffer (optional)
     * @param unknown $offset (optional)
     */
    public function setBuffer($buffer = null, $offset = 0)
    {
        $this->buffer = $buffer;
        $this->offset = (int) $offset;
    }


    /**
     *
     * @return unknown
     */
    public function getOffset()
    {
        return $this->offset;
    }


    /**
     *
     * @return unknown
     */
    public function getBuffer()
    {
        return $this->buffer;
    }


    /**
     *
     * @param unknown $len
     * @return unknown
     */
    public function get($len)
    {
        if ($len < 0) {
            $this->offset = strlen($this->buffer) - 1;
            return "";
        } elseif ($len === true) {
            return substr($this->buffer, $this->offset);
        }

        return $len === 1 ? $this->buffer{$this->offset++}
        : substr($this->buffer, ($this->offset += $len) - $len, $len);
    }


    /**
     *
     * @param unknown $str
     */
    public function put($str)
    {
        $this->buffer .= $str;
    }


    /**
     *
     * @return unknown
     */
    public function getLong()
    {
        return Binary::readLong($this->get(8));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLong($v)
    {
        $this->buffer .= Binary::writeLong($v);
    }


    /**
     *
     * @return unknown
     */
    public function getInt()
    {
        return Binary::readInt($this->get(4));
    }


    /**
     *
     * @param unknown $v
     */
    public function putInt($v)
    {
        $this->buffer .= Binary::writeInt($v);
    }


    /**
     *
     * @return unknown
     */
    public function getLLong()
    {
        return Binary::readLLong($this->get(8));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLLong($v)
    {
        $this->buffer .= Binary::writeLLong($v);
    }


    /**
     *
     * @return unknown
     */
    public function getLInt()
    {
        return Binary::readLInt($this->get(4));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLInt($v)
    {
        $this->buffer .= Binary::writeLInt($v);
    }


    /**
     *
     * @return unknown
     */
    public function getSignedShort()
    {
        return Binary::readSignedShort($this->get(2));
    }


    /**
     *
     * @param unknown $v
     */
    public function putShort($v)
    {
        $this->buffer .= Binary::writeShort($v);
    }


    /**
     *
     * @return unknown
     */
    public function getShort()
    {
        return Binary::readShort($this->get(2));
    }


    /**
     *
     * @param unknown $v
     */
    public function putSignedShort($v)
    {
        $this->buffer .= Binary::writeShort($v);
    }


    /**
     *
     * @return unknown
     */
    public function getFloat()
    {
        return Binary::readFloat($this->get(4));
    }


    /**
     *
     * @param unknown $v
     */
    public function putFloat($v)
    {
        $this->buffer .= Binary::writeFloat($v);
    }


    /**
     *
     * @param unknown $signed (optional)
     * @return unknown
     */
    public function getLShort($signed = true)
    {
        return $signed ? Binary::readSignedLShort($this->get(2)) : Binary::readLShort($this->get(2));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLShort($v)
    {
        $this->buffer .= Binary::writeLShort($v);
    }


    /**
     *
     * @return unknown
     */
    public function getLFloat()
    {
        return Binary::readLFloat($this->get(4));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLFloat($v)
    {
        $this->buffer .= Binary::writeLFloat($v);
    }


    /**
     *
     * @return unknown
     */
    public function getTriad()
    {
        return Binary::readTriad($this->get(3));
    }


    /**
     *
     * @param unknown $v
     */
    public function putTriad($v)
    {
        $this->buffer .= Binary::writeTriad($v);
    }


    /**
     *
     * @return unknown
     */
    public function getLTriad()
    {
        return Binary::readLTriad($this->get(3));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLTriad($v)
    {
        $this->buffer .= Binary::writeLTriad($v);
    }


    /**
     *
     * @return unknown
     */
    public function getByte()
    {
        return ord($this->buffer{$this->offset++});
    }


    /**
     *
     * @param unknown $v
     */
    public function putByte($v)
    {
        $this->buffer .= chr($v);
    }


    /**
     *
     * @param unknown $len (optional)
     * @return unknown
     */
    public function getDataArray($len = 10)
    {
        $data = [];
        for ($i = 1; $i <= $len and !$this->feof(); ++$i) {
            $data[] = $this->get($this->getTriad());
        }

        return $data;
    }


    /**
     *
     * @param array   $data (optional)
     */
    public function putDataArray(array $data = [])
    {
        foreach ($data as $v) {
            $this->putTriad(strlen($v));
            $this->put($v);
        }
    }


    /**
     *
     * @return unknown
     */
    public function getUUID()
    {
        return UUID::fromBinary($this->get(16));
    }


    /**
     *
     * @param UUID    $uuid
     */
    public function putUUID(UUID $uuid)
    {
        $this->put($uuid->toBinary());
    }


    /**
     *
     * @return unknown
     */
    public function getSlot()
    {
        $id = $this->getSignedShort();

        if ($id <= 0) {
            return Item::get(0, 0, 0);
        }

        $cnt = $this->getByte();

        $data = $this->getShort();

        $nbtLen = $this->getLShort();

        $nbt = "";

        if ($nbtLen > 0) {
            $nbt = $this->get($nbtLen);
        }

        return Item::get(
            $id,
            $data,
            $cnt,
            $nbt
        );
    }


    /**
     *
     * @param Item    $item
     */
    public function putSlot(Item $item)
    {
        if ($item->getId() === 0) {
            $this->putShort(0);
            return;
        }

        $this->putShort($item->getId());
        $this->putByte($item->getCount());
        $this->putShort($item->getDamage() === null ? -1 : $item->getDamage());
        $nbt = $item->getCompoundTag();
        $this->putLShort(strlen($nbt));
        $this->put($nbt);
    }


    /**
     *
     * @return unknown
     */
    public function getString()
    {
        return $this->get($this->getShort());
    }


    /**
     *
     * @param unknown $v
     */
    public function putString($v)
    {
        $this->putShort(strlen($v));
        $this->put($v);
    }


    /**
     *
     * @return unknown
     */
    public function feof()
    {
        return !isset($this->buffer{$this->offset});
    }
}
