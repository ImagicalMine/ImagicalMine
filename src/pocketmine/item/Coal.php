<?php
namespace pocketmine\item;


class Coal extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COAL, $meta, $count, "Coal");
		if($this->meta === 1){
			$this->name = "Charcoal";
		}
	}

        public function getSmeltingExp(){
            //for Charcoal more exp
            if($this->meta===1)
                return 0.15;
            return 0.1;
        }
}
