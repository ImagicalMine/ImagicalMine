
<?php
namespace pocketmine\tile;
use pocketmine\inventory\DispenserInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\{CompoundTag, ListTag, IntTag, StringTag};
use pocketmine\network\protocol\ContainerSetDataPacket;
class Dispenser extends Spawnable implements InventoryHolder, Container, Nameable{
	/** @var DropperInventory */
	protected $inventory;
	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new DropperInventory($this);
	}
	public function getSize(){
		return 9;
	}
	/**
	 * @return DropperInventory
	 */
	public function getInventory(){
		return $this->inventory;
	}
	public function getName(){
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Dropper";
	}
	public function hasName(){
		return isset($this->namedtag->CustomName);
	}
	public function setName($str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}
		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}
	public function getSpawnCompound(){
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::DROPPER),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
		]);
		if($this->hasName()){
			$nbt->CustomName = $this->namedtag->CustomName;
		}
		return $nbt;
	}
	public function close(){
		if($this->closed === false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
			}
			parent::close();
		}
	}
	public function saveNBT(){
		$this->namedtag->Items = new ListTag("Items", []);
		$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		for($index = 0; $index < $this->getSize(); ++$index){
			$this->setItem($index, $this->inventory->getItem($index));
		}
	}
	protected function getSlotIndex($index){
		foreach($this->namedtag->Items as $i => $slot){
			if($slot["Slot"] === $index){
				return $i;
			}
		}
		return -1;
	}
	public function getItem($index){
		$i = $this->getSlotIndex($index);
		if($i < 0){
			return Item::get(Item::AIR, 0, 0);
		}else{
			return NBT::getItemHelper($this->namedtag->Items[$i]);
		}
	}
	public function setItem($index, Item $item){
		$i = $this->getSlotIndex($index);
		$d = NBT::putItemHelper($item, $index);
		if($item->getId() === Item::AIR or $item->getCount() <= 0){
			if($i >= 0){
				unset($this->namedtag->Items[$i]);
			}
		}elseif($i < 0){
			for($i = 0; $i <= $this->getSize(); ++$i){
				if(!isset($this->namedtag->Items[$i])){
					break;
				}
			}
			$this->namedtag->Items[$i] = $d;
		}else{
			$this->namedtag->Items[$i] = $d;
		}
		return true;
	}
	public function onUpdate(){
		if($this->closed === true){
			return false;
		}
		$this->timings->startTiming();
		foreach($this->getInventory()->getViewers() as $player){
			$windowId = $player->getWindowId($this->getInventory());
			if($windowId > 0){
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$player->dataPacket($pk);
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$player->dataPacket($pk);
			}
		}
		$this->lastUpdate = microtime(true);
		$this->timings->stopTiming();
		return true;
	}
}
