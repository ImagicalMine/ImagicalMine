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

namespace pocketmine\item;

class CookedFish extends Food{
	const NORMAL = 0;
	const SALMON = 1;

	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_FISH);
		$this->meta = $meta;
		$this->name = $this->getMetaName();
	}

	public function getMetaName(){
		static $names = [self::NORMAL => "Cooked Fish",self::SALMON => "Cooked Salmon",2 => "Unknown Cooked Fish"];
		return $names[$this->meta & 0x02];
	}

	public function getSaturation(){
		return ($this->meta === self::NORMAL)?5:(($this->meta === self::SALMON)?6:0);
	}
}