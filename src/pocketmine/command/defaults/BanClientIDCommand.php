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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class BanClientIDCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.banclient.description",
			"%commands.banclient.usage"
		);
		$this->setPermission("pocketmine.command.banclient");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return false;
		}

		//Why @BlackShadow1 doing array_shift here??
		$client = $args[0];
		$reason = $args[1];
		
		//I have no idea what are u doing, @BlackShadow1.
		//Checking numeric and looping online players is actually not needed....
		//P/S: I noticed that too many ... devs is working on ImagicalMine cause ImagicalMine into a whole mess...
		if(!empty($p = $sender->getServer()->getPlayer($client))){
			$sender->getServer()->getClientBans()->addBan($p->getClientId, $reason, null, $sender->getName());
			$p->kick($reason !== "" ? "Banned by admin Reason: " . $reason : "Banned by admin", false);
				
			Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.banclientid.success.players", [$p->getClientId, $p->getName()]));
		}else{
			//Tell them the target is offline, idk if u guys needed to do this, if yes, fill it into this space
			
		}
		//return true;
		
	}
	
}
