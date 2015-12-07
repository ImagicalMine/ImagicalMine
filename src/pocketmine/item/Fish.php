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

namespace pocketmine\item;


class Fish extends Item implements Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_FISH, $meta, $count, "Raw Fish");
		if($meta === 1){
			$this->name = "Raw Salmon";
		}elseif($meta === 2){
			$this->name = "Clownfish";
		}elseif($meta === 3){
			$this->name = "Pufferfish";
		}
	}

        public function isEatable() {
                return true;
        }

}
