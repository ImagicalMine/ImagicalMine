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

class ExpCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct($name, "Gives the specified player a certain amount of experience. Specify <amount>L to give levels instead, with a negative amount resulting in taking levels.", "/xp <amount> [player] OR /xp <amount>L [player]", []);
		$this->setPermission("pocketmine.command.xp");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		
		if(count($args) > 0){
			$inputAmount = $args[0];
			$player = null;
			
			$isLevel = $this->endsWith($inputAmount, "l") || $this->endsWith($inputAmount, "L");
			if($isLevel && strlen($inputAmount) > 1){
				$inputAmount = substr($inputAmount, 0, strlen($inputAmount) - 1);
			}
			
			$amount = intval($inputAmount);
			$isTaking = $amount < 0;
			
			if($isTaking){
				$amount *= -1;
			}
			
			if(count($args) > 1){
				$player = $sender->getServer()->getPlayer($args[1]);
			}
			elseif($sender instanceof Player){
				$player = $sender;
			}
			
			if($player != null){
				if($isLevel){
					if($isTaking){
						$player->removeExpLevels($amount);
						$player->getServer()->broadcastMessage("Taken " . $amount + " level(s) from " . $player->getName(), $player);
					}
					else{
						$player->giveExpLevels($amount);
						$player->getServer()->broadcastMessage("Given " . $amount + " level(s) to " . $player->getName(), $sender);
					}
				}
				else{
					if($isTaking){
						$sender->sendMessage(TextFormat::RED . "Taking experience can only be done by levels, cannot give players negative experience points");
						return false;
					}
					else{
						$player->giveExp($amount);
						$player->getServer()->broadcastMessage("Given " . $amount + " experience to " . $player->getName(), $sender);
					}
				}
			}
			else{
				$sender->sendMessage("Can't find player, was one provided?\n" . TextFormat::RED . "Usage: " . $this->usageMessage);
				return false;
			}
			
			return true;
		}
		
		$sender->sendMessage(TextFormat::RED . "Usage: " . $this->usageMessage);
		return false;
	}
	
	// $sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
	// Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.give.success", [$xp->getName() . " (" . $xp->getId() . ":" . $xp->getDamage() . ")",(string) $xp->getCount(),$player->getName()]));
	public function endsWith($haystack, $needle){
		// search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
}
