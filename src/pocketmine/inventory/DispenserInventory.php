<?php
/**
 * src/pocketmine/inventory/DispenserInventory.php
 *
 * @package default
 */


namespace pocketmine\inventory;

use pocketmine\level\Level;
use pocketmine\network\protocol\BlockEventPacket;
use pocketmine\Player;
use pocketmine\tile\Dispenser;

class DispenserInventory extends ContainerInventory
{

    /**
     *
     * @param Dispenser $tile
     */
    public function __construct(Dispenser $tile)
    {
        parent::__construct($tile, InventoryType::get(InventoryType::DISPENSER));
    }


    /**
     *
     * @return Dispenser
     */
    public function getHolder()
    {
        return $this->holder;
    }


    /**
     *
     * @param Player  $who
     */
    public function onOpen(Player $who)
    {
        parent::onOpen($who);

        if (count($this->getViewers()) === 1) {
            $pk = new BlockEventPacket();
            $pk->x = $this->getHolder()->getX();
            $pk->y = $this->getHolder()->getY();
            $pk->z = $this->getHolder()->getZ();
            $pk->case1 = 1;
            $pk->case2 = 2;
            if (($level = $this->getHolder()->getLevel()) instanceof Level) {
                $level->addChunkPacket($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4, $pk);
            }
        }
    }


    /**
     *
     * @param Player  $who
     */
    public function onClose(Player $who)
    {
        if (count($this->getViewers()) === 1) {
            $pk = new BlockEventPacket();
            $pk->x = $this->getHolder()->getX();
            $pk->y = $this->getHolder()->getY();
            $pk->z = $this->getHolder()->getZ();
            $pk->case1 = 1;
            $pk->case2 = 0;
            if (($level = $this->getHolder()->getLevel()) instanceof Level) {
                $level->addChunkPacket($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4, $pk);
            }
        }
        parent::onClose($who);
    }
}
