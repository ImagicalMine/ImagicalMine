<?php
/**
 * src/pocketmine/block/LitRedstoneRepeater.php
 *
 * @package default
 */


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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;

class LitRedstoneRepeater extends UnlitRedstoneRepeater{

	protected $id = self::LIT_REDSTONE_REPEATER;

    /**
     * LitRedstoneRepeater constructor.
     * @param int $meta
     */
	public function __construct($meta = 0) {
		$this->meta = $meta;
	}

    /**
     * @return int
     */
    public function getStrength() : int{
        return 15;
    }

    /**
     * @return string
     */
	public function getName() : string {
		return "Redstone Repeater";
	}

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null){
        $meta = $this->meta + 4;
        
        if($meta > 15) 
            $this->meta = $this->meta % 4;
        else 
            $this->meta = $meta;
        $this->getLevel()->setBlock($this, $this, true, false);
        return true;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function onBreak(Item $item){
        return $this->getLevel()->setBlock($this, new Air(), true, true);
    }


    /**
     * @param Item $item
     * @return array
     */
	public function getDrops(Item $item) : array{
		return [
            [Item::UNLIT_REDSTONE_REPEATER, 0, 1]
        ];
	}


}
