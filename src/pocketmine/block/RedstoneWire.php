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
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\math\Vector3;

class RedstoneWire extends Flowable implements Redstone,RedstoneTransmitter{
	protected $id = self::REDSTONE_WIRE;

	public function isRedstone(){
		return true;
	}
	
	public function __construct($meta = 0){
		$this->meta = $meta;
	}
	
	public function getPower(){
		$this_hash = Level::blockHash($this->x,$this->y,$this->z);
		if(isset($this->getLevel()->RedstoneUpdateList[$this_hash])){
			return $this->getLevel()->RedstoneUpdateList[$this_hash]['power'];
		}else{
			return $this->meta;
		}
	}
	
	public function setPower($power){
		$this->meta = $power;
	}
	
	public function getHardness(){
		return 0;
	}

	public function isSolid(){
		return true;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0);
			if($down instanceof Transparent){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return true;
	}
	
	public function fetchMaxPower(){
		$power_in_max = 0;
		for($side = 0; $side <= 5; $side++){
			$near = $this->getSide($side);
			
			if($near instanceof RedstoneSource or $near instanceof RedstoneSwitch){
				$power_in = $near->getPower();
				if($power_in > $power_in_max){
					return $power_in;
				}
			}
			
			if(!$near instanceof Transparent){
				for ($side1 =0;$side1<=5;$side1++){
					$around = $near->getSide($side);
					if($around instanceof RedstoneSwitch and $around->getPower() > 0){
						return 16;
					}
				}
			}
			
			if($side >=2){
				if($near instanceof RedstoneTransmitter){
					$power_in = $near->getPower();
					if($power_in > $power_in_max){
						$power_in_max = $power_in;
					}
				}else{
					$near = $this->getSide($side);
					$around_down = $near->getSide(0);
					$around_up = $near->getSide(1);
					$around_next = $near->getSide($side);
					if($near->id == self::AIR and $around_down instanceof RedstoneTransmitter){
						$power_in = $around_down->getPower();
						if($power_in > $power_in_max){
							$power_in_max = $power_in;
						}
					}
					if(!$near instanceof Transparent and $around_up instanceof RedstoneTransmitter){
						$power_in = $around_up->getPower();
						if($power_in > $power_in_max){
							$power_in_max = $power_in;
						}
					}
				}
			}
		}
		return $power_in_max;
	}
	
	public function getName(){
		return "Redstone Wire";
	}

	public function getDrops(Item $item){
		return [[Item::REDSTONE_DUST,0,1]];
	}
	
	public function __toString(){
		return $this->getName() . ($this->getPower() > 0?"":"NOT ") . "POWERED";
	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down instanceof Transparent && $down->getId() !== Block::GLOWSTONE_BLOCK) return false;
		else{
			$this->getLevel()->setBlock($block, $this, true, true);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE,0,$this);
			return true;
		}
	}
	
	public function onBreak(Item $item){
		$oBreturn = $this->getLevel()->setBlock($this, new Air(), true, true);
		$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_BREAK,$this->getPower());
		return $oBreturn;
	}
	
	public function doRedstoneListUpdate(){
		if($this->chkRedstoneRepowers()){
			$this->getLevel()->setRedstoneUpdate($this,Block::REDSTONEDELAY,Level::REDSTONE_UPDATE_REPOWER,16);
			return;
		}
		$this->getLevel()->RedstoneUpdaters = [];
		foreach($this->getLevel()->RedstoneUpdateList as $ublock){
			$hash = Level::blockHash($ublock['x'], $ublock['y'], $ublock['z']);
			$pos = new Vector3($ublock['x'], $ublock['y'], $ublock['z']);
			$sblock = new RedstoneWire();
			$sblock->setPower($ublock['power']);
			$this->getLevel()->setBlock($pos,$sblock,true,false);
			$sblock = $this->getLevel()->getBlock($pos);
			if($sblock->getPower() == 0 ){
				$sblock->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_LOSTPOWER,$sblock->getPower());
			}else{
				$sblock->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL,$sblock->getPower());
			}
			unset($this->getLevel()->RedstoneUpdateList[$hash]);
		}
	}
	
	public function chkRedstoneUpdateStat(){
		$chk = true;
		foreach($this->getLevel()->RedstoneUpdaters as $Updaters){
			if(!$Updaters){
				$chk = false;
			}
		}
		if($chk){
			$this->doRedstoneListUpdate();
		}
	}
	
	public function setRedstoneUpdateList($type,$power){
		if($type === Level::REDSTONE_UPDATE_NORMAL){
			if($power > $this->getPower() + 1){
				$this_hash = Level::blockHash($this->x,$this->y,$this->z);
				$this->getLevel()->RedstoneUpdaters[$this_hash] = false;
				$thispower = $power - 1;
				$this->getLevel()->RedstoneUpdateList[$this_hash] = ['x'=>$this->x,'y'=>$this->y,'z'=>$this->z,'power'=>$thispower];
				
				for($side = 2; $side <= 5; $side++){
					$near = $this->getSide($side);
					if($near instanceof RedstoneTransmitter){
						$near_hash = Level::blockHash($near->x,$near->y,$near->z);
						if(isset($this->getLevel()->RedstoneUpdateList[$near_hash])){
							if($this->getLevel()->RedstoneUpdateList[$near_hash]['power'] >= $thispower - 1){
								continue;
							}
						}
						$near->setRedstoneUpdateList($type,$thispower);
					}else{
						$around_down = $near->getSide(0);
						$around_up = $near->getSide(1);
						if($near->getId() == Block::AIR and $around_down instanceof RedstoneTransmitter){
							$around_down_hash = Level::blockHash($around_down->x,$around_down->y,$around_down->z);
							if(isset($this->getLevel()->RedstoneUpdateList[$around_down_hash])){
								if($this->getLevel()->RedstoneUpdateList[$around_down_hash]['power'] >= $thispower - 1){
									continue;
								}
							}
							$around_down->setRedstoneUpdateList($type,$thispower);
						}elseif(!$near instanceof Transparent and $around_up instanceof RedstoneTransmitter){
							$around_up_hash = Level::blockHash($around_up->x,$around_up->y,$around_up->z);
							if(isset($this->getLevel()->RedstoneUpdateList[$around_up_hash])){
								if($this->getLevel()->RedstoneUpdateList[$around_up_hash]['power'] >= $thispower - 1){
									continue;
								}
							}
							$around_up->setRedstoneUpdateList($type,$thispower);
						}
					}
				}
				$this->getLevel()->RedstoneUpdaters[$this_hash] = true;
				$this->chkRedstoneUpdateStat();
			}
		}
		
		if($type === Level::REDSTONE_UPDATE_REPOWER){
			$type = Level::REDSTONE_UPDATE_NORMAL;
			$this_hash = Level::blockHash($this->x,$this->y,$this->z);
			$this->getLevel()->RedstoneUpdaters[$this_hash] = false;
			$thispower = $this->getPower();
			$this->getLevel()->RedstoneUpdateList[$this_hash] = ['x'=>$this->x,'y'=>$this->y,'z'=>$this->z,'power'=>$thispower];
			
			for($side = 2; $side <= 5; $side++){
				$near = $this->getSide($side);
				if($near instanceof RedstoneTransmitter){
					$near_hash = Level::blockHash($near->x,$near->y,$near->z);
					if(isset($this->getLevel()->RedstoneUpdateList[$near_hash])){
						if($this->getLevel()->RedstoneUpdateList[$near_hash]['power'] >= $thispower - 1){
							continue;
						}
					}
					$near->setRedstoneUpdateList($type,$thispower);
				}else{
					$around_down = $near->getSide(0);
					$around_up = $near->getSide(1);
					if($near->getId() == Block::AIR and $around_down instanceof RedstoneTransmitter){
						$around_down_hash = Level::blockHash($around_down->x,$around_down->y,$around_down->z);
						if(isset($this->getLevel()->RedstoneUpdateList[$around_down_hash])){
							if($this->getLevel()->RedstoneUpdateList[$around_down_hash]['power'] >= $thispower - 1){
								continue;
							}
						}
						$around_down->setRedstoneUpdateList($type,$thispower);
					}elseif(!$near instanceof Transparent and $around_up instanceof RedstoneTransmitter){
						$around_up_hash = Level::blockHash($around_up->x,$around_up->y,$around_up->z);
						if(isset($this->getLevel()->RedstoneUpdateList[$around_up_hash])){
							if($this->getLevel()->RedstoneUpdateList[$around_up_hash]['power'] >= $thispower - 1){
								continue;
							}
						}
						$around_up->setRedstoneUpdateList($type,$thispower);
					}
				}
			}
			$this->getLevel()->RedstoneUpdaters[$this_hash] = true;
		}
		
		if($type === Level::REDSTONE_UPDATE_LOSTPOWER){
			if($this->getPower() !== 0 and $power >= $this->getPower + 1){
				$thispower = $this->getPower();
				$this_hash = Level::blockHash($this->x,$this->y,$this->z);
				$this->getLevel()->RedstoneUpdateList[$this_hash] = ['x'=>$this->x,'y'=>$this->y,'z'=>$this->z,'power'=>0];
				$FetchedMaxPower = $this->fetchMaxPower();
				if($FetchedMaxPower == 16){
					unset($this->getLevel()->RedstoneUpdateList[$this_hash]);
					$this->getLevel()->RedstoneRepowers[$this_hash] = ['x'=>$this->x,'y'=>$this->y,'z'=>$this->z];
					return;
				}
				
				$this->getLevel()->RedstoneUpdaters[$this_hash] = false;
				for($side = 2; $side <= 5; $side++){
					$near = $this->getSide($side);
					if($near instanceof RedstoneTransmitter){
						$near_hash = Level::blockHash($near->x,$near->y,$near->z);
						if(isset($this->getLevel()->RedstoneUpdateList[$near_hash])){
							continue;
						}
						$near->setRedstoneUpdateList($type,$thispower);
					}else{
						$around_down = $near->getSide(0);
						$around_up = $near->getSide(1);
						if($near->getId() == Block::AIR and $around_down instanceof RedstoneTransmitter){
							$around_down_hash = Level::blockHash($around_down->x,$around_down->y,$around_down->z);
							if(isset($this->getLevel()->RedstoneUpdateList[$around_down_hash])){
								continue;
							}
						$around_down->setRedstoneUpdateList($type,$thispower);
						}elseif(!$near instanceof Transparent and $around_up instanceof RedstoneTransmitter){
							$around_up_hash = Level::blockHash($around_up->x,$around_up->y,$around_up->z);
							if(isset($this->getLevel()->RedstoneUpdateList[$around_up_hash])){
								continue;
							}
						$around_up->setRedstoneUpdateList($type,$thispower);
						}
					}
				}
				$this->getLevel()->RedstoneUpdaters[$this_hash] = true;
				$this->chkRedstoneUpdateStat();
			}
			
		}
	}
	
	public function chkRedstoneRepowers(){
		if(count($this->getLevel()->RedstoneRepowers) == 0){
			return false;
		}
		return true;
	}
	
	public function BroadcastRedstoneUpdate($type,$power){
		$down = $this->getSide(0);
		$up = $this->getSide(1);
		if($down instanceof Redstone){
			$this->getLevel()->setRedstoneUpdate($down,Block::REDSTONEDELAY,$type,$power);
		}
		if($up instanceof Redstone){
			$this->getLevel()->setRedstoneUpdate($up,Block::REDSTONEDELAY,$type,$power);
		}
		for($side = 2; $side <= 5; $side++){
			$around=$this->getSide($side);
			$this->getLevel()->setRedstoneUpdate($around,Block::REDSTONEDELAY,$type,$power);
			if(!$around instanceof Transparent){
				$up = $around->getSide(1);
				if($up instanceof RedstoneTransmitter){
					$this->getLevel()->setRedstoneUpdate($up,Block::REDSTONEDELAY,$type,$power);
				}
			}else{
				if($around->id==self::AIR){
					$down = $around->getSide(0);
					if($down instanceof Redstone)
						$this->getLevel()->setRedstoneUpdate($down,Block::REDSTONEDELAY,$type,$power);
				}
			}
		}
	}
	
	public function onRedstoneUpdate($type,$power){
		if($type == Level::REDSTONE_UPDATE_REPOWER){
			foreach($this->getLevel()->RedstoneRepowers as $ublock){
				$hash = Level::blockHash($ublock['x'], $ublock['y'], $ublock['z']);
				$pos = new Vector3($ublock['x'], $ublock['y'], $ublock['z']);
				unset($this->getLevel()->RedstoneRepowers[$hash]);
				$this->getLevel()->getBlock($pos)->setRedstoneUpdateList(Level::REDSTONE_UPDATE_REPOWER,null);
				$this->doRedstoneListUpdate();
				return;
			}
		}
		
		if($type == Level::REDSTONE_UPDATE_PLACE){
			if($this->getPower() > 1 and $power == 0){
				$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE,$this->getPower());
				return;
			}
			if($this->getPower()+1 >= $power){
				return;
			}
			$this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_NORMAL,$power);
			return;
		}
		
		if($type == Level::REDSTONE_UPDATE_BREAK){
			if($power <= $this->getPower()){
				$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE,$this->getPower());
				return;
			}
			$this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_LOSTPOWER,$power);
			return;
		}
	}
	
}