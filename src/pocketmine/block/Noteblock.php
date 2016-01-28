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
/*
 * Author: PeratX
 * Time: 2015/12/25 16:46
 * Copyright(C) 2011-2015 iTX Technologies LLC.
 * All rights reserved.
 *
 * OpenGenisys Project
*/
namespace pocketmine\block;
use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\level\sound\NoteblockSound;
use pocketmine\Player;

class Noteblock extends Solid implements RedstoneConsumer{
	protected $id = self::NOTEBLOCK;
	public function __construct($meta = 0){
		$this->meta = $meta;
	}
	public function getHardness(){
		return 0.8;
	}
	public function getResistance(){
		return 4;
	}
	public function getToolType(){
		return Tool::TYPE_AXE;
	}
	public function canBeActivated(){
		return true;
	}
	public function getStrength(){
		if($this->meta < 24) $this->meta ++;
		else $this->meta = 0;
		$this->getLevel()->setBlock($this, $this);
		return $this->meta * 1;
	}
	public function onActivate(Item $item, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === self::GLASS || $down->getId() === self::GLOWSTONE){
		        $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_CLICK, $this->getStrength()));
		}
		elseif($down->getId() === self::SAND || $down->getId() === self::GRAVEL){
		        $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_TABOUR, $this->getStrength()));
		}
		elseif($down->getId() === self::WOOD){        
			$this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_BASS, $this->getStrength()));
		}
		elseif($down->getId() === self::STONE){
			$this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_BASS_DRUM, $this->getStrength()));
		}else{
		        $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_PIANO, $this->getStrength()));
		}
		return true;
	}
	public function getName(){
		return "Noteblock";
	}
}
