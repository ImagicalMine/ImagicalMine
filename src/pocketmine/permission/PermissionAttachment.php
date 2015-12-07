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

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;
use pocketmine\utils\PluginException;

class PermissionAttachment{
	/** @var PermissionRemovedExecutor */
	private $removed = null;

	/**
	 * @var bool[]
	 */
	private $permissions = [];

	/** @var Permissible */
	private $permissible;

	/** @var Plugin */
	private $plugin;

	/**
	 * @param Plugin      $plugin
	 * @param Permissible $permissible
	 *
	 * @throws PluginException
	 */
	public function __construct(Plugin $plugin, Permissible $permissible){
		if(!$plugin->isEnabled()){
			throw new PluginException("Plugin " . $plugin->getDescription()->getName() . " is disabled");
		}

		$this->permissible = $permissible;
		$this->plugin = $plugin;
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(){
		return $this->plugin;
	}

	/**
	 * @param PermissionRemovedExecutor $ex
	 */
	public function setRemovalCallback(PermissionRemovedExecutor $ex){
		$this->removed = $ex;
	}

	/**
	 * @return PermissionRemovedExecutor
	 */
	public function getRemovalCallback(){
		return $this->removed;
	}

	/**
	 * @return Permissible
	 */
	public function getPermissible(){
		return $this->permissible;
	}

	/**
	 * @return bool[]
	 */
	public function getPermissions(){
		return $this->permissions;
	}

	/**
	 * @return bool[]
	 */
	public function clearPermissions(){
		$this->permissions = [];
		$this->permissible->recalculatePermissions();
	}

	/**
	 * @param bool[] $permissions
	 */
	public function setPermissions(array $permissions){
		foreach($permissions as $key => $value){
			$this->permissions[$key] = (bool) $value;
		}
		$this->permissible->recalculatePermissions();
	}

	/**
	 * @param string[] $permissions
	 */
	public function unsetPermissions(array $permissions){
		foreach($permissions as $node){
			unset($this->permissions[$node]);
		}
		$this->permissible->recalculatePermissions();
	}

	/**
	 * @param string|Permission $name
	 * @param bool              $value
	 */
	public function setPermission($name, $value){
		$name = $name instanceof Permission ? $name->getName() : $name;
		if(isset($this->permissions[$name])){
			if($this->permissions[$name] === $value){
				return;
			}
			unset($this->permissions[$name]); //Fixes children getting overwritten
		}
		$this->permissions[$name] = $value;
		$this->permissible->recalculatePermissions();
	}

	/**
	 * @param string|Permission $name
	 */
	public function unsetPermission($name){
		$name = $name instanceof Permission ? $name->getName() : $name;
		if(isset($this->permissions[$name])){
			unset($this->permissions[$name]);
			$this->permissible->recalculatePermissions();
		}
	}

	/**
	 * @return void
	 */
	public function remove(){
		$this->permissible->removeAttachment($this);
	}
}