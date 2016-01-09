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
use pocketmine\math\Vector3;
use pocketmine\Player;

class Rail extends RailBlock{
	protected $id = self::RAIL;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Rail";
	}
		
	protected function update(){
		$status = 3;
		$x = 0;
		$z = 0;
		$north = $this->canConnect($this->getSide(Vector3::SIDE_NORTH));
		$south = $this->canConnect($this->getSide(Vector3::SIDE_SOUTH));
		$west = $this->canConnect($this->getSide(Vector3::SIDE_WEST));
		$east = $this->canConnect($this->getSide(Vector3::SIDE_EAST));
		
		if($east == 1 and $west == 1 and $north == 0 and $south == 0){
			$status = 2;
		}elseif($east == 0 and $west == 0 and $north == 1 and $south == 1){
			$status = 1;
		}
		if($status == 3){
			$status = false;
			if($north == 1 and $south == 0){
				$z = 1;
			}elseif($north == 0 and $south == 1){
				$z = 2;
			}
			if($west == 1 and $east == 0){
				$x = 1;
			}elseif($west == 0 and $east == 1){
				$x = 2;
			}
		}
		if($status != false){
			return ((($status + 1) % 2) & 0x03);
		}else{
			if(     $z == 1 and ($x == 1 or $this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP)) or $this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN)))){
				return 8;//echo("|-");//8
			}elseif($z == 1 and ($x == 2 or $this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP)) or $this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN)))){
				return 9;//echo("-|");//9
			}elseif($x == 1 and ($z == 2 or $this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP)) or $this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN)))){
				return 7;//echo("|_");//7
			}elseif($x == 2 and ($z == 2 or $this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP)) or $this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN)))){
				return 6;//echo("_|");//6
			}elseif($x == 0 and ($z == 1 or $z == 2)){
				if($z == 1){
					if($this->isBlock($this->getSide(Vector3::SIDE_SOUTH))){
						if($this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP))){
							return 5; // /
						}
					}else{
						if($this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN))){
							return 0; // \
						}
					}
				}
				if($z == 2){
					if($this->isBlock($this->getSide(Vector3::SIDE_NORTH))){
						if($this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP))){
							return 4; // /
						}
					}else{
						if($this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN))){
							return 0; // \
						}
					}
				}
				return 0;//return 1;//echo("|");
			}elseif($z == 0 and ($x == 1 or $x == 2)){
				if($x == 1){	
					if($this->isBlock($this->getSide(Vector3::SIDE_EAST))){
						if($this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP))){
							return 2; // /
						}
					}else{
						if($this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN))){
							return 1; // \
						}
					
					}
				}
				if($x == 2){	
					if($this->isBlock($this->getSide(Vector3::SIDE_WEST))){
						if($this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP))){
							return 3; // /
						}
					}else{
						if($this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN))){
							return 1; // \
						}
					}
				}
				return 1;//return 2;//echo("-");
			}elseif($x == 0 and $z == 0){
				if($this->isBlock($this->getSide(Vector3::SIDE_SOUTH))){
					if($this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP))){
						return 5; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN))){
						return 0; // \
					}
				}
				if($this->isBlock($this->getSide(Vector3::SIDE_NORTH))){
					if($this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP))){
						return 4; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN))){
						return 0; // \
					}
				}
				if($this->isBlock($this->getSide(Vector3::SIDE_EAST))){
					if($this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP))){
						return 2; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN))){
						return 1; // \
					}
				}
				if($this->isBlock($this->getSide(Vector3::SIDE_WEST))){
					if($this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP))){
						return 3; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN))){
						return 1; // \
					}
				}
			}
		}
		return false;
	}
	
	protected function updateOther(){
		$this->updateSideRail($this->getSide(Vector3::SIDE_NORTH));
		$this->updateSideRail($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP));
		$this->updateSideRail($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN));
		$this->updateSideRail($this->getSide(Vector3::SIDE_SOUTH));
		$this->updateSideRail($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP));
		$this->updateSideRail($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN));
		$this->updateSideRail($this->getSide(Vector3::SIDE_WEST));
		$this->updateSideRail($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP));
		$this->updateSideRail($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN));
		$this->updateSideRail($this->getSide(Vector3::SIDE_EAST));
		$this->updateSideRail($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP));
		$this->updateSideRail($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN));
	}
	
	protected function updateSideRail(Block $block){
		$status = 3;
		$x = 0;
		$z = 0;
		$final = 0;
		$north = $this->canConnect($block->getSide(Vector3::SIDE_NORTH));
		$south = $this->canConnect($block->getSide(Vector3::SIDE_SOUTH));
		$west = $this->canConnect($block->getSide(Vector3::SIDE_WEST));
		$east = $this->canConnect($block->getSide(Vector3::SIDE_EAST));
		
		if($east == 1 and $west == 1 and $north == 0 and $south == 0){
			$status = 2;
		}elseif($east == 0 and $west == 0 and $north == 1 and $south == 1){
			$status = 1;
		}
		if($status == 3){
			$status = false;
			if($north == 1 and $south == 0){
				$z = 1;
			}elseif($north == 0 and $south == 1){
				$z = 2;
			}
			if($west == 1 and $east == 0){
				$x = 1;
			}elseif($west == 0 and $east == 1){
				$x = 2;
			}
		}
		
		if($status != false){
			$final = ((($status + 1) % 2) & 0x03);
			$this->getLevel()->setBlock($block, Block::get($block->id, $final), true, true);
		}else{
			if(     $z == 1 and ($x == 1 or $this->canConnect($block->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP)) or $this->canConnect($block->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN)))){
				$final = 8;//echo("|-");//8
			}elseif($z == 1 and ($x == 2 or $this->canConnect($block->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP)) or $this->canConnect($block->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN)))){
				$final = 9;//echo("-|");//9
			}elseif(($z == 2 or $this->canConnect($block->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP)) or $this->canConnect($block->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN))) and $x == 1){
				$final = 7;//echo("|_");//7
			}elseif(($z == 2 or $this->canConnect($block->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP)) or $this->canConnect($block->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN))) and $x == 2){
				$final = 6;//echo("_|");//6
			}elseif($x == 0 and ($z == 1 or $z == 2)){
				$final = 0;//return 1;//echo("|");
				if($z == 1){
					if($this->isBlock($block->getSide(Vector3::SIDE_SOUTH))){
						if($this->canConnect($block->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP))){
							$final = 5; // /
						}else{
							$final = 0;//return 1;//echo("|");
						}
					}else{
						if($this->canConnect($block->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN))){
							$final = 0;//return 1;//echo("|");
						}
					}
				}
				if($z == 2){
					if($this->isBlock($block->getSide(Vector3::SIDE_NORTH))){
						if($this->canConnect($block->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP))){
							$final = 4; // /
						}else{
							$final = 0;//return 1;//echo("|");
						}
					}else{
						if($this->canConnect($block->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN))){					
							$final = 0;//return 1;//echo("|");
						}
					}
				}
			}elseif($z == 0 and ($x == 1 or $x == 2)){
				$final = 1;//return 2;//echo("-");
				if($x == 1){	
					if($this->isBlock($block->getSide(Vector3::SIDE_EAST))){
						if($this->canConnect($block->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP))){
							$final = 2; // /
						}else{
							$final = 1;//return 1;//echo("|");
						}
					}else{
						if($this->canConnect($block->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN))){
							$final = 1;//return 1;//echo("|");
						}
					
					}
				}
				if($x == 2){	
					if($this->isBlock($block->getSide(Vector3::SIDE_WEST))){
						if($this->canConnect($block->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP))){
							$final = 3; // /
						}else{
							$final = 1;//return 1;//echo("|");
						}
					}else{
						if($this->canConnect($block->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN))){
							$final = 1;//return 1;//echo("|");
						}
					}
				}
			}elseif($x == 0 and $z == 0){
				if($this->isBlock($this->getSide(Vector3::SIDE_SOUTH))){
					if($this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_UP))){
						$final = 5; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_DOWN))){
						$final = 4; // \
					}
				}
				if($this->isBlock($this->getSide(Vector3::SIDE_NORTH))){
					if($this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_UP))){
						$final = 4; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_NORTH)->getSide(Vector3::SIDE_DOWN))){
						$final = 5; // \
					}
				}
				if($this->isBlock($this->getSide(Vector3::SIDE_EAST))){
					if($this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_UP))){
						$final = 2; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_EAST)->getSide(Vector3::SIDE_DOWN))){
						$final = 3; // \
					}
				}
				if($this->isBlock($this->getSide(Vector3::SIDE_WEST))){
					if($this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_UP))){
						$final = 3; // /
					}
				}else{
					if($this->canConnect($this->getSide(Vector3::SIDE_WEST)->getSide(Vector3::SIDE_DOWN))){
						$final = 2; // \
					}
				}
			}
			if($final !== false and $this->isPRail($block) != false){
				$this->getLevel()->setBlock($block, Block::get($block->id, $final), true, true);
			}else{
				if(($final == 1 or $final == 0) and $this->isRail($block) != false){
					$this->getLevel()->setBlock($block, Block::get($block->id, $final), true, true);
				}
			}
		}
	}
	
	public function canConnect(Block $block){
		if($block instanceof Rail or $block instanceof PoweredRail){
			return 1;
		}
		return 0;
	}
	
	public function isRail(Block $block){
		if($block instanceof Rail or $block instanceof PoweredRail){
			return $block;
		}
		return false;
	}
	
	public function isBlock(Block $block){
		if($block instanceof AIR){	
			return false;
		}
		return $block;
	}
	
	Public function isPRail(Block $block){
		if($block instanceof Rail){
			return $block;
		}
		return false;
	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if(!$this->isRail($this->getSide(Vector3::SIDE_DOWN))){
			$down = $this->getSide(0);
			$d = $player instanceof Player ? $player->getDirection() : 0;
			$meta = (($d + 1) % 2) & 0x03;
			$mmm = $this->update();
		
			if($mmm !== false){
				$this->getLevel()->setBlock($block, Block::get($this->id, $mmm), true, true);
			}else{
				$this->getLevel()->setBlock($block, Block::get($this->id, $meta), true, true);
			}
		
			$this->updateOther();
		
			return true;
		}
	}
	
	public function getHardness(){
		return 0.6;
	}
	
	public function canPassThrough(){
		return true;
	}
	
	public function getDrops(Item $item){
		return [[Item::RAIL,0,1]];
	}
}
