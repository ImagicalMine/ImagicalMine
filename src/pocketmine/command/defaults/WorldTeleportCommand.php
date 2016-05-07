<?php
    /**
 * wtp
 * teleport between worlds(levels)
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
*/
namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
    use pocketmine\event\TranslationContainer;
    use pocketmine\Player;
    use pocketmine\utils\TextFormat;

    class WorldTeleportCommand extends VanillaCommand
    {
        public function __construct($name)
        {
            parent::__construct($name,            "%pocketmine.command.wtp.description",            "%commands.wtp.usage");
            $this->setPermission("pocketmine.command.wtp");
        }

        public function execute(CommandSender $sender, $currentAlias, array $args)
        {
            if (!$this->testPermission($sender)) {
                return true;
            }

            $countArgs = count($args);
            
            if ($countArgs == 0) {
                $sender->sendMessage(new TranslationContainer("commands.generic.usage", array($this->usageMessage)));
                return true;
            }

            $target = null;
            
            if ($countArgs === 1) {
                //check subcommands
                switch ($args[0]) {
                    case 'ls':
                        case 'list':
                            $levels = $sender->getServer()->getLevels();
                            
                            if (count($levels) > 0) {
                                $sender->sendMessage(TextFormat::YELLOW . "Worlds: ");
                                $k = 1;
                                foreach ($levels as $level) {
                                    $sender->sendMessage(TextFormat::GREEN . "/wtp " . $k . " -> " . $level->getName());
                                    $k++;
                                }
                            }

                            return true;
                            break;
                        default:
                            
                            if (!($sender instanceof Player)) {
                                $sender->sendMessage(TextFormat::RED . "Please provide a player!");
                                return true;
                            }

                            $levels = $sender->getServer()->getLevels();
                            
                            if (count($levels) > 0) {
                                $k =1;
                                foreach ($levels as $level) {
                                    $levelnames[$k] = $level->getName();
                                    $k++;
                                }
                            }

                            
                            if (!empty($levelnames[$args[0]])) {
                                $target = $sender->getServer()->getLevelByName($levelnames[$args[0]]);
                                
                                if ($sender->getLevel() == $target) {
                                    // @todo add transaltion
                                    $sender->sendMessage(TextFormat::RED . "You are already here!");
                                    return true;
                                }

                                
                                if ($target == null) {
                                    // @todo add translation
                                    $sender->sendMessage(TextFormat::RED . "World not found!");
                                    return true;
                                }

                                // @todo add transaltion
                                $sender->sendMessage(TextFormat::GREEN . "Here we go! Imagical teleport to " . $levelnames[$args[0]]);
                                $sender->teleport($target->getSafeSpawn());
                                return true;
                                break;
                            }

                        $sender->sendMessage(TextFormat::RED . "World not found!");
                        return true;
                    }
            }
        }
    }
