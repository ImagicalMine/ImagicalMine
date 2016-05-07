<?php
/**
 * src/pocketmine/entity/Attribute.php
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

use pocketmine\network\protocol\UpdateAttributesPacket;
use pocketmine\Player;

class Attribute
{

    private $id;
    protected $minValue;
    protected $maxValue;
    protected $defaultValue;
    protected $currentValue;
    protected $name;
    protected $shouldSend;

    /** @var Player */
    protected $player;

    /**
     *
     * @param unknown $id
     * @param unknown $name
     * @param unknown $minValue
     * @param unknown $maxValue
     * @param unknown $defaultValue
     * @param unknown $shouldSend
     * @param unknown $player
     */
    public function __construct($id, $name, $minValue, $maxValue, $defaultValue, $shouldSend, $player)
    {
        $this->id = (int) $id;
        $this->name = (string) $name;
        $this->minValue = (float) $minValue;
        $this->maxValue = (float) $maxValue;
        $this->defaultValue = (float) $defaultValue;
        $this->shouldSend = (float) $shouldSend;

        $this->currentValue = $this->defaultValue;
        $this->player = $player;
    }


    /**
     *
     * @return unknown
     */
    public function getMinValue()
    {
        return $this->minValue;
    }


    /**
     *
     * @param unknown $minValue
     * @return unknown
     */
    public function setMinValue($minValue)
    {
        if ($minValue > $this->getMaxValue()) {
            throw new \InvalidArgumentException("Value $minValue is bigger than the maxValue!");
        }

        $this->minValue = $minValue;
        return $this;
    }


    /**
     *
     * @return unknown
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }


    /**
     *
     * @param unknown $maxValue
     * @return unknown
     */
    public function setMaxValue($maxValue)
    {
        if ($maxValue < $this->getMinValue()) {
            throw new \InvalidArgumentException("Value $maxValue is bigger than the minValue!");
        }

        $this->maxValue = $maxValue;
        return $this;
    }


    /**
     *
     * @return unknown
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }


    /**
     *
     * @param unknown $defaultValue
     * @return unknown
     */
    public function setDefaultValue($defaultValue)
    {
        if ($defaultValue > $this->getMaxValue() or $defaultValue < $this->getMinValue()) {
            throw new \InvalidArgumentException("Value $defaultValue exceeds the range!");
        }

        $this->defaultValue = $defaultValue;
        return $this;
    }


    /**
     *
     * @return unknown
     */
    public function getValue()
    {
        return $this->currentValue;
    }


    /**
     *
     * @param unknown $value
     */
    public function setValue($value)
    {
        if ($value > $this->getMaxValue()) {
            $value = $this->getMaxValue();
        }
        if ($value < $this->getMinValue()) {
            $value = $this->getMinValue();
        }

        $this->currentValue = $value;

        if ($this->shouldSend) {
            $this->send();
        }
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     *
     * @return unknown
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     *
     * @return unknown
     */
    public function isSyncable()
    {
        return $this->shouldSend;
    }


    /**
     *
     */
    public function send()
    {
        $pk = new UpdateAttributesPacket();
        $pk->maxValue = $this->getMaxValue();
        $pk->minValue = $this->getMinValue();
        $pk->value = $this->currentValue;
        $pk->name = $this->getName();
        $pk->entityId = 0;
        $pk->encode();
        $this->player->dataPacket($pk);
    }
}
