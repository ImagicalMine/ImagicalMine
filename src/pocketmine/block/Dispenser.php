<?php

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
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\tile\Dispenser as TileDispenser;

class Dispenser extends Solid{

	protected $id = self::DISPENSER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Dispenser";
	}

    public function canBeActivated(){//At the moment disable, prevent servers crash (For devs, put true if you want check error)
		return false;
	}

	public function getHardness(){
		return 3.5;
	}

    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3,
		];

		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
		$this->getLevel()->setBlock($block, $this, true, true);

		$nbt = new CompoundTag("", [
			new ListTag("Items", []),
			new StringTag("id", Tile::DISPENSER),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);

		if($item->hasCustomName()){
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}

		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}

		Tile::createTile("Dispenser", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		if($player instanceof Player){
			$t = $this->getLevel()->getTile($this);
			$dispenser = null;
			if($t instanceof TileDispenser){
				$dispenser = $t;
			}else{
				$nbt = new CompoundTag("", [
					new ListTag("Items", []),
					new StringTag("id", Tile::DISPENSER),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);
				$nbt->Items->setTagType(NBT::TAG_Compound);
				$dispenser = Tile::createTile("Dispenser", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}

			if(isset($dispenser->namedtag->Lock) and $dispenser->namedtag->Lock instanceof StringTag){
				if($dispenser->namedtag->Lock->getValue() !== $item->getCustomName()){
					return true;
				}
			}

			$player->addWindow($dispenser->getInventory());
		}
		return true;
	}

    public function getDrops(Item $item){
		$drops = [];
		if($item->isPickaxe() >= 1){
			$drops[] = [$this->id, 0, 1];
		}
		return $drops;
	}
}
