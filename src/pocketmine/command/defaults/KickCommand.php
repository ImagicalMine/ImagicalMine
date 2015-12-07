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
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class KickCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.kick.description",
			"%commands.kick.usage"
		);
		$this->setPermission("pocketmine.command.kick");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$name = array_shift($args);
		$reason = trim(implode(" ", $args));

		if(($player = $sender->getServer()->getPlayer($name)) instanceof Player){
			$player->kick($reason);
			if(strlen($reason) >= 1){
				Command::broadcastCommandMessage($sender, new TranslationContainer("commands.kick.success.reason", [$player->getName(), $reason]));
			}else{
				Command::broadcastCommandMessage($sender, new TranslationContainer("commands.kick.success", [$player->getName()]));
			}
		}else{
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
		}


		return true;
	}
}
