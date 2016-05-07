<?php
/**
 * src/pocketmine/block/UnlitRedstoneRepeater.php
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
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Player;

class UnlitRedstoneRepeater extends Flowable implements Redstone, RedstoneTransmitter{
    
	protected $id = self::UNLIT_REDSTONE_REPEATER;

    /**
     * UnlitRedstoneRepeater constructor.
     * @param int $meta
     */
    public function __construct($meta = 0) {
        $this->meta = $meta;
    }

    /**
     * @return bool
     */
    public function isRedstone() : bool{
        return true;
    }

    /**
     * @return AxisAlignedBB
     */
    protected function recalculateBoundingBox() : AxisAlignedBB{
        return new AxisAlignedBB(
            $this->x,
            $this->y,
            $this->z,
            $this->x + 1,
            $this->y + 0.125,
            $this->z + 1);
    }

    public function onUpdate($type){
        if($type === Level::BLOCK_UPDATE_NORMAL){
            $down = $this->getSide(0);

            if($down->isTransparent() && !($down instanceof Slab && ($down->meta & 0x08) === 0x08) || ($down instanceof WoodSlab && ($down->meta & 0x08) === 0x08) || ($down instanceof Stair && ($down->meta & 0x04) === 0x04)){
                $this->getLevel()->useBreakOn($this);
 				return Level::BLOCK_UPDATE_NORMAL;
			}

 			if($this->getLevel()->getServer()->getProperty("redstone.calculation", true)){
                if($this->fetchMaxPower() < $this->getPower() + 1){
                    $this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_LOSTPOWER, $this->getPower() + 1);
 				}else{
                    $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE, $this->getPower());
				}
 			}
		}
 		return true;
 	}

    /**
     * @return int
     */
    public function fetchMaxPower() : int{
        $power_in_max = 0;
        for($side = 0; $side <= 5; $side++){
            $near = $this->getSide($side);

            if($near->isActivitedByRedstone()){
                return Block::REDSTONE_BLOCK;
            }

            if($near->isRedstoneSource()){
                $power_in = $near->getPower();
                if($power_in == Block::REDSTONE_BLOCK){
                    return Block::REDSTONE_BLOCK;
                }
                elseif($power_in > $power_in_max){
                    return $power_in;
                }
            }

            if($side >= 2){
                if($near instanceof RedstoneTransmitter){
                    $power_in = $near->getPower();
                    if($power_in > $power_in_max){
                        $power_in_max = $power_in;
                    }
                }
                else{
                    $near = $this->getSide($side);
                    $around_down = $near->getSide(0);
                    $around_up = $near->getSide(1);
                    if($near->getId() == Block::AIR and $around_down instanceof RedstoneTransmitter){
                        $power_in = $around_down->getPower();
                        if($power_in > $power_in_max){
                            $power_in_max = $power_in;
                        }
                    }
                    elseif(!$near instanceof Transparent and $around_up instanceof RedstoneTransmitter and $this->getSide(1) instanceof Transparent){
                        $power_in = $around_up->getPower();
                        if($power_in > $power_in_max){
                            $power_in_max = $power_in;
                        }
                    }
                }
            }
        }
        return $power_in_max;
    }

    /**
     * @param $type
     * @param $power
     */
    public function BroadcastRedstoneUpdate($type, $power){
        for($side = 0; $side <= 5; $side++){
            $around = $this->getSide($side);
            $this->getLevel()->setRedstoneUpdate($around, Block::REDSTONEDELAY, $type, $power);
            if($type === Level::REDSTONE_UPDATE_BREAK){
                $up = $around->getSide(1);
                $down = $around->getSide(0);
                if(!$around instanceof Transparent and $up instanceof RedstoneTransmitter and $this->getSide(1) instanceof Transparent){
                    $this->getLevel()->setRedstoneUpdate($up, Block::REDSTONEDELAY, $type, $power);
                }
                elseif($around->id == self::AIR and $down instanceof Redstone){
                    $this->getLevel()->setRedstoneUpdate($down, Block::REDSTONEDELAY, $type, $power);
                }
            }
        }
    }

    /**
     * @param $type
     * @param $power
     */
    public function onRedstoneUpdate($type, $power){
        if($type == Level::REDSTONE_UPDATE_PLACE){
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL, $this->getPower());
            if($power > $this->getPower() + 1){
                $this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_NORMAL, $power);
            }
            return;
        }

        if($type == Level::REDSTONE_UPDATE_REPOWER){
            foreach($this->getLevel()->RedstoneRepowers as $repower){
                $pos = new Vector3($repower['x'], $repower['y'], $repower['z']);
                $this->getLevel()->getBlock($pos)->setRedstoneUpdateList(Level::REDSTONE_UPDATE_REPOWER, null);
                return;
            }
        }

        if($type == Level::REDSTONE_UPDATE_BLOCK_CHARGE){
            if($this->fetchMaxPower() > $this->getPower() + 1){
                $this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_NORMAL, $power);
                return;
            }
            if($this->fetchMaxPower() == $this->getPower() + 1){
                return;
            }
            if($this->fetchMaxPower() < $this->getPower() + 1){
                $this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_LOSTPOWER, $power);
                return;
            }
        }

        if($type == Level::REDSTONE_UPDATE_BREAK){
            if($power > $this->getPower()){
                $this->setRedstoneUpdateList(Level::REDSTONE_UPDATE_LOSTPOWER, $power);
            }
            $this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_NORMAL, $this->getPower());
            return;
        }
    }


    /**
     * @return bool
     */
    public function isSolid() : bool {
        return true;
    }

    /**
     * @param int $power
     */
    public function setPower($power){
        $this->meta = $power;
        $this->getLevel()->setBlock($this, $this, true, false);
    }

    /**
     * @return int
     */
    public function getPower() : int{
        $hash = $this->getLevel()->blockHash($this->x, $this->y, $this->z);
        if(isset($this->getLevel()->RedstoneUpdateList[$hash])){
            return $this->getLevel()->RedstoneUpdateList[$hash]['power'];
        }else{
            return $this->meta;
        }
    }

    /**
     * @return bool
     */
    public function canBeActivated() : bool {
        return true;
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
        return "Unlit Redstone Repeater";
    }
    
    /**
     * @return float
     */
    public function getHardness() : float{
        return 0.1;
    }
    
    /**
     * @param Item $item
     * @param Block $block
     * @param Block $target
     * @param int $face
     * @param float $fx
     * @param float $fy
     * @param float $fz
     * @param Player|null $player
     * @return bool
     */
    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null) : bool {
        $down = $this->getSide(0);
        if($down->isTransparent() && !($down instanceof Slab && ($down->meta & 0x08) === 0x08) || ($down instanceof WoodSlab && ($down->meta & 0x08) === 0x08) || ($down instanceof Stair && ($down->meta & 0x04) === 0x04)) {
            return false;
        }else {
            $faces = [
                0 => 4,
                1 => 2,
                2 => 5,
                3 => 3,
            ];
            $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
            $this->getLevel()->setBlock($block, $this, true, true);
            return true;
        }
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
            [$this->id, 0, 1]
        ];
    }
}
