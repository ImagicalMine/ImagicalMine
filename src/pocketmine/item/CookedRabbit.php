<?php
namespace pocketmine\item;

class CookedRabbit extends Food{
	public $saturation = 5;

	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_RABBIT, $meta, $count, "Cooked Rabbit");
	}

        public function getSmeltingExp(){
            return 0.35;
        }
}
