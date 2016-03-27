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
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TeleportCommand extends VanillaCommand{

    public function __construct($name){
        parent::__construct(
            $name,
            "%pocketmine.command.tp.description",
            "%commands.tp.usage"
        );
        //todo check/add permissions subcommands
        $this->setPermission("pocketmine.command.teleport");
    }

    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }

        $countArgs = count($args);

        if($countArgs < 1 or $countArgs > 6){
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", array($this->usageMessage)));

            return true;
        }

        $target = null;

        if($countArgs === 1) {
            if(!($sender instanceof Player)){
                $sender->sendMessage(TextFormat::RED . "Please provide a player!");
                return true;
            }
            //check subcommands
            switch($args[0]) {
                case 'off':
                    //player disable teleporting to or from him
                    $sender->setTeleportEnabled(false);
                    $sender->sendMessage("Teleporting off. Other players can not teleport you or to you");
                    return true;
                    break;
                case 'on':
                    //player enable teleporting to or from him
                    $sender->setTeleportEnabled(true);
                    $sender->sendMessage("Teleporting on. Other players can teleport you or to you");
                    return true;
                    break;
            }
        }

        //set origin
        if(in_array($countArgs, array(1,3))) {
            //tp sender to somewhere
            if(!($sender instanceof Player)){
                $sender->sendMessage(TextFormat::RED . "Please provide a player!");
                return true;
            }
            $originName = $sender->getName();
            $origin = $sender;
            $isSender = true;
        }elseif(in_array($countArgs, array(2,4,5,6))) {
            //tp arg[0] to somewhere
            $originName = $args[0];
            $origin = $sender->getServer()->getPlayer($originName);
            $isSender = false;
        }else{
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", array($this->usageMessage)));
            return true;
        }

        if(in_array($countArgs, array(1,2))) {
            //tp to player
            $targetName = $args[$countArgs-1];
            $target = $sender->getServer()->getPlayer($targetName);
            if(!($origin instanceof Player)){
                $sender->sendMessage(TextFormat::RED . "Can't find player " . $originName);
                return true;
            }
            if(!($target instanceof Player)){
                $sender->sendMessage(TextFormat::RED . "Can't find player " . $targetName);
                return true;
            }

            if(($origin->getTeleportEnabled() && $target->getTeleportEnabled()) || $sender->hasPermission('pocketmine.command.teleport.always')) {
                $origin->teleport($target);
                Command::broadcastCommandMessage($origin, new TranslationContainer("commands.tp.success", array($origin->getName(), $target->getName())));
            } else {
                if($isSender && !$target->getTeleportEnabled()) {
                    $sender->sendMessage($targetName . " does not allow to teleport.");
                }
                if(!$isSender && !$origin->getTeleportEnabled()) {
                    $sender->sendMessage($originName . " does not allow to teleport.");
                }
                if(!$isSender && !$target->getTeleportEnabled()) {
                    $sender->sendMessage($targetName . " does not allow to teleport.");
                }
            }
            return true;
        }else{
            //tp to position
            $pos = 0;
            if(in_array($countArgs, array(4,6))){
                $pos = 1;
            }

            $x = $this->getRelativeDouble($origin->x, $origin, $args[$pos++]);
            $y = $this->getRelativeDouble($origin->y, $origin, $args[$pos++], 0, 128);
            $z = $this->getRelativeDouble($origin->z, $origin, $args[$pos++]);
            $yaw = $origin->getYaw();
            $pitch = $origin->getPitch();

            if($countArgs === 6 or ($countArgs === 5 and $pos === 3)){
                $yaw = $args[$pos++];
                $pitch = $args[$pos++];
            }
            if($isSender || $origin->getTeleportEnabled() || $sender->hasPermission('pocketmine.command.teleport.always')) {
                $origin->teleport(new Vector3($x, $y, $z), $yaw, $pitch);
                Command::broadcastCommandMessage($origin, new TranslationContainer("commands.tp.success.coordinates", array($origin->getName(), round($x, 2), round($y, 2), round($z, 2))));
            }else{
                $sender->sendMessage($originName . " does not allow to teleport.");
            }

            return true;
        }
    }
}
