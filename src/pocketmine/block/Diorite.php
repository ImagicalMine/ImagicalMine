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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Diorite extends Solid{

	protected $id = 1;

	public function __construct(int $meta = 3){
		$this->meta = $meta;
	}

	public function getHardness() : int{
		return 1.5;
	}

	public function getToolType() : int{
		return Tool::TYPE_PICKAXE;
	}

	public function getName() : string{
		return "Diorite";
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_WOODEN){
			return [
				[Item::DIORITE, $this->meta, 1],
			];
		}else{
			return [];
		}
	}

}
