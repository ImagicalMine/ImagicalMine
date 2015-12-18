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

class RedstoneWire extends Flowable implements Redstone{
	protected $id = self::REDSTONE_WIRE;
	//protected $power = 0;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}
	
	public function canBeActivated(){
		return true;
	}
	
	public function onActivate(Item $item, Player $player = null){
		echo "Current Power ".$this->getPower()."\n";
	}
	
	public function getPower(){
		return $this->meta;
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

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down instanceof Transparent && $down->getId() !== Block::GLOWSTONE_BLOCK) return false;
		else{
			$this->getLevel()->setBlock($block, $this, true, true);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE,0);
			return true;
		}
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
			if($near instanceof Redstone){
				$power_in = $near->getPower();
				if($power_in >= 15){
					return 15;
				}
				if($power_in > $power_in_max){
					$power_in_max = $power_in;
				}
			}
		}
		for($side = 2;$side<=5;$side++){
			$near = $this->getSide($side);
			$around_down = $near->getSide(0);
			$around_up = $near->getSide(1);
			if($near->id == self::AIR and $around_down->id==self::REDSTONE_WIRE){
				$power_in = $around_down->getPower();
				if($power_in >= 15){
					return 15;
				}
				if($power_in > $power_in_max){
					$power_in_max = $power_in;
				}
			}
			if(!$near instanceof Transparent and $around_up->id==self::REDSTONE_WIRE){
				$power_in = $around_up->getPower();
				if($power_in >= 15){
					return 15;
				}
				if($power_in > $power_in_max)
					$power_in_max = $power_in;
			}
		}
		return $power_in_max;
	}
	
	public function BroadcastRedstoneUpdate($type,$power){
		$this->getSide(0)->onRedstoneUpdate($type,$power);
		$this->getSide(1)->onRedstoneUpdate($type,$power);
		for($side = 2; $side <= 5; $side++){
			$around=$this->getSide($side);
			$around->onRedstoneUpdate($type,$power);
			if(!$around instanceof Transparent){
				$up = $around->getSide(1);
				if($up instanceof Redstone){
					$up -> onRedstoneUpdate($type,$power);
				}
			}else{
				if($around->id==self::AIR){
					$aroundDown = $around->getSide(0);
					if($aroundDown instanceof Redstone)
						$aroundDown -> onRedstoneUpdate($type,$power);
				}
			}
		}
	}
	
	public function onRedstoneUpdate($type,$power){
		if($type == Level::REDSTONE_UPDATE_PLACE){
			if(!($this->getPower() < 2) and $power == 0){
				$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL,$this->getPower());
				return;
			}
			if(!($this->getPower()+1 < $power)){
				return;
			}
			$this->setPower($power - 1);
			$this->getLevel()->setBlock($this, $this, true, true);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL,$this->getPower());
		}
		
		if($type == Level::REDSTONE_UPDATE_NORMAL){
			if($power < $this->getPower()+1){
				return;
			}
			$this->setPower($power - 1);
			$this->getLevel()->setBlock($this, $this, true, true);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL,$this->getPower());
		}
		
		if($type == Level::REDSTONE_UPDATE_LOSTPOWER){
			if(!($power >= $this->getPower() + 1)){
				$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL,$this->getPower());
				return;
			}
			/*$old_power = $this->getPower();
			$fetchedPower = $this->fetchMaxPower();
			if($fetchedPower - 1<= $this->getPower()){
				return;
			}*/
			//$this->setPower($fetchedPower -1);
			$old_power = $this->getPower();
			$this->setPower(0);
			$this->getLevel()->setBlock($this, $this, true, true);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_LOSTPOWER,$old_power);
		}
		
		if($type == Level::REDSTONE_UPDATE_BREAK){
			echo"Receive BREAK\n";
			if(!($power >= $this->getPower() + 1)){
				return;
			}
			$old_power = $this->getPower();
			$fetchedPower = $this->fetchMaxPower();
			$this->setPower(0);
			$this->getLevel()->setBlock($this, $this, true, true);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_LOSTPOWER,$old_power);
		}
	}
	
	public function getName(){
		return "Redstone Wire";
	}

	public function getDrops(Item $item){
		return [[Item::REDSTONE_DUST,0,1]];
	}
	
	public function onBreak(Item $item){
		$oBreturn = $this->getLevel()->setBlock($this, new Air(), true, true);
		$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_BREAK,$this->getPower());
		return $oBreturn;
	}
	
	public function __toString(){
		return $this->getName() . (isPowered()?"":"NOT ") . "POWERED";
	}
}
