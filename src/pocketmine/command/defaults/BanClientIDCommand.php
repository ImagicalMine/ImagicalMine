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

		$client = array_shift($args);
		$reason = implode(" ", $args);
		
		if(is_numeric($client)){
			//Still checing here, not sure if loop is needed, but I think it's not needed too
			foreach($sender->getServer()->getOnlinePlayers() as $p){
				$p = $sender->getServer()->getPlayer($client);
			    	if($p->getClientId() === $client){
				    $p->kick($reason !== "" ? "Banned by admin. Reason:" . $reason : "Banned by admin.");
				    break;
				}
				$sender->getServer()->getClientBans()->addBan($client, $reason, null, $sender->getName());
			
			    Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.banclientid.success", [$p !== null ? $client : $client]));
			
			    return true;
			}
		}else{
			//Obviously, the guys coded this part has 0% of PHP knowledge or 0% of Pocketmine APIs
			//foreach($sender->getServer()->getOnlinePlayers() as $p){
				//Im not sure if this is working, but I'm sure it's better than previous foreach loop...
				if(!empty($p = $sender->getServer()->getPlayer($client))){
			    		$sender->getServer()->getClientBans()->addBan($p->getClientId, $reason, null, $sender->getName());
			    		$p->kick($reason !== "" ? "Banned by admin Reason: " . $reason : "Banned by admin", false);
				
					Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.banclientid.success.players", [$p->getClientId, $p->getName()]));
				}
			//}
			
			return true;
		}
		
		return true;
		
	}
	
}
