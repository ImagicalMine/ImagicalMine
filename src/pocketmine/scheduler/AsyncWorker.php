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

namespace pocketmine\scheduler;

use pocketmine\Worker;

class AsyncWorker extends Worker{
	
	private $logger;
	private $id;
	
	public function __construct(\ThreadedLogger $logger, $id){
		$this->logger = $logger;
		$this->id = $id;
	}

	public function run(){
		$this->registerClassLoader();
		gc_enable();
		ini_set("memory_limit", -1);

		global $store;
		$store = [];

	}

	public function handleException(\Throwable $e){
 		$this->logger->logException($e);
	}

	public function getThreadName(){
		return "Asynchronous Worker #" . $this->id;
	}
}
