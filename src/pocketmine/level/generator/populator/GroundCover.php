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

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\biome\Biome;
use pocketmine\utils\Random;

class GroundCover extends Populator{

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		$chunk = $level->getChunk($chunkX, $chunkZ);
		for($x = 0; $x < 16; ++$x){
			for($z = 0; $z < 16; ++$z){
				$biome = Biome::getBiome($chunk->getBiomeId($x, $z));
				$cover = $biome->getGroundCover();
				if(count($cover) > 0){
					$diffY = 0;
					if(!$cover[0]->isSolid()){
						$diffY = 1;
					}

					$column = $chunk->getBlockIdColumn($x, $z);
					for($y = 127; $y > 0; --$y){
						if($column{$y} !== "\x00" and !Block::get(ord($column{$y}))->isTransparent()){
							break;
						}
					}
					$startY = min(127, $y + $diffY);
					$endY = $startY - count($cover);
					for($y = $startY; $y > $endY and $y >= 0; --$y){
						$b = $cover[$startY - $y];
						if($column{$y} === "\x00" and $b->isSolid()){
							break;
						}
						if($b->getDamage() === 0){
							$chunk->setBlockId($x, $y, $z, $b->getId());
						}else{
							$chunk->setBlock($x, $y, $z, $b->getId(), $b->getDamage());
						}
					}
				}
			}
		}
	}
}