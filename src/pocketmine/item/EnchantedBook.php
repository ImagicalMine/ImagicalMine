<?php
namespace pocketmine\item;

/**
 * @todo just added to prevent , needs more improvements
 */
class EnchantedBook extends Item{
    public function __construct($meta = 0, $count = 1){
        parent::__construct(self::ENCHANTED_BOOK, $meta, $count, "Enchanted Book");
    }
}