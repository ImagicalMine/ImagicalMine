<?php
/**
 * wpt
 * teleport between worlds(levels)
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
*/

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TeleportCommand extends VanillaCommand{

    public function __construct($name){
        parent::__construct(
            $name,
            "%pocketmine.command.wtp.description",
            "%commands.wtp.usage"
        );
        //todo check/add permissions subcommands
        $this->setPermission("pocketmine.command.wtp");
    }

    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }
        $countArgs = count($args);
        if($countArgs == 0){
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
                case 'ls':
                    // @todo list worlds (levels)
                    $levels = $sender->getServer()->getLevels();
                    return true;
                    break;
                default:
                    // @todo try to tp to world
                    $target = $sender->getServer()->getLevelByName($args[0]);
                    if ($sender->getLevel() == $target) {
                        // @todo add message
                        return true;
                    }
                    if ($target == null) {
                        // @todo add message
                        return true;
                    }
                    $sender->teleport($target->getSafeSpawn());
                    return true;
                    break;
            }
        }
    }
}