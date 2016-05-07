<?php
/**
 * src/pocketmine/level/Location.php
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

namespace pocketmine\level;

use pocketmine\math\Vector3;

class Location extends Position
{

    public $yaw;
    public $pitch;

    /**
     *
     * @param int     $x     (optional)
     * @param int     $y     (optional)
     * @param int     $z     (optional)
     * @param float   $yaw   (optional)
     * @param float   $pitch (optional)
     * @param Level   $level (optional)
     */
    public function __construct($x = 0, $y = 0, $z = 0, $yaw = 0.0, $pitch = 0.0, Level $level = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->yaw = $yaw;
        $this->pitch = $pitch;
        $this->level = $level;
    }


    /**
     *
     * @param Vector3    $pos
     * @param Level|null $level default null
     * @param float      $yaw   (optional) default 0.0
     * @param float      $pitch (optional) default 0.0
     * @return unknown
     */
    public static function fromObject(Vector3 $pos, Level $level = null, $yaw = 0.0, $pitch = 0.0)
    {
        return new Location($pos->x, $pos->y, $pos->z, $yaw, $pitch, ($level === null) ? (($pos instanceof Position) ? $pos->level : null) : $level);
    }


    /**
     *
     * @return unknown
     */
    public function getYaw()
    {
        return $this->yaw;
    }


    /**
     *
     * @return unknown
     */
    public function getPitch()
    {
        return $this->pitch;
    }


    /**
     *
     * @return unknown
     */
    public function __toString()
    {
        return "Location (level=" . ($this->isValid() ? $this->getLevel()->getName() : "null") . ", x=$this->x, y=$this->y, z=$this->z, yaw=$this->yaw, pitch=$this->pitch)";
    }
}
