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
 * @link http://forums.imagicalmine.net/
 * 
 *
*/
namespace pocketmine\block;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
class Ice extends Transparent{
	protected $id = self::ICE;
	public function __construct(){
	}
	public function getName(){
		return "Ice";
	}
	public function getHardness(){
		return 0.5;
	}
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}
	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Water(), true);
		return true;
	}
	public function getDrops(Item $item){
		return [];
	}
	
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_RANDOM){
			if($this->getLevel()->getBlockLightAt($this->x, $this->y, $this->z) >= 12){
				$this->getLevel()->setBlock($this, new Water(), true);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}
}