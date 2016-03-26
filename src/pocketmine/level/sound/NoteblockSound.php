<?php

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\protocol\BlockEventPacket;
use pocketmine\network\protocol\LevelEventPacket;

class NoteblockSound extends GenericSound{
	
	protected $instrument;
	protected $pitch;

	const INSTRUMENT_PIANO = 0;
	const INSTRUMENT_BASS_DRUM = 1;
	const INSTRUMENT_CLICK = 2;
	const INSTRUMENT_TABOUR = 3;
	const INSTRUMENT_BASS = 4;

	public function __construct(Vector3 $pos, $instrument = self::INSTRUMENT_PIANO, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ANVIL_BREAK, $pitch);
		$this->instrument = $instrument;
		$this->pitch = $pitch;
	}
	
	public function getRandomSound(){
		switch(true){
			case 0:
			self::INSTRUMENT_PIANO;
			case 1: 
			self::INSTRUMENT_BASS_DRUM;
			case 2:
			self::INSTRUMENT_CLICK;
			case 3:
			self::INSTRUMENT_TABOUR;
			case 4:
			self::INSTRUMENT_BASS;
		}
	}

	public function encode(){
		$pk = new BlockEventPacket();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->case1 = $this->instrument;
		$pk->case2 = $this->pitch;

		return $pk;
	}
}
