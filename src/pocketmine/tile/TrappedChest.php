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

namespace pocketmine\tile;

use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Int;
use pocketmine\nbt\tag\String;

class TrappedChest extends Chest{
	public function close(){
		if($this->closed === false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
			}

			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getRealInventory());
			}
			parent::close();
		}
	}

	public function getName(){
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Trapped chest";
	}

	public function getSpawnCompound(){
		if($this->isPaired()){
			$c = new Compound("", [
				new String("id", Tile::TRAPPED_CHEST),
				new Int("x", (int) $this->x),
				new Int("y", (int) $this->y),
				new Int("z", (int) $this->z),
				new Int("pairx", (int) $this->namedtag["pairx"]),
				new Int("pairz", (int) $this->namedtag["pairz"])
			]);
		}else{
			$c = new Compound("", [
				new String("id", Tile::TRAPPED_CHEST),
				new Int("x", (int) $this->x),
				new Int("y", (int) $this->y),
				new Int("z", (int) $this->z)
			]);
		}

		if($this->hasName()){
			$c->CustomName = $this->namedtag->CustomName;
		}

		return $c;
	}
}
