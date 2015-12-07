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

namespace pocketmine\level\format;

use pocketmine\Server;
use pocketmine\utils\LevelException;

abstract class LevelProviderManager{
	protected static $providers = [];

	/**
	 * @param Server $server
	 * @param string $class
	 *
	 * @throws LevelException
	 */
	public static function addProvider(Server $server, $class){
		if(!is_subclass_of($class, LevelProvider::class)){
			throw new LevelException("Class is not a subclass of LevelProvider");
		}
		/** @var LevelProvider $class */
		self::$providers[strtolower($class::getProviderName())] = $class;
	}

	/**
	 * Returns a LevelProvider class for this path, or null
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function getProvider($path){
		foreach(self::$providers as $provider){
			/** @var $provider LevelProvider */
			if($provider::isValid($path)){
				return $provider;
			}
		}

		return null;
	}

	public static function getProviderByName($name){
		$name = trim(strtolower($name));

		return isset(self::$providers[$name]) ? self::$providers[$name] : null;
	}
}