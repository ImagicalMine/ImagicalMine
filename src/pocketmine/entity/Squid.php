<?php
/**
 * src/pocketmine/entity/Squid.php
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

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item as drp;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class Squid extends WaterAnimal implements Ageable
{
    const NETWORK_ID = 17;

    public $width = 0.75;
    public $length = 0.75;
    public $height = 1;

    /** @var Vector3 */
    public $swimDirection = null;
    public $swimSpeed = 0.1;

    private $switchDirectionTicker = 0;

    /**
     *
     */
    public function initEntity()
    {
        $this->setMaxHealth(10);
        parent::initEntity();
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return "Squid";
    }


    /**
     *
     * @param unknown           $damage
     * @param EntityDamageEvent $source
     */
    public function attack($damage, EntityDamageEvent $source)
    {
        parent::attack($damage, $source);
        if ($source->isCancelled()) {
            return;
        }

        if ($source instanceof EntityDamageByEntityEvent) {
            $this->swimSpeed = mt_rand(150, 350) / 2000;
            $e = $source->getDamager();
            $this->swimDirection = (new Vector3($this->x - $e->x, $this->y - $e->y, $this->z - $e->z))->normalize();

            $pk = new EntityEventPacket();
            $pk->eid = $this->getId();
            $pk->event = EntityEventPacket::SQUID_INK_CLOUD;
            Server::broadcastPacket($this->hasSpawned, $pk);
        }
    }


    /**
     *
     * @return unknown
     */
    private function generateRandomDirection()
    {
        return new Vector3(mt_rand(-1000, 1000) / 1000, mt_rand(-500, 500) / 1000, mt_rand(-1000, 1000) / 1000);
    }


    /**
     *
     * @param unknown $currentTick
     * @return unknown
     */
    public function onUpdate($currentTick)
    {
        if ($this->closed !== false) {
            return false;
        }

        if (++$this->switchDirectionTicker === 100) {
            $this->switchDirectionTicker = 0;
            if (mt_rand(0, 100) < 50) {
                $this->swimDirection = null;
            }
        }

        $this->lastUpdate = $currentTick;

        $this->timings->startTiming();

        $hasUpdate = parent::onUpdate($currentTick);

        if ($this->isAlive()) {
            if ($this->y > 62 and $this->swimDirection !== null) {
                $this->swimDirection->y = -0.5;
            }

            $inWater = $this->isInsideOfWater();
            if (!$inWater) {
                $this->motionY -= $this->gravity;
                $this->swimDirection = null;
            } elseif ($this->swimDirection !== null) {
                if ($this->motionX ** 2 + $this->motionY ** 2 + $this->motionZ ** 2 <= $this->swimDirection->lengthSquared()) {
                    $this->motionX = $this->swimDirection->x * $this->swimSpeed;
                    $this->motionY = $this->swimDirection->y * $this->swimSpeed;
                    $this->motionZ = $this->swimDirection->z * $this->swimSpeed;
                }
            } else {
                $this->swimDirection = $this->generateRandomDirection();
                $this->swimSpeed = mt_rand(50, 100) / 2000;
            }

            $expectedPos = new Vector3($this->x + $this->motionX, $this->y + $this->motionY, $this->z + $this->motionZ);

            $this->move($this->motionX, $this->motionY, $this->motionZ);

            if ($expectedPos->distanceSquared($this) > 0) {
                $this->swimDirection = $this->generateRandomDirection();
                $this->swimSpeed = mt_rand(50, 100) / 2000;
            }

            $friction = 1 - $this->drag;

            $this->motionX *= $friction;
            $this->motionY *= 1 - $this->drag;
            $this->motionZ *= $friction;

            $f = sqrt(($this->motionX ** 2) + ($this->motionZ ** 2));
            $this->yaw = (-atan2($this->motionX, $this->motionZ) * 180 / M_PI);
            $this->pitch = (-atan2($f, $this->motionY) * 180 / M_PI);

            if ($this->onGround) {
                $this->motionY *= -0.5;
            }
        }

        $this->timings->stopTiming();

        return $hasUpdate or !$this->onGround or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;
    }


    /**
     *
     * @param Player  $player
     */
    public function spawnTo(Player $player)
    {
        $pk = $this->addEntityDataPacket($player);
        $pk->type = Squid::NETWORK_ID;

        $player->dataPacket($pk);
        parent::spawnTo($player);
    }


    /**
     *
     * @return unknown
     */
    public function getDrops()
    {
        return [
            drp::get(drp::DYE, 0, mt_rand(1, 3))
        ];
    }
}
