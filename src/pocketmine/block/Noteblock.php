<?php
/**
 * src/pocketmine/block/Noteblock.php
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
 * OpenGenisys Project
 */
namespace pocketmine\block;

use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\level\sound\NoteblockSound;
use pocketmine\Player;
use pocketmine\Server;

class Noteblock extends Solid implements RedstoneConsumer
{
    
    protected $id = self::NOTEBLOCK;
    protected $downSideId = null;

    /**
     *
     * @param unknown $meta (optional)
     */
    public function __construct($meta = 0)
    {
        $this->meta = $meta;
    }


    /**
     *
     * @return unknown
     */
    public function getHardness()
    {
        return 0.8;
    }


    /**
     *
     * @return unknown
     */
    public function getResistance()
    {
        return 4;
    }


    /**
     *
     * @return unknown
     */
    public function getToolType()
    {
        return Tool::TYPE_AXE;
    }


    /**
     *
     * @return unknown
     */
    public function canBeActivated()
    {
        return true;
    }


    /**
     *
     * @return unknown
     */
    public function getStrength()
    {
        if ($this->meta < 24) {
            $this->meta ++;
        } else {
            $this->meta = 0;
        }
        $this->getLevel()->setBlock($this, $this);
        return $this->meta * 1;
    }


    /**
     *
     * @param Item    $item
     * @param Player  $player (optional)
     * @return unknown
     */
    public function onActivate(Item $item, Player $player = null)
    {
        switch ($this->downSideId) {
            case self::GLASS:
            case self::GLOWSTONE:
                $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_CLICK, $this->getStrength()));
                break;
            case self::SAND:
            case self::GRAVEL:
                $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_TABOUR, $this->getStrength()));
                break;
            case self::WOOD:
                $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_BASS, $this->getStrength()));
                break;
            case self::STONE:
                $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_BASS_DRUM, $this->getStrength()));
                break;
            default:
                $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::INSTRUMENT_PIANO, $this->getStrength()));
                break;
        }
        return true;
    }


    /**
     *
     * @param unknown $type
     * @return unknown
     */
    public function onUpdate($type)
    {
        $this->downSideId = $this->getSide(0)->getId();
        return parent::onUpdate($type);
    }


    /**
     *
     * @param Item    $item
     * @param Block   $block
     * @param Block   $target
     * @param unknown $face
     * @param unknown $fx
     * @param unknown $fy
     * @param unknown $fz
     * @param Player  $player (optional)
     * @return unknown
     */
    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null)
    {
        $this->downSideId = $this->getSide(0)->getId();
        return parent::place($item, $block, $target, $face, $fx, $fy, $fz, $player);
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return "Noteblock";
    }


    /**
     * overriding Block::onRedstoneUpdate
     * is causing memory leak if noteblock is activated
     *
     * @param unknown $type
     * @param unknown $power
     * @return unknown
     */
    public function onRedstoneUpdate($type, $power)
    {
        $this->server = Server::getInstance();
        $this->getLevel()->addSound(new NoteblockSound($this, NoteblockSound::getRandomSound(), $this->getStrength()));

        return true;
    }
}
