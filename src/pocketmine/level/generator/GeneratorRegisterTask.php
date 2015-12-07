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

namespace pocketmine\level\generator;

use pocketmine\block\Block;

use pocketmine\level\generator\biome\Biome;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;

use pocketmine\utils\Random;

class GeneratorRegisterTask extends AsyncTask{

	public $generator;
	public $settings;
	public $seed;
	public $levelId;

	public function __construct(Level $level, Generator $generator){
		$this->generator = get_class($generator);
		$this->settings = $generator->getSettings();
		$this->seed = $level->getSeed();
		$this->levelId = $level->getId();
	}

	public function onRun(){
		Block::init();
		Biome::init();
		$manager = new SimpleChunkManager($this->seed);
		$this->saveToThreadStore("generation.level{$this->levelId}.manager", $manager);
		/** @var Generator $generator */
		$generator = $this->generator;
		$generator = new $generator($this->settings);
		$generator->init($manager, new Random($manager->getSeed()));
		$this->saveToThreadStore("generation.level{$this->levelId}.generator", $generator);
	}
}
