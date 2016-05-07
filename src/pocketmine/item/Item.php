<?php
/**
 * All the Item classes
 *
 * @package default
 */


namespace pocketmine\item;


use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\MinecartFurnace;
use pocketmine\inventory\Fuel;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\level\Level;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\NBT;


class Item extends ItemContainer{

	protected      $block;
	private        $cachedNBT = null;
	public         $count;
	protected      $durability = 0;
	protected      $id;
	protected      $meta;
	protected      $name;
	private        $tags = "";

    /**
     * Item constructor.
     * @param $id
     * @param int $meta
     * @param int $count
     * @param string $name
     */
	public function __construct($id, $meta = 0, $count = 1, $name = "Unknown") {
		$this->id    = $id & 0xffff;
		$this->meta  = $meta !== null ? $meta & 0xffff : null;
		$this->count = (int) $count;
		$this->name  = $name;
		if (!isset($this->block) and $this->id <= 0xff and isset(Block::$list[$this->id])) {
			$this->block = Block::get($this->id, $this->meta);
			$this->name = $this->block->getName();
		}
	}


	/**
	 *
	 * @return string
	 */
	public function __toString(){
		return (string) "Item " . $this->name . " (" . $this->id . ":" . ($this->meta === null ? "?" : $this->meta) . ")x" . $this->count . ($this->hasCompoundTag() ? " tags:0x".bin2hex($this->getCompoundTag()) : "");
	}


	/**
	 *
	 * @param Enchantment $ench
	 */
	public function addEnchantment(Enchantment $ench) {
		if (!$this->hasCompoundTag()) {
			$tag = new CompoundTag("", []);
		}else {
			$tag = $this->getNamedTag();
		}
		if (!isset($tag->ench)) {
			$tag->ench = new ListTag("ench", []);
			$tag->ench->setTagType(NBT::TAG_Compound);
		}
		$found = false;
		foreach ($tag->ench as $k => $entry) {
			if ($entry["id"] === $ench->getId()) {
				$tag->ench->{$k} = new CompoundTag("", [
						"id" => new ShortTag("id", $ench->getId()),
						"lvl" => new ShortTag("lvl", $ench->getLevel())
					]);
				$found = true;
				break;
			}
		}
		if (!$found) {
			$tag->ench->{count($tag->ench) + 1} = new CompoundTag("", [
					"id" => new ShortTag("id", $ench->getId()),
					"lvl" => new ShortTag("lvl", $ench->getLevel())
				]);
		}
		$this->setNamedTag($tag);
	}


	/**
	 *
	 * @return unknown
	 */
	public function canBeActivated() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function canBePlaced() {
		return $this->block !== null and $this->block->canBePlaced();
	}


	/**
	 *
	 */
	public static function clearCreativeItems() {
		self::$creative = [];
	}


	/**
	 *
	 * @return unknown
	 */
	public function clearCustomBlockData() {
		if (!$this->hasCompoundTag()) {
			return $this;
		}
		$tag = $this->getNamedTag();
		if (isset($tag->BlockEntityTag) and $tag->BlockEntityTag instanceof CompoundTag) {
			unset($tag->display->BlockEntityTag);
			$this->setNamedTag($tag);
		}
		return $this;
	}


	/**
	 *
	 * @return unknown
	 */
	public function clearCustomName() {
		if (!$this->hasCompoundTag()) {
			return $this;
		}
		$tag = $this->getNamedTag();
		if (isset($tag->display) and $tag->display instanceof CompoundTag) {
			unset($tag->display->Name);
			if ($tag->display->getCount() === 0) {
				unset($tag->display);
			}
			$this->setNamedTag($tag);
		}
		return $this;
	}


	/**
	 *
	 * @return unknown
	 */
	public function clearNamedTag() {
		return $this->setCompoundTag("");
	}


	/**
	 *
	 * @param Item    $item
	 * @param unknown $checkDamage   (optional)
	 * @param unknown $checkCompound (optional)
	 * @return unknown
	 */
	public function deepEquals(Item $item, $checkDamage = true, $checkCompound = true) {
		if ($this->equals($item, $checkDamage, $checkCompound)) {
			return true;
		}elseif ($item->hasCompoundTag() and $this->hasCompoundTag()) {
			return NBT::matchTree($this->getNamedTag(), $item->getNamedTag());
		}
		return false;
	}


	/**
	 *
	 * @param Item    $item
	 * @param unknown $checkDamage   (optional)
	 * @param unknown $checkCompound (optional)
	 * @return unknown
	 */
	public function equals(Item $item, $checkDamage = true, $checkCompound = true) : bool{
		return $this->id === $item->getId() and ($checkDamage === false or $this->getDamage() === $item->getDamage()) and ($checkCompound === false or $this->getCompoundTag() === $item->getCompoundTag());
	}


	/**
	 *
	 * @param unknown $str
	 * @param unknown $multiple (optional)
	 * @return unknown
	 */
	public static function fromString($str, $multiple = false) {
		if ($multiple === true) {
			$blocks = [];
			foreach (explode(",", $str) as $b) {
				$blocks[] = self::fromString($b, false);
			}
			return $blocks;
		}else {
			$b = explode(":", str_replace([" ", "minecraft:"], ["_", ""], trim($str)));
			if (!isset($b[1])) {
				$meta = 0;
			}else {
				$meta = $b[1] & 0xFFFF;
			}
			if (defined(self::class . "::" . strtoupper($b[0]))) {
				$item = self::get(constant(self::class . "::" . strtoupper($b[0])), $meta);
				if ($item->getId() === self::AIR and strtoupper($b[0]) !== "AIR") {
					$item = self::get($b[0] & 0xFFFF, $meta);
				}
			} else {
				$item = self::get($b[0] & 0xFFFF, $meta);
			}
			return $item;
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getBlock() : Block{
		if ($this->block instanceof Block) {
			return clone $this->block;
		}else {
			return Block::get(self::AIR);
		}
	}


	/**
	 *
	 * @return string
	 */
	public function getCompoundTag() {
		return $this->tags;
	}


	/**
	 *
	 * @param unknown $index
	 * @return Item
	 */
	public static function getCreativeItem($index) {
		return isset(self::$creative[$index]) ? self::$creative[$index] : null;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public static function getCreativeItemIndex(Item $item) {
		foreach (self::$creative as $i => $d) {
			if ($item->equals($d, !$item->isTool())) {
				return $i;
			}
		}
		return -1;
	}


	/**
	 *
	 * @return unknown
	 */
	public static function getCreativeItems() {
		return self::$creative;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getCount() {
		return $this->count;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getCustomBlockData() {
		if (!$this->hasCompoundTag()) {
			return null;
		}
		$tag = $this->getNamedTag();
		if (isset($tag->BlockEntityTag) and $tag->BlockEntityTag instanceof CompoundTag) {
			return $tag->BlockEntityTag;
		}
		return null;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getCustomName() {
		if (!$this->hasCompoundTag()) {
			return "";
		}
		$tag = $this->getNamedTag();
		if (isset($tag->display)) {
			$tag = $tag->display;
			if ($tag instanceof CompoundTag and isset($tag->Name) and $tag->Name instanceof StringTag) {
				return $tag->Name->getValue();
			}
		}
		return "";
	}


	/**
	 *
	 * @return unknown
	 */
	public function getDamage() {
		return $this->meta;
	}


	/**
	 *
	 * @param Block   $block
	 * @param Player  $player
	 * @return unknown
	 */
	public function getDestroySpeed(Block $block, Player $player) {
		return 1;
	}


	/**
	 *
	 * @param unknown $id
	 * @return Enchantment|null
	 */
	public function getEnchantment($id) {
		if (!$this->hasEnchantments()) {
			return null;
		}
		foreach ($this->getNamedTag()->ench as $entry) {
			if ($entry["id"] === $id) {
				$e = Enchantment::getEnchantment($entry["id"]);
				$e->setLevel($entry["lvl"]);
				return $e;
			}
		}
		return null;
	}


	/**
	 *
	 * @return Enchantment[]
	 */
	public function getEnchantments() {
		if (!$this->hasEnchantments()) {
			return [];
		}
		$enchantments = [];
		foreach ($this->getNamedTag()->ench as $entry) {
			$e = Enchantment::getEnchantment($entry["id"]);
			$e->setLevel($entry["lvl"]);
			$enchantments[] = $e;
		}
		return $enchantments;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getFuelTime() {
		if (!isset(Fuel::$duration[$this->id])) {
			return null;
		}
		if ($this->id !== self::BUCKET or $this->meta === 10) {
			return Fuel::$duration[$this->id];
		}
		return null;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getId() : int{
		return $this->id;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getName() {
		return (string) $this->hasCustomName() ? $this->getCustomName() : $this->name;
	}


	/**
	 *
	 * @param unknown $name
	 * @return unknown
	 */
	public function getNamedTagEntry($name) {
		$tag = $this->getNamedTag();
		if ($tag !== null) {
			return isset($tag->{$name}) ? $tag->{$name} : null;
		}
		return null;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getNamedTag() {
		if (!$this->hasCompoundTag()) {
			return null;
		}elseif ($this->cachedNBT !== null) {
			return $this->cachedNBT;
		}
		return $this->cachedNBT = self::parseCompoundTag($this->tags);
	}


	/**
	 *
	 * @return int|bool
	 */
	public function getMaxDurability() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getMaxStackSize() {
		return 64;
	}


	/**
	 *
	 * @return unknown
	 */
	public function hasCompoundTag() {
		return $this->tags !== "" and $this->tags !== null;
	}


	/**
	 *
	 * @return unknown
	 */
	public function hasCustomBlockData() {
		if (!$this->hasCompoundTag()) {
			return false;
		}
		$tag = $this->getNamedTag();
		if (isset($tag->BlockEntityTag) and $tag->BlockEntityTag instanceof CompoundTag) {
			return true;
		}
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function hasEnchantments() {
		if (!$this->hasCompoundTag()) {
			return false;
		}
		$tag = $this->getNamedTag();
		if (isset($tag->ench)) {
			$tag = $tag->ench;
			if ($tag instanceof ListTag) {
				return true;
			}
		}
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function hasCustomName() {
		if (!$this->hasCompoundTag()) {
			return false;
		}
		$tag = $this->getNamedTag();
		if (isset($tag->display)) {
			$tag = $tag->display;
			if ($tag instanceof CompoundTag and isset($tag->Name) and $tag->Name instanceof StringTag) {
				return true;
			}
		}
		return false;
	}


	/**
	 *
	 */
	public static function init() {
		if (!self::$list instanceof \SplFixedArray) {
			//self::$list = new \SplFixedArray(65536);
			self::$list = \SplFixedArray::fromArray(self::$list, true);
		}
		for ($i = 0; $i < 256; ++$i) {
			if (Block::$list[$i] !== null) {
				self::$list[$i] = Block::$list[$i];
			}
		}
		self::initCreativeItems();
	}


	/**
	 *
	 */
	private static function initCreativeItems() {
		self::clearCreativeItems();
		self::buildingTab();
		self::decorationTab();
		self::toolsTab();
		self::seedsTab();
	}


	/**
	 *
	 * @return unknown
	 */
	public function isAxe() {
		return false;
	}


	/**
	 *
	 * @param Item    $item
	 * @return unknown
	 */
	public static function isCreativeItem(Item $item) {
		foreach (self::$creative as $i => $d) {
			if ($item->equals($d, !$item->isTool())) {
				return true;
			}
		}
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isHoe() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isPickaxe() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isShears() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isShovel() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isSword() {
		return false;
	}


	/**
	 *
	 * @return bool
	 */
	public function isTool() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isHelmet() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isChestplate() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isLeggings() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isBoots() {
		return false;
	}


	/**
	 *
	 * @return bool
	 */
	public function isArmor() {
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getProtection() {
		if (($ench = $this->getEnchantment(Enchantment::TYPE_ARMOR_PROTECTION)) != null) {
			return floor((6 + $ench->getLevel() ^ 2) * 0.75 / 3 );
		}

		return 0;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getHpDamage() {
		return 1;
	}


	/**
	 *
	 * @param Level   $level
	 * @param Player  $player
	 * @param Block   $block
	 * @param Block   $target
	 * @param unknown $face
	 * @param unknown $fx
	 * @param unknown $fy
	 * @param unknown $fz
	 * @return unknown
	 */
	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz) {
		return false;
	}


	/**
	 * onPlayerAction
	 * use this method in item classes to handle specific logic
	 * this method is added to remove item based logic from Player class
	 * can be called in Player for individual logic required for an item
	 * example:
	 * if ($playerAction == PlayerActionPacket::ACTION_JUMP) {
	 *     //do something
	 * }
	 *
	 *
	 * @param Player  $player
	 * @param int     $playerAction - defined in PlayerActionPacket
	 * @return bool
	 */
	public function onPlayerAction(Player $player, $playerAction) {
		//override in specific item class
		//if ($playerAction == PlayerActionPacket::ACTION_JUMP) {
		//do something
		//}
		return true;
	}


	/**
	 *
	 * @param unknown $tag
	 * @return CompoundTag
	 */
	private static function parseCompoundTag($tag) {
		if (self::$cachedParser === null) {
			self::$cachedParser = new NBT(NBT::LITTLE_ENDIAN);
		}
		self::$cachedParser->read($tag);
		return self::$cachedParser->getData();
	}


	/**
	 *
	 * @param Item    $item
	 */
	public static function removeCreativeItem(Item $item) {
		$index = self::getCreativeItemIndex($item);
		if ($index !== -1) {
			unset(self::$creative[$index]);
		}
	}


	/**
	 *
	 * @param unknown $tags
	 * @return unknown
	 */
	public function setCompoundTag($tags) {
		if ($tags instanceof CompoundTag) {
			$this->setNamedTag($tags);
		}else {
			$this->tags = $tags;
			$this->cachedNBT = null;
		}
		return $this;
	}


	/**
	 *
	 * @param unknown $count
	 */
	public function setCount($count) {
		$this->count = (int) $count;
	}


	/**
	 *
	 * @param CompoundTag $compound
	 * @return unknown
	 */
	public function setCustomBlockData(CompoundTag $compound) {
		$tags = clone $compound;
		$tags->setName("BlockEntityTag");
		if (!$this->hasCompoundTag()) {
			$tag = new CompoundTag("", []);
		}else {
			$tag = $this->getNamedTag();
		}
		$tag->BlockEntityTag = $tags;
		$this->setNamedTag($tag);
		return $this;
	}


	/**
	 *
	 * @param unknown $name
	 * @return unknown
	 */
	public function setCustomName($name) {
		if ((string) $name === "") {
			$this->clearCustomName();
		}
		if (!$this->hasCompoundTag()) {
			$tag = new CompoundTag("", []);
		}else {
			$tag = $this->getNamedTag();
		}
		if (isset($tag->display) and $tag->display instanceof CompoundTag) {
			$tag->display->Name = new StringTag("Name", $name);
		}else {
			$tag->display = new CompoundTag("display", [
					"Name" => new StringTag("Name", $name)
				]);
		}
		return $this;
	}


	/**
	 *
	 * @param unknown $color
	 * @return unknown
	 */
	public function setCustomColor($color) {
		if (!$this->hasCompoundTag()) {
			$tag = new CompoundTag("", []);
		}else {
			$tag = $this->getNamedTag();
		}

		$tag->customColor = new IntTag("customColor", $color);

		$this->setNamedTag($tag);
		return $this;
	}


	/**
	 *
	 * @param unknown $meta
	 */
	public function setDamage($meta) {
		$this->meta = $meta !== null ? $meta & 0xFFFF : null;
	}


	/**
	 *
	 * @param CompoundTag $tag
	 * @return unknown
	 */
	public function setNamedTag(CompoundTag $tag) {
		if ($tag->getCount() === 0) {
			return $this->clearNamedTag();
		}
		$this->cachedNBT = $tag;
		$this->tags = self::writeCompoundTag($tag);
		return $this;
	}


	/**
	 *
	 * @param Entity|Block $object
	 * @return bool
	 */
	public function useOn($object) {
		return false;
	}


	/**
	 *
	 * @param CompoundTag $tag
	 * @return unknown
	 */
	private static function writeCompoundTag(CompoundTag $tag) {
		if (self::$cachedParser === null) {
			self::$cachedParser = new NBT(NBT::LITTLE_ENDIAN);
		}
		self::$cachedParser->setData($tag);
		return self::$cachedParser->write();
	}


}
