<?php
/**
 * src/pocketmine/item/EnchantedBook.php
 *
 * @package default
 */


namespace pocketmine\item;

/**
 *
 * @todo just added to prevent , needs more improvements
 */
class EnchantedBook extends Item{

	/**
	 *
	 * @param unknown $meta  (optional)
	 * @param unknown $count (optional)
	 */
	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::ENCHANTED_BOOK, $meta, $count, "Enchanted Book");
	}


}
