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

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;


class BanListCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.banlist.description",
			"%commands.banlist.usage"
		);
		$this->setPermission("pocketmine.command.ban.list");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		$list = $sender->getServer()->getNameBans();
		if(isset($args[0])){
			$args[0] = strtolower($args[0]);
			if($args[0] === "ips"){
				$list = $sender->getServer()->getIPBans();
			}elseif($args[0] === "players"){
				$list = $sender->getServer()->getNameBans();
			}else{
				$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

				return false;
			}
		}else{
			$list = $sender->getServer()->getNameBans();
			$args[0] = "players";
		}

		$message = "";
		$list = $list->getEntries();
		foreach($list as $entry){
			$message .= $entry->getName() . ", ";
		}

		if($args[0] === "ips"){
			$sender->sendMessage(new TranslationContainer("commands.banlist.ips", [count($list)]));
		}else{
			$sender->sendMessage(new TranslationContainer("commands.banlist.players", [count($list)]));
		}

		$sender->sendMessage(substr($message, 0, -2));

		return true;
	}
}