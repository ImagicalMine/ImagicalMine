<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\utils;

use pocketmine\utils\Binary;











use pocketmine\item\Item;


class BinaryStream extends \stdClass{

	public $offset;
	public $buffer;
	
	public function __construct($buffer = "", $offset = 0){
		$this->buffer = $buffer;
		$this->offset = $offset;
	}

	public function reset(){
		$this->buffer = "";
		$this->offset = 0;
	}

	public function setBuffer($buffer = \null, $offset = 0){
		$this->buffer = $buffer;
		$this->offset = (int) $offset;
	}

	public function getOffset(){
		return $this->offset;
	}

	public function getBuffer(){
		return $this->buffer;
	}

	public function get($len){
		if($len < 0){
			$this->offset = \strlen($this->buffer) - 1;
			return "";
		}elseif($len === \true){
			return \substr($this->buffer, $this->offset);
		}

		return $len === 1 ? $this->buffer{$this->offset++} : \substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	public function put($str){
		$this->buffer .= $str;
	}

	public function getLong(){
		return Binary::readLong($this->get(8));
	}

	public function putLong($v){
		$this->buffer .= Binary::writeLong($v);
	}

	public function getInt(){
		return (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
	}

	public function putInt($v){
		$this->buffer .= \pack("N", $v);
	}

	public function getLLong(){
		return Binary::readLLong($this->get(8));
	}

	public function putLLong($v){
		$this->buffer .= Binary::writeLLong($v);
	}

	public function getLInt(){
		return (\PHP_INT_SIZE === 8 ? \unpack("V", $this->get(4))[1] << 32 >> 32 : \unpack("V", $this->get(4))[1]);
	}

	public function putLInt($v){
		$this->buffer .= \pack("V", $v);
	}

	public function getSignedShort(){
		return (\PHP_INT_SIZE === 8 ? \unpack("n", $this->get(2))[1] << 48 >> 48 : \unpack("n", $this->get(2))[1] << 16 >> 16);
	}

	public function putShort($v){
		$this->buffer .= \pack("n", $v);
	}

	public function getShort(){
		return \unpack("n", $this->get(2))[1];
	}

	public function putSignedShort($v){
		$this->buffer .= \pack("n", $v);
	}

	public function getFloat(){
		return (\ENDIANNESS === 0 ? \unpack("f", $this->get(4))[1] : \unpack("f", \strrev($this->get(4)))[1]);
	}

	public function putFloat($v){
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $v) : \strrev(\pack("f", $v)));
	}

	public function getLShort($signed = \true){
		return $signed ? (\PHP_INT_SIZE === 8 ? \unpack("v", $this->get(2))[1] << 48 >> 48 : \unpack("v", $this->get(2))[1] << 16 >> 16) : \unpack("v", $this->get(2))[1];
	}

	public function putLShort($v){
		$this->buffer .= \pack("v", $v);
	}

	public function getLFloat(){
		return (\ENDIANNESS === 0 ? \unpack("f", \strrev($this->get(4)))[1] : \unpack("f", $this->get(4))[1]);
	}

	public function putLFloat($v){
		$this->buffer .= (\ENDIANNESS === 0 ? \strrev(\pack("f", $v)) : \pack("f", $v));
	}


	public function getTriad(){
		return \unpack("N", "\x00" . $this->get(3))[1];
	}

	public function putTriad($v){
		$this->buffer .= \substr(\pack("N", $v), 1);
	}


	public function getLTriad(){
		return \unpack("V", $this->get(3) . "\x00")[1];
	}

	public function putLTriad($v){
		$this->buffer .= \substr(\pack("V", $v), 0, -1);
	}

	public function getByte(){
		return \ord($this->buffer{$this->offset++});
	}

	public function putByte($v){
		$this->buffer .= \chr($v);
	}

	public function getDataArray($len = 10){
		$data = [];
		for($i = 1; $i <= $len and !$this->feof(); ++$i){
			$data[] = $this->get(\unpack("N", "\x00" . $this->get(3))[1]);
		}

		return $data;
	}

	public function putDataArray(array $data = []){
		foreach($data as $v){
			$this->buffer .= \substr(\pack("N", \strlen($v)), 1);
			$this->buffer .= $v;
		}
	}

	public function getUUID(){
		return UUID::fromBinary($this->get(16));
	}

	public function putUUID(UUID $uuid){
		$this->buffer .= $uuid->toBinary();
	}

	public function getSlot(){
		$id = (\PHP_INT_SIZE === 8 ? \unpack("n", $this->get(2))[1] << 48 >> 48 : \unpack("n", $this->get(2))[1] << 16 >> 16);
		
		if($id <= 0){
			return Item::get(0, 0, 0);
		}
		
		$cnt = \ord($this->get(1));
		
		$data = \unpack("n", $this->get(2))[1];
		
		$nbtLen = \unpack("n", $this->get(2))[1];
		
		$nbt = "";
		
		if($nbtLen > 0){
			$nbt = $this->get($nbtLen);
		}

		return Item::get(
			$id,
			$data,
			$cnt,
			$nbt
		);
	}

	public function putSlot(Item $item){
		if($item->getId() === 0){
			$this->buffer .= \pack("n", 0);
			return;
		}
		
		$this->buffer .= \pack("n", $item->getId());
		$this->buffer .= \chr($item->getCount());
		$this->buffer .= \pack("n", $item->getDamage() === \null ? -1 : $item->getDamage());
		$nbt = $item->getCompoundTag();
		$this->buffer .= \pack("n", \strlen($nbt));
		$this->buffer .= $nbt;
		
	}

	public function getString(){
		return $this->get(\unpack("n", $this->get(2))[1]);
	}

	public function putString($v){
		$this->buffer .= \pack("n", \strlen($v));
		$this->buffer .= $v;
	}

	public function feof(){
		return !isset($this->buffer{$this->offset});
	}
}
