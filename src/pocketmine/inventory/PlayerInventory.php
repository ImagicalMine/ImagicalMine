<?php
/**
 * src/pocketmine/inventory/PlayerInventory.php
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

namespace pocketmine\inventory;

use pocketmine\entity\FishingHook;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\network\protocol\ContainerSetContentPacket;
use pocketmine\network\protocol\ContainerSetSlotPacket;
use pocketmine\network\protocol\MobArmorEquipmentPacket;
use pocketmine\network\protocol\MobEquipmentPacket;
use pocketmine\Player;
use pocketmine\Server;

class PlayerInventory extends BaseInventory
{

    protected $itemInHandIndex = 0;
    /** @var int[] */
    protected $hotbar;

    /**
     *
     * @param Human   $player
     */
    public function __construct(Human $player)
    {
        $this->hotbar = array_fill(0, $this->getHotbarSize(), -1);
        parent::__construct($player, InventoryType::get(InventoryType::PLAYER));
    }


    /**
     *
     * @return unknown
     */
    public function getSize()
    {
        return parent::getSize() - 13; //Remove armor slots + 9 hotbar slots
    }


    /**
     *
     * @param int     $size
     */
    public function setSize(int $size)
    {
        parent::setSize($size + 13);
        $this->sendContents($this->getViewers());
    }


    /**
     *
     * @param unknown $index
     * @return unknown
     */
    public function getHotbarSlotIndex($index)
    {
        return ($index >= 0 and $index < $this->getHotbarSize()) ? $this->hotbar[$index] : -1;
    }


    /**
     *
     * @param unknown $index
     * @param unknown $slot
     */
    public function setHotbarSlotIndex($index, $slot)
    {
        if ($index >= 0 and $index < $this->getHotbarSize() and $slot >= -1 and $slot < $this->getSize()) {
            $this->hotbar[$index] = $slot;
        }
    }


    /**
     *
     * @return unknown
     */
    public function getHeldItemIndex()
    {
        return $this->itemInHandIndex;
    }


    /**
     *
     * @param unknown $index
     */
    public function setHeldItemIndex($index)
    {
        if ($index >= 0 and $index < $this->getHotbarSize()) {
            $this->itemInHandIndex = $index;

            if ($this->getHolder() instanceof Player) {
                $this->sendHeldItem($this->getHolder());
            }

            $this->sendHeldItem($this->getHolder()->getViewers());
        }
    }


    /**
     *
     * @return unknown
     */
    public function getItemInHand()
    {
        $item = $this->getItem($this->getHeldItemSlot());
        if ($item instanceof Item) {
            return $item;
        } else {
            return Item::get(Item::AIR, 0, 0);
        }
    }


    /**
     *
     * @param Item    $item
     * @return bool
     */
    public function setItemInHand(Item $item)
    {
        return $this->setItem($this->getHeldItemSlot(), $item);
    }


    /**
     *
     * @return unknown
     */
    public function getHeldItemSlot()
    {
        return $this->getHotbarSlotIndex($this->itemInHandIndex);
    }


    /**
     *
     * @param unknown $slot
     */
    public function setHeldItemSlot($slot)
    {
        if ($slot >= -1 and $slot < $this->getSize()) {
            $item = $this->getItem($slot);

            $itemIndex = $this->getHeldItemIndex();

            if ($this->getHolder() instanceof Player) {
                Server::getInstance()->getPluginManager()->callEvent($ev = new PlayerItemHeldEvent($this->getHolder(), $item, $slot, $itemIndex));
                if ($ev->isCancelled()) {
                    $this->sendContents($this->getHolder());
                    return;
                }

                if ($this->getHolder()->fishingHook instanceof FishingHook) {
                    $this->getHolder()->fishingHook->close();
                }
            }

            $this->setHotbarSlotIndex($itemIndex, $slot);
        }
    }


    /**
     *
     * @param Player|Player[] $target
     */
    public function sendHeldItem($target)
    {
        $item = $this->getItemInHand();

        $pk = new MobEquipmentPacket();
        $pk->eid = ($target === $this->getHolder() ? 0 : $this->getHolder()->getId());
        $pk->item = $item;
        $pk->slot = $this->getHeldItemSlot();
        $pk->selectedSlot = $this->getHeldItemIndex();

        if (!is_array($target)) {
            $target->dataPacket($pk);
            if ($target === $this->getHolder()) {
                $this->sendSlot($this->getHeldItemSlot(), $target);
            }
        } else {
            Server::broadcastPacket($target, $pk);
            foreach ($target as $player) {
                if ($player === $this->getHolder()) {
                    $this->sendSlot($this->getHeldItemSlot(), $player);
                    break;
                }
            }
        }
    }


    /**
     *
     * @param unknown $index
     * @param unknown $before
     */
    public function onSlotChange($index, $before)
    {
        $holder = $this->getHolder();
        if ($holder instanceof Player and !$holder->spawned) {
            return;
        }

        parent::onSlotChange($index, $before);

        if ($index >= $this->getSize()) {
            $this->sendArmorSlot($index, $this->getViewers());
            $this->sendArmorSlot($index, $this->getHolder()->getViewers());
        }
    }


    /**
     *
     * @return unknown
     */
    public function getHotbarSize()
    {
        return 9;
    }


    /**
     *
     * @param unknown $index
     * @return unknown
     */
    public function getArmorItem($index)
    {
        return $this->getItem($this->getSize() + $index);
    }


    /**
     *
     * @param unknown $index
     * @param Item    $item
     * @return unknown
     */
    public function setArmorItem($index, Item $item)
    {
        return $this->setItem($this->getSize() + $index, $item);
    }


    /**
     *
     * @return unknown
     */
    public function getHelmet()
    {
        return $this->getItem($this->getSize());
    }


    /**
     *
     * @return unknown
     */
    public function getChestplate()
    {
        return $this->getItem($this->getSize() + 1);
    }


    /**
     *
     * @return unknown
     */
    public function getLeggings()
    {
        return $this->getItem($this->getSize() + 2);
    }


    /**
     *
     * @return unknown
     */
    public function getBoots()
    {
        return $this->getItem($this->getSize() + 3);
    }


    /**
     *
     * @param Item    $helmet
     * @return unknown
     */
    public function setHelmet(Item $helmet)
    {
        return $this->setItem($this->getSize(), $helmet);
    }


    /**
     *
     * @param Item    $chestplate
     * @return unknown
     */
    public function setChestplate(Item $chestplate)
    {
        return $this->setItem($this->getSize() + 1, $chestplate);
    }


    /**
     *
     * @param Item    $leggings
     * @return unknown
     */
    public function setLeggings(Item $leggings)
    {
        return $this->setItem($this->getSize() + 2, $leggings);
    }


    /**
     *
     * @param Item    $boots
     * @return unknown
     */
    public function setBoots(Item $boots)
    {
        return $this->setItem($this->getSize() + 3, $boots);
    }


    /**
     *
     * @param unknown $index
     * @param Item    $item
     * @return unknown
     */
    public function setItem($index, Item $item)
    {
        if ($index < 0 or $index >= $this->size) {
            return false;
        } elseif ($item->getId() === 0 or $item->getCount() <= 0) {
            return $this->clear($index);
        }

        if ($index >= $this->getSize()) { //Armor change
            Server::getInstance()->getPluginManager()->callEvent($ev = new EntityArmorChangeEvent($this->getHolder(), $this->getItem($index), $item, $index));
            if ($ev->isCancelled() and $this->getHolder() instanceof Human) {
                $this->sendArmorSlot($index, $this->getViewers());
                return false;
            }
            $item = $ev->getNewItem();
        } else {
            Server::getInstance()->getPluginManager()->callEvent($ev = new EntityInventoryChangeEvent($this->getHolder(), $this->getItem($index), $item, $index));
            if ($ev->isCancelled()) {
                $this->sendSlot($index, $this->getViewers());
                return false;
            }
            $item = $ev->getNewItem();
        }


        $old = $this->getItem($index);
        $this->slots[$index] = clone $item;
        $this->onSlotChange($index, $old);
        if ($this->getHolder() instanceof Player) {
            if ($this->getHolder()->isSurvival()) {
                $this->sendContents($this->getHolder());
            }
        }
        return true;
    }


    /**
     *
     * @param unknown $index
     * @return unknown
     */
    public function clear($index)
    {
        if (isset($this->slots[$index])) {
            $item = Item::get(Item::AIR, null, 0);
            $old = $this->slots[$index];
            if ($index >= $this->getSize() and $index < $this->size) { //Armor change
                Server::getInstance()->getPluginManager()->callEvent($ev = new EntityArmorChangeEvent($this->getHolder(), $old, $item, $index));
                if ($ev->isCancelled()) {
                    if ($index >= $this->size) {
                        $this->sendArmorSlot($index, $this->getViewers());
                    } else {
                        $this->sendSlot($index, $this->getViewers());
                    }
                    return false;
                }
                $item = $ev->getNewItem();
            } else {
                Server::getInstance()->getPluginManager()->callEvent($ev = new EntityInventoryChangeEvent($this->getHolder(), $old, $item, $index));
                if ($ev->isCancelled()) {
                    if ($index >= $this->size) {
                        $this->sendArmorSlot($index, $this->getViewers());
                    } else {
                        $this->sendSlot($index, $this->getViewers());
                    }
                    return false;
                }
                $item = $ev->getNewItem();
            }
            if ($item->getId() !== Item::AIR) {
                $this->slots[$index] = clone $item;
            } else {
                unset($this->slots[$index]);
            }

            $this->onSlotChange($index, $old);
        }

        return true;
    }


    /**
     *
     * @return Item[]
     */
    public function getArmorContents()
    {
        $armor = [];

        for ($i = 0; $i < 4; ++$i) {
            $armor[$i] = $this->getItem($this->getSize() + $i);
        }

        return $armor;
    }


    /**
     *
     */
    public function clearAll()
    {
        $limit = $this->getSize() + 4;
        for ($index = 0; $index < $limit; ++$index) {
            $this->clear($index);
        }
    }


    /**
     *
     * @param Player|Player[] $target
     */
    public function sendArmorContents($target)
    {
        if ($target instanceof Player) {
            $target = [$target];
        }

        $armor = $this->getArmorContents();

        $pk = new MobArmorEquipmentPacket();
        $pk->eid = $this->getHolder()->getId();
        $pk->slots = $armor;
        $pk->encode();
        $pk;
        $pk->isEncoded = true;

        foreach ($target as $player) {
            if ($player === $this->getHolder()) {
                $pk2 = new ContainerSetContentPacket();
                $pk2->windowid = ContainerSetContentPacket::SPECIAL_ARMOR;
                $pk2->slots = $armor;
                $player->dataPacket($pk2);
            } else {
                $player->dataPacket($pk);
            }
        }
    }


    /**
     *
     * @param Item[]  $items
     */
    public function setArmorContents(array $items)
    {
        for ($i = 0; $i < 4; ++$i) {
            if (!isset($items[$i]) or !($items[$i] instanceof Item)) {
                $items[$i] = Item::get(Item::AIR, null, 0);
            }

            if ($items[$i]->getId() === Item::AIR) {
                $this->clear($this->getSize() + $i);
            } else {
                $this->setItem($this->getSize() + $i, $items[$i]);
            }
        }
    }


    /**
     *
     * @param int             $index
     * @param Player|Player[] $target
     */
    public function sendArmorSlot($index, $target)
    {
        if ($target instanceof Player) {
            $target = [$target];
        }

        $armor = $this->getArmorContents();

        $pk = new MobArmorEquipmentPacket();
        $pk->eid = $this->getHolder()->getId();
        $pk->slots = $armor;
        $pk->encode();
        $pk->isEncoded = true;

        foreach ($target as $player) {
            if ($player === $this->getHolder()) {
                /** @var Player $player */
                $pk2 = new ContainerSetSlotPacket();
                $pk2->windowid = ContainerSetContentPacket::SPECIAL_ARMOR;
                $pk2->slot = $index - $this->getSize();
                $pk2->item = $this->getItem($index);
                $player->dataPacket($pk2);
            } else {
                $player->dataPacket($pk);
            }
        }
    }


    /**
     *
     * @param Player|Player[] $target
     */
    public function sendContents($target)
    {
        if ($target instanceof Player) {
            $target = [$target];
        }
        $pk = new ContainerSetContentPacket();
        $pk->slots = [];
        $holder = $this->getHolder();
        for ($i = 0; $i < $this->getSize(); ++$i) { //Do not send armor by error here
            $pk->slots[$i] = $this->getItem($i);
        }

        foreach ($target as $player) {
            $pk->hotbar = [];
            if ($player === $this->getHolder()) {
                for ($i = 0; $i < $this->getHotbarSize(); ++$i) {
                    $index = $this->getHotbarSlotIndex($i);
                    $pk->hotbar[] = $index <= -1 ? -1 : $index + 9;
                }
            }
            if (($id = $player->getWindowId($this)) === -1 or $player->spawned !== true) {
                $this->close($player);
                continue;
            }
            $pk->windowid = $id;
            $player->dataPacket(clone $pk);
        }
    }


    /**
     *
     * @param unknown $slots
     * @return unknown
     */
    public function addItem(...$slots)
    {
        $result = parent::addItem(...$slots);
        if ($this->getHolder() instanceof Player) {
            if ($this->getHolder()->isSurvival()) {
                $this->sendContents($this->getHolder());
            }
        }
        return $result;
    }


    /**
     *
     * @param unknown $slots
     * @return unknown
     */
    public function removeItem(...$slots)
    {
        $result = parent::removeItem(...$slots);
        if ($this->getHolder() instanceof Player) {
            if ($this->getHolder()->isSurvival()) {
                $this->sendContents($this->getHolder());
            }
        }
        return $result;
    }


    /**
     *
     * @param int             $index
     * @param Player|Player[] $target
     */
    public function sendSlot($index, $target)
    {
        if ($target instanceof Player) {
            $target = [$target];
        }

        $pk = new ContainerSetSlotPacket();
        $pk->hotbar = [];
        for ($i = 0; $i < $this->getHotbarSize(); ++$i) {
            $index = $this->getHotbarSlotIndex($i);
            $pk->hotbar[] = $index <= -1 ? -1 : $index + 9;
        }
        $pk->slot = $index;
        $pk->item = clone $this->getItem($index);

        foreach ($target as $player) {
            if ($player === $this->getHolder()) {
                /** @var Player $player */
                $pk->windowid = 0;
                $player->dataPacket(clone $pk);
            } else {
                if (($id = $player->getWindowId($this)) === -1) {
                    $this->close($player);
                    continue;
                }
                $pk->windowid = $id;
                $player->dataPacket(clone $pk);
            }
        }
    }


    /**
     *
     * @return Human|Player
     */
    public function getHolder()
    {
        return parent::getHolder();
    }


    /**
     *
     * @param EntityDamageEvent $source
     * @return unknown
     */
    public function calculateArmorModifiers(EntityDamageEvent $source)
    {
        $protection = 0;

        $protectionEnch = null;
        $modifier = 0;

        if ($source instanceof EntityDamageByEntityEvent || $source instanceof EntityDamageByChildEntityEvent) {
            $damager = $source->getDamager();
        } else {
            $damager = null;
        }

        switch ($source->getCause()) {
        case EntityDamageEvent::CAUSE_FIRE:
        case EntityDamageEvent::CAUSE_FIRE_TICK:
        case EntityDamageEvent::CAUSE_LAVA:
            $protectionEnch = Enchantment::TYPE_ARMOR_FIRE_PROTECTION;
            $modifier = 1.25;
            break;
        case EntityDamageEvent::CAUSE_FALL:
            $protectionEnch = Enchantment::TYPE_ARMOR_FALL_PROTECTION;
            $modifier = 2.5;
            break;
        case EntityDamageEvent::CAUSE_PROJECTILE:
            $protectionEnch = Enchantment::TYPE_ARMOR_PROJECTILE_PROTECTION;
            $modifier = 1.5;
            break;
        case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
        case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
            $protectionEnch = Enchantment::TYPE_ARMOR_EXPLOSION_PROTECTION;
            $modifier = 1.5;
            break;
        }

        foreach ($this->getArmorContents() as $item) {
            $protection += $item->getProtection();

            if ($protectionEnch != null && ($ench = $item->getEnchantment($protectionEnch)) != null) {
                $protection += floor((6 + $ench->getLevel()^2) * $modifier / 3);
            }
        }

        return $protection;
    }
}
