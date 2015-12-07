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

namespace pocketmine\plugin;

/**
 * Handles different types of plugins
 */
interface PluginLoader{

	/**
	 * Loads the plugin contained in $file
	 *
	 * @param string $file
	 *
	 * @return Plugin
	 */
	public function loadPlugin($file);

	/**
	 * Gets the PluginDescription from the file
	 *
	 * @param string $file
	 *
	 * @return PluginDescription
	 */
	public function getPluginDescription($file);

	/**
	 * Returns the filename patterns that this loader accepts
	 *
	 * @return string[]
	 */
	public function getPluginFilters();

	/**
	 * @param Plugin $plugin
	 *
	 * @return void
	 */
	public function enablePlugin(Plugin $plugin);

	/**
	 * @param Plugin $plugin
	 *
	 * @return void
	 */
	public function disablePlugin(Plugin $plugin);


}