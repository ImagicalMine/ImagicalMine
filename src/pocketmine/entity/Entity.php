<?php
/**
 * src/pocketmine/entity/Entity.php
 *
 * @package default
 */


namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\block\Portal;
use pocketmine\block\Water;
use pocketmine\entity\utils\Movement;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Timings;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Math;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\metadata\Metadatable;
use pocketmine\metadata\MetadataValue;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\MobEffectPacket;
use pocketmine\network\protocol\RemoveEntityPacket;
use pocketmine\network\protocol\SetEntityDataPacket;
use pocketmine\network\protocol\SetEntityLinkPacket;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\ChunkException;

abstract class Entity extends Location implements Metadatable{

	const DATA_AIR = 1;
	const DATA_FLAG_ACTION = 4;
	const DATA_FLAG_INVISIBLE = 5;
	const DATA_FLAG_ONFIRE = 0;
	const DATA_FLAG_RIDING = 2;
	const DATA_FLAG_SNEAKING = 1;
	const DATA_FLAG_SPRINTING = 3;
	const DATA_FLAGS = 0;
	const DATA_NAMETAG = 2;
	const DATA_NO_AI = 15;
	const DATA_POTION_AMBIENT = 8;
	const DATA_POTION_COLOR = 7;
	const DATA_SHOW_NAMETAG = 3;
	const DATA_SILENT = 4;
	const DATA_TYPE_BYTE = 0;
	const DATA_TYPE_FLOAT = 3;
	const DATA_TYPE_INT = 2;
	const DATA_TYPE_LONG = 8;
	const DATA_TYPE_POS = 6;
	const DATA_TYPE_ROTATION = 7;
	const DATA_TYPE_SHORT = 1;
	const DATA_TYPE_SLOT = 5;
	const DATA_TYPE_STRING = 4;
	/** EntityLink **/
	const LINK_EMPTY = 0;
	const LINK_MASTER = 1;
	const LINK_SLAVE = 2;
	const NETWORK_ID = -1;

	protected $age = 0;
	/** @var Block[] */
	private $blocksAround = array();
	/** @var AxisAlignedBB */
	public $boundingBox;
	public $canCollide = true;
	/** @var Chunk */
	public $chunk;
	public $closed = false;
	protected $dataFlags = 0;

	protected $dataProperties = [
		self::DATA_FLAGS => [self::DATA_TYPE_BYTE, 0],
		self::DATA_AIR => [self::DATA_TYPE_SHORT, 300],
		self::DATA_NAMETAG => [self::DATA_TYPE_STRING, ""],
		self::DATA_SHOW_NAMETAG => [self::DATA_TYPE_BYTE, 1],
		self::DATA_SILENT => [self::DATA_TYPE_BYTE, 0],
		self::DATA_NO_AI => [self::DATA_TYPE_BYTE, 0],
	];
	public $deadTicks = 0;
	protected $drag;
	/** @var Effect[] */
	protected $effects = array();
	public static $entityCount = 1;
	public $eyeHeight = null;
	public $fallDistance = 0;
	protected $fireProof;
	public $fireTicks = 0;
	protected $gravity;

	/**
	 *
	 * @var Player[]
	 */
	protected $hasSpawned = array();
	/** @var int */
	private $health = 20;
	public $height;

	protected $id;
	public $inBlock = false;
	private $invulnerable;
	public $isCollided = false;
	public $isCollidedHorizontally = false;
	public $isCollidedVertically = false;
	protected $islinked = false;
	protected $isPlayer = false;
	protected $isStatic = false;
	protected $justCreated;
	public $keepMovement = false;
	/** @var Entity[] */
	private static $knownEntities = array();
	protected $lastDamageCause = null;
	public $lastMotionX;
	public $lastMotionY;
	public $lastMotionZ;
	public $lastPitch;
	public $lastUpdate;
	public $lastX = null;
	public $lastY = null;
	public $lastYaw;
	public $lastZ = null;
	public $length;
	protected $linkedEntity = null;
	protected $linkedTarget = null;
	protected $linkedType = null;
	public $maxFireTicks;
	private $maxHealth = 20;
	public $motionChanged;
	public $motionX;
	public $motionY;
	public $motionZ;
	public $namedtag;
	public $noDamageTicks;
	public $onGround;
	public $passenger = null;
	public $positionChanged;
	protected $riding = null;
	/** @var Server */
	protected $server;
	private static $shortNames = array();
	protected $stepHeight = 0;
	/** @var Vector3 */
	public $temporalVector;
	public $ticksLived = 0;
	/** @var \pocketmine\event\TimingsHandler */
	protected $timings;
	public $vehicle = null;
	public $width;
	protected $ySize = 0;

	/**
	 *
	 * @param FullChunk   $chunk
	 * @param CompoundTag $nbt
	 */
	public function __construct(FullChunk $chunk, CompoundTag $nbt) {
		if ($chunk === null or $chunk->getProvider() === null) {
			throw new ChunkException("Invalid garbage Chunk given to Entity");
		}
		$this->timings = Timings::getEntityTimings($this);
		$this->isPlayer = $this instanceof Player;
		$this->temporalVector = new Vector3();
		if ($this->eyeHeight === null) {
			$this->eyeHeight = $this->height / 2 + 0.1;
		}
		$this->id = Entity::$entityCount++;
		$this->justCreated = true;
		$this->namedtag = $nbt;
		$this->chunk = $chunk;
		$this->setLevel($chunk->getProvider()->getLevel());
		$this->server = $chunk->getProvider()->getLevel()->getServer();
		$this->boundingBox = new AxisAlignedBB(0, 0, 0, 0, 0, 0);
		$this->setPositionAndRotation(
			$this->temporalVector->setComponents(
				$this->namedtag["Pos"][0],
				$this->namedtag["Pos"][1],
				$this->namedtag["Pos"][2]
			),
			$this->namedtag->Rotation[0],
			$this->namedtag->Rotation[1]
		);
		$this->setMotion($this->temporalVector->setComponents($this->namedtag["Motion"][0], $this->namedtag["Motion"][1], $this->namedtag["Motion"][2]));
		assert(!is_nan($this->x) and !is_infinite($this->x) and !is_nan($this->y) and !is_infinite($this->y) and !is_nan($this->z) and !is_infinite($this->z));
		if (!isset($this->namedtag->FallDistance)) {
			$this->namedtag->FallDistance = new FloatTag("FallDistance", 0);
		}
		$this->fallDistance = $this->namedtag["FallDistance"];
		if (!isset($this->namedtag->Fire)) {
			$this->namedtag->Fire = new ShortTag("Fire", 0);
		}
		$this->fireTicks = $this->namedtag["Fire"];
		if (!isset($this->namedtag->Air)) {
			$this->namedtag->Air = new ShortTag("Air", 300);
		}
		$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, $this->namedtag["Air"]);
		if (!isset($this->namedtag->OnGround)) {
			$this->namedtag->OnGround = new ByteTag("OnGround", 0);
		}
		$this->onGround = $this->namedtag["OnGround"] > 0 ? true : false;
		if (!isset($this->namedtag->Invulnerable)) {
			$this->namedtag->Invulnerable = new ByteTag("Invulnerable", 0);
		}
		$this->invulnerable = $this->namedtag["Invulnerable"] > 0 ? true : false;
		$this->chunk->addEntity($this);
		$this->level->addEntity($this);
		$this->initEntity();
		$this->lastUpdate = $this->server->getTick();
		$this->server->getPluginManager()->callEvent(new EntitySpawnEvent($this));
		$this->scheduleUpdate();
	}


	/**
	 *
	 */
	public function __destruct() {
		$this->close();
	}


	/**
	 *
	 * @return unknown
	 */
	public function __toString() {
		return (new \ReflectionClass($this))->getShortName() . "(" . $this->getId() . ")";
	}


	/**
	 *
	 * @param Effect  $effect
	 */
	public function addEffect(Effect $effect) {
		if (isset($this->effects[$effect->getId()])) {
			$oldEffect = $this->effects[$effect->getId()];
			if (
				abs($effect->getAmplifier()) <= ($oldEffect->getAmplifier())
				or (abs($effect->getAmplifier()) === abs($oldEffect->getAmplifier())
					and $effect->getDuration() < $oldEffect->getDuration())
			) {
				return;
			}
			$effect->add($this, true);
		}else {
			$effect->add($this, false);
		}
		$this->effects[$effect->getId()] = $effect;
		$this->recalculateEffectColor();
		if ($effect->getId() === Effect::HEALTH_BOOST) {
			$this->setHealth($this->getHealth() + 4 * ($effect->getAmplifier() + 1));
		}
		if ($effect->getId() === Effect::HEALING) {
			if ($this->getHealth() + 2 * ($effect->getAmplifier() + 1) > $this->getMaxHealth()) {
				$ev = new EntityRegainHealthEvent($this, $this->getMaxHealth() - $this->getHealth(), EntityRegainHealthEvent::CAUSE_MAGIC);
				$this->heal($ev->getAmount(), $ev);
			}else {
				$ev = new EntityRegainHealthEvent($this, 2 * ($effect->getAmplifier() + 1), EntityRegainHealthEvent::CAUSE_MAGIC);
				$this->heal($ev->getAmount(), $ev);
			}
		}
		if ($effect->getId() === Effect::HARMING) {
			if ($this->getHealth() - 3 * ($effect->getAmplifier() + 1) < 0) {
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_MAGIC, $this->getHealth());
				$this->attack($ev->getFinalDamage(), $ev);
			}else {
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_MAGIC, 3 * ($effect->getAmplifier() + 1));
				$this->attack($ev->getFinalDamage(), $ev);
			}
		}
	}


	/**
	 *
	 * @param Player  $player
	 * @return unknown
	 */
	protected function addEntityDataPacket(Player $player) : AddEntityPacket{
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		return $pk;
	}


	/**
	 *
	 * @param float             $damage
	 * @param EntityDamageEvent $source
	 */
	public function attack($damage, EntityDamageEvent $source) {
		if ($this->hasEffect(Effect::FIRE_RESISTANCE)
			and ($source->getCause() === EntityDamageEvent::CAUSE_FIRE
				or $source->getCause() === EntityDamageEvent::CAUSE_FIRE_TICK
				or $source->getCause() === EntityDamageEvent::CAUSE_LAVA)) {
			$source->setCancelled();
		}
		if ($source instanceof EntityDamageByEntityEvent && $source->getCause() === EntityDamageEvent::CAUSE_PROJECTILE) {
			$e = $source->getDamager();
			if ($e instanceof Player) {
				if ($e->getGamemode() === 3) {
					$source->setCancelled();
				}
			}
			if ($source instanceof EntityDamageByChildEntityEvent) {
				$e = $source->getChild();
			}
			if ($e instanceof ThrownExpBottle || $e instanceof ThrownPotion) $source->setCancelled();
		}
		$this->server->getPluginManager()->callEvent($source);
		if ($source->isCancelled()) {
			return;
		}
		$this->setLastDamageCause($source);
		($this->getHealth() - $source->getFinalDamage() <= 0) ? $this->setHealth(0) : $this->setHealth($this->getHealth() - $source->getFinalDamage());
	}


	/**
	 *
	 * @param Entity  $entity
	 * @return unknown
	 */
	public function canCollideWith(Entity $entity) {
		return !$this->justCreated and $entity !== $this;
	}


	/**
	 *
	 * @return unknown
	 */
	public function canTriggerWalking() {
		return true;
	}


	/**
	 *
	 */
	protected function checkBlockCollision() {
		$vector = new Vector3(0, 0, 0);
		foreach ($this->getBlocksAround() as $block) {
			$block->onEntityCollide($this);
			$block->addVelocityToEntity($this, $vector);
		}
		if ($vector->lengthSquared() > 0) {
			$vector = $vector->normalize();
			$d = 0.014;
			$this->motionX += $vector->x * $d;
			$this->motionY += $vector->y * $d;
			$this->motionZ += $vector->z * $d;
		}
	}


	/**
	 *
	 */
	protected function checkChunks() {
		if ($this->chunk === null or ($this->chunk->getX() !== ($this->x >> 4) or $this->chunk->getZ() !== ($this->z >> 4))) {
			if ($this->chunk !== null) {
				$this->chunk->removeEntity($this);
			}
			$this->chunk = $this->level->getChunk($this->x >> 4, $this->z >> 4, true);
			if (!$this->justCreated) {
				$newChunk = $this->level->getChunkPlayers($this->x >> 4, $this->z >> 4);
				foreach ($this->hasSpawned as $player) {
					if (!isset($newChunk[$player->getLoaderId()])) {
						$this->despawnFrom($player);
					}else {
						unset($newChunk[$player->getLoaderId()]);
					}
				}
				foreach ($newChunk as $player) {
					$this->spawnTo($player);
				}
			}
			if ($this->chunk === null) {
				return;
			}
			$this->chunk->addEntity($this);
		}
	}


	/**
	 *
	 * @param unknown $movX
	 * @param unknown $movY
	 * @param unknown $movZ
	 * @param unknown $dx
	 * @param unknown $dy
	 * @param unknown $dz
	 */
	protected function checkGroundState($movX, $movY, $movZ, $dx, $dy, $dz) {
		$this->isCollidedVertically = $movY != $dy;
		$this->isCollidedHorizontally = ($movX != $dx or $movZ != $dz);
		$this->isCollided = ($this->isCollidedHorizontally or $this->isCollidedVertically);
		$this->onGround = ($movY != $dy and $movY < 0);
	}


	/**
	 *
	 * @param unknown $x
	 * @param unknown $y
	 * @param unknown $z
	 * @return unknown
	 */
	protected function checkObstruction($x, $y, $z) {
		$i = Math::floorFloat($x);
		$j = Math::floorFloat($y);
		$k = Math::floorFloat($z);
		$diffX = $x - $i;
		$diffY = $y - $j;
		$diffZ = $z - $k;
		if (Block::$solid[$this->level->getBlockIdAt($i, $j, $k)]) {
			$flag = !Block::$solid[$this->level->getBlockIdAt($i - 1, $j, $k)];
			$flag1 = !Block::$solid[$this->level->getBlockIdAt($i + 1, $j, $k)];
			$flag2 = !Block::$solid[$this->level->getBlockIdAt($i, $j - 1, $k)];
			$flag3 = !Block::$solid[$this->level->getBlockIdAt($i, $j + 1, $k)];
			$flag4 = !Block::$solid[$this->level->getBlockIdAt($i, $j, $k - 1)];
			$flag5 = !Block::$solid[$this->level->getBlockIdAt($i, $j, $k + 1)];
			$direction = -1;
			$limit = 9999;
			if ($flag) {
				$limit = $diffX;
				$direction = 0;
			}
			if ($flag1 and 1 - $diffX < $limit) {
				$limit = 1 - $diffX;
				$direction = 1;
			}
			if ($flag2 and $diffY < $limit) {
				$limit = $diffY;
				$direction = 2;
			}
			if ($flag3 and 1 - $diffY < $limit) {
				$limit = 1 - $diffY;
				$direction = 3;
			}
			if ($flag4 and $diffZ < $limit) {
				$limit = $diffZ;
				$direction = 4;
			}
			if ($flag5 and 1 - $diffZ < $limit) {
				$direction = 5;
			}
			$force = lcg_value() * 0.2 + 0.1;
			if ($direction === 0) {
				$this->motionX = -$force;
				return true;
			}
			if ($direction === 1) {
				$this->motionX = $force;
				return true;
			}
			if ($direction === 2) {
				$this->motionY = -$force;
				return true;
			}
			if ($direction === 3) {
				$this->motionY = $force;
				return true;
			}
			if ($direction === 4) {
				$this->motionZ = -$force;
				return true;
			}
			if ($direction === 5) {
				$this->motionY = $force;
				return true;
			}
		}

		return false;
	}


	/**
	 *
	 */
	public function close() {
		if (!$this->closed) {
			$this->server->getPluginManager()->callEvent(new EntityDespawnEvent($this));
			$this->closed = true;
			$this->despawnFromAll();
			if ($this->chunk !== null) {
				$this->chunk->removeEntity($this);
			}
			if ($this->level !== null) {
				$this->level->removeEntity($this);
			}
		}
	}


	/**
	 *
	 * @param int|string  $type
	 * @param FullChunk   $chunk
	 * @param CompoundTag $nbt
	 * @param unknown     $args
	 * @return Entity
	 */
	public static function createEntity($type, FullChunk $chunk, CompoundTag $nbt, ...$args) {
		if (isset(self::$knownEntities[$type])) {
			$class = self::$knownEntities[$type];
			return new $class($chunk, $nbt, ...$args);
		}
		return null;
	}


	/**
	 *
	 * @param Player  $player
	 */
	public function despawnFrom(Player $player) {
		if (isset($this->hasSpawned[$player->getLoaderId()])) {
			$pk = new RemoveEntityPacket();
			$pk->eid = $this->getId();
			$player->dataPacket($pk);
			unset($this->hasSpawned[$player->getLoaderId()]);
		}
	}


	/**
	 *
	 */
	public function despawnFromAll() {
		foreach ($this->hasSpawned as $player) {
			$this->despawnFrom($player);
		}
	}


	/**
	 *
	 * @param unknown $tickDiff (optional)
	 * @return unknown
	 */
	public function entityBaseTick($tickDiff = 1) {
		Timings::$timerEntityBaseTick->startTiming();
		//TODO: check vehicles
		$this->blocksAround = null;
		$this->justCreated = false;
		if (!$this->isAlive()) {
			$this->removeAllEffects();
			$this->despawnFromAll();
			if (!$this->isPlayer) {
				$this->close();
			}
			Timings::$timerEntityBaseTick->stopTiming();
			return false;
		}
		if (count($this->effects) > 0) {
			foreach ($this->effects as $effect) {
				if ($effect->canTick()) {
					$effect->applyEffect($this);
				}
				$effect->setDuration($effect->getDuration() - $tickDiff);
				if ($effect->getDuration() <= 0) {
					$this->removeEffect($effect->getId());
				}
			}
		}
		$hasUpdate = false;
		$this->checkBlockCollision();

		if ($this->y <= -16 and $this->isAlive()) {
			$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_VOID, 10);
			$this->attack($ev->getFinalDamage(), $ev);
			$hasUpdate = true;
		}

		if ($this->fireTicks > 0) {
			if ($this->fireProof) {
				$this->fireTicks -= 4 * $tickDiff;
				if ($this->fireTicks < 0) {
					$this->fireTicks = 0;
				}
			}else {
				if (!$this->hasEffect(Effect::FIRE_RESISTANCE) and ($this->fireTicks % 20) === 0 or $tickDiff > 20) {
					$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_FIRE_TICK, 1);
					$this->attack($ev->getFinalDamage(), $ev);
				}
				$this->fireTicks -= $tickDiff;
			}
			if ($this->fireTicks <= 0 && $this->fireTicks > -10) {
				$this->extinguish();
			}else {
				$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_ONFIRE, true);
				$hasUpdate = true;
			}
		}
		if ($this->noDamageTicks > 0) {
			$this->noDamageTicks -= $tickDiff;
			if ($this->noDamageTicks < 0) {
				$this->noDamageTicks = 0;
			}
		}
		$this->age += $tickDiff;
		$this->ticksLived += $tickDiff;
		Timings::$timerEntityBaseTick->stopTiming();
		return $hasUpdate;
	}


	/**
	 *
	 */
	public function extinguish() {
		$this->fireTicks = 0;
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_ONFIRE, false);
	}


	/**
	 *
	 * @param unknown $fallDistance
	 */
	public function fall($fallDistance) {
		if ($this->isInsideOfWater()) return;
		$damage = floor($fallDistance - 3 - ($this->hasEffect(Effect::JUMP) ? $this->getEffect(Effect::JUMP)->getAmplifier() + 1 : 0));
		if ($damage > 0) {
			$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_FALL, $damage);
			$this->attack($ev->getFinalDamage(), $ev);
		}
	}


	/**
	 *
	 * @param unknown $dx
	 * @param unknown $dy
	 * @param unknown $dz
	 * @return unknown
	 */
	public function fastMove($dx, $dy, $dz) {

		return Movement::moveFast($this, $dx, $dy, $dz);

		if ($dx == 0 and $dz == 0 and $dy == 0) {
			return true;
		}
		Timings::$entityMoveTimer->startTiming();
		// $newBB = $this->boundingBox->getOffsetBoundingBox($dx, $dy, $dz);
		// $list = $this->level->getCollisionCubes($this, $newBB, false);
		// if(count($list) === 0){
		// $this->boundingBox = $newBB;
		// }
		$this->x = ($this->boundingBox->minX + $this->boundingBox->maxX) / 2;
		$this->y = $this->boundingBox->minY - $this->ySize;
		$this->z = ($this->boundingBox->minZ + $this->boundingBox->maxZ) / 2;
		$this->checkChunks();
		if (!$this->onGround or $dy != 0) {
			$bb = clone $this->boundingBox;
			$bb->minY -= 0.75;
			$this->onGround = false;
			if (!$this->level->getBlock(new Vector3($this->x, $this->y - 1, $this->z))->isTransparent())
				$this->onGround = \true;
			// if(count($this->level->getCollisionBlocks($bb)) > 0){
			// $this->onGround = true;
			// }
		}
		$this->isCollided = $this->onGround;
		$this->updateFallState($dy, $this->onGround);
		Timings::$entityMoveTimer->stopTiming();
		return true;
	}


	/**
	 *
	 * @param Entity  $entity
	 */
	public function followEntity(Entity $entity) {
		$this->setPosition($entity->temporalVector->setComponents($entity->x, $entity->y - 0.5, $entity->z));
	}


	/**
	 *
	 * @return unknown
	 */
	public function getBlocksAround() {
		if ($this->blocksAround === null) {
			$minX = Math::floorFloat($this->boundingBox->minX);
			$minY = Math::floorFloat($this->boundingBox->minY);
			$minZ = Math::floorFloat($this->boundingBox->minZ);
			$maxX = Math::ceilFloat($this->boundingBox->maxX);
			$maxY = Math::ceilFloat($this->boundingBox->maxY);
			$maxZ = Math::ceilFloat($this->boundingBox->maxZ);
			$this->blocksAround = [];
			for ($z = $minZ; $z <= $maxZ; ++$z) {
				for ($x = $minX; $x <= $maxX; ++$x) {
					for ($y = $minY; $y <= $maxY; ++$y) {
						$block = $this->level->getBlock($this->temporalVector->setComponents($x, $y, $z));
						if ($block->hasEntityCollision()) {
							$this->blocksAround[] = $block;
						}
					}
				}
			}
		}

		return $this->blocksAround;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getBoundingBox() {
		return $this->boundingBox;
	}


	/**
	 *
	 * @param int     $propertyId
	 * @param int     $id
	 * @return bool
	 */
	public function getDataFlag($propertyId, $id) {
		return (((int) $this->getDataProperty($propertyId)) & (1 << $id)) > 0;
	}


	/**
	 *
	 * @param int     $id
	 * @return mixed
	 */
	public function getDataProperty($id) {
		return isset($this->dataProperties[$id]) ? $this->dataProperties[$id][1] : null;
	}


	/**
	 *
	 * @param int     $id
	 * @return int
	 */
	public function getDataPropertyType($id) {
		return isset($this->dataProperties[$id]) ? $this->dataProperties[$id][0] : null;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getDirection() {
		$rotation = ($this->yaw - 90) % 360;
		if ($rotation < 0) {
			$rotation += 360.0;
		}
		if ((0 <= $rotation and $rotation < 45) or (315 <= $rotation and $rotation < 360)) {
			return 2; //North
		}elseif (45 <= $rotation and $rotation < 135) {
			return 3; //East
		}elseif (135 <= $rotation and $rotation < 225) {
			return 0; //South
		}elseif (225 <= $rotation and $rotation < 315) {
			return 1; //West
		}else {
			return null;
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getDirectionPlane() {
		return (new Vector2(-cos(deg2rad($this->yaw) - M_PI_2), -sin(deg2rad($this->yaw) - M_PI_2)))->normalize();
	}


	/**
	 *
	 * @return Vector3
	 */
	public function getDirectionVector() {
		$y = -sin(deg2rad($this->pitch));
		$xz = cos(deg2rad($this->pitch));
		$x = -$xz * sin(deg2rad($this->yaw));
		$z = $xz * cos(deg2rad($this->yaw));
		return $this->temporalVector->setComponents($x, $y, $z)->normalize();
	}


	/**
	 *
	 * @param unknown $effectId
	 * @return unknown
	 */
	public function getEffect($effectId) {
		return isset($this->effects[$effectId]) ? $this->effects[$effectId] : null;
	}


	/**
	 *
	 * @return Effect[]
	 */
	public function getEffects() {
		return $this->effects;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getEyeHeight() {
		return $this->eyeHeight;
	}


	/**
	 *
	 * @return int
	 */
	public function getHealth() {
		return $this->health;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 *
	 * @return EntityDamageEvent|null
	 */
	public function getLastDamageCause() {
		return $this->lastDamageCause;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getLinkedEntity() {
		return $this->linkedEntity;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getlinkedTarget() {
		return $this->linkedTarget;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getLinkedType() {
		return $this->linkedType;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getlinkTarget() {
		return $this->linkedTarget;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getlinkType() {
		if (!$this->islinked) {
			return Entity::LINK_EMPTY;
		}else {
			if ($this->linkedTarget !== null) {
				return Entity::LINK_MASTER;
			}else {
				return Entity::LINK_SLAVE;
			}
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getLocation() : Location{
		return new Location($this->x, $this->y, $this->z, $this->yaw, $this->pitch, $this->level);
	}


	/**
	 *
	 * @return int
	 */
	public function getMaxHealth() {
		return $this->maxHealth + ($this->hasEffect(Effect::HEALTH_BOOST) ? 4 * ($this->getEffect(Effect::HEALTH_BOOST)->getAmplifier() + 1) : 0);
	}


	/**
	 *
	 * @param unknown $metadataKey
	 * @return unknown
	 */
	public function getMetadata($metadataKey) {
		return $this->server->getEntityMetadata()->getMetadata($this, $metadataKey);
	}


	/**
	 *
	 * @return unknown
	 */
	public function getMotion() : Vector3{
		return new Vector3($this->motionX, $this->motionY, $this->motionZ);
	}


	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->getNameTag();
	}


	/**
	 *
	 * @return string
	 */
	public function getNameTag() {
		return $this->getDataProperty(self::DATA_NAMETAG);
	}


	/**
	 *
	 * @return unknown
	 */
	public function getPosition() : Position{
		return new Position($this->x, $this->y, $this->z, $this->level);
	}


	/**
	 * Returns the short save name
	 *
	 * @return string
	 */
	public function getSaveId() {
		return self::$shortNames[static::class];
	}


	/**
	 *
	 * @return unknown
	 */
	public function getStepHeight() {
		return $this->stepHeight;
	}


	/**
	 *
	 * @return Player[]
	 */
	public function getViewers() {
		return $this->hasSpawned;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getYsize() {
		return $this->ySize;
	}


	/**
	 *
	 */
	public function handleLavaMovement() { //TODO
	}


	/**
	 *
	 * @param unknown $effectId
	 * @return unknown
	 */
	public function hasEffect($effectId) {
		return isset($this->effects[$effectId]);
	}


	/**
	 *
	 * @param unknown $metadataKey
	 * @return unknown
	 */
	public function hasMetadata($metadataKey) {
		return $this->server->getEntityMetadata()->hasMetadata($this, $metadataKey);
	}


	/**
	 *
	 * @param float                   $amount
	 * @param EntityRegainHealthEvent $source
	 */
	public function heal($amount, EntityRegainHealthEvent $source) {
		$this->server->getPluginManager()->callEvent($source);
		if ($source->isCancelled()) {
			return;
		}
		$this->setHealth($this->getHealth() + $source->getAmount());
	}


	/**
	 *
	 */
	protected function initEntity() {
		assert($this->namedtag instanceof CompoundTag);
		if (isset($this->namedtag->ActiveEffects)) {
			foreach ($this->namedtag->ActiveEffects->getValue() as $e) {
				$effect = Effect::getEffect($e["Id"]);
				if ($effect === null) {
					continue;
				}
				$effect->setAmplifier($e["Amplifier"])->setDuration($e["Duration"])->setVisible($e["ShowParticles"] > 0);
				$this->addEffect($effect);
			}
		}
		if (isset($this->namedtag->CustomName)) {
			$this->setNameTag($this->namedtag["CustomName"]);
			if (isset($this->namedtag->CustomNameVisible)) {
				$this->setNameTagVisible($this->namedtag["CustomNameVisible"] > 0);
			}
		}
		$this->scheduleUpdate();
	}


	/**
	 *
	 * @return unknown
	 */
	public function isAlive() {
		return $this->health > 0;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isInsideOfPortal() {
		$blocks = $this->getBlocksAround();
		foreach ($blocks as $block) {
			if ($block instanceof Portal) return true;
		}
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isInsideOfSolid() {
		$block = $this->level->getBlock($this->temporalVector->setComponents(Math::floorFloat($this->x), Math::floorFloat($y = ($this->y + $this->getEyeHeight())), Math::floorFloat($this->z)));
		$bb = $block->getBoundingBox();
		if ($bb !== null and $block->isSolid() and !$block->isTransparent() and $bb->intersectsWith($this->getBoundingBox())) {
			return true;
		}
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isInsideOfWater() {
		$block = $this->level->getBlock($this->temporalVector->setComponents(Math::floorFloat($this->x), Math::floorFloat($y = ($this->y + $this->getEyeHeight())), Math::floorFloat($this->z)));
		if ($block instanceof Water) {
			$f = ($block->y + 1) - ($block->getFluidHeightPercent() - 0.1111111);
			return $y < $f;
		}
		return false;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isLinked() {
		return $this->isLinked;
	}


	/**
	 *
	 * @return bool
	 */
	public function isNameTagVisible() {
		return $this->getDataProperty(self::DATA_SHOW_NAMETAG) > 0;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isOnFire() {
		return $this->fireTicks > 0;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isOnGround() {
		return $this->onGround === true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function isSneaking() {
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SNEAKING);
	}


	/**
	 *
	 * @return unknown
	 */
	public function isSprinting() {
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SPRINTING);
	}


	/**
	 *
	 * @return unknown
	 */
	public function isVehicle() {
		return false;
	}


	/**
	 *
	 */
	public function kill() {
		$this->health = 0;
		$this->scheduleUpdate();
	}


	/**
	 *
	 * @param Entity  $entity
	 * @return unknown
	 */
	public function linkEntity(Entity $entity) {
		return $this->setLinked(1, $entity);
	}


	/**
	 *
	 * @param unknown $dx
	 * @param unknown $dy
	 * @param unknown $dz
	 * @return unknown
	 */
	public function move($dx, $dy, $dz) {
		if ($dx == 0 and $dz == 0 and $dy == 0) {
			return true;
		}
		return Movement::move($this, $dx, $dy, $dz);
	}


	/**
	 *
	 */
	public function moveCheckChunks() {
		$this->checkChunks();
	}


	/**
	 *
	 * @param unknown $movX
	 * @param unknown $movY
	 * @param unknown $movZ
	 * @param unknown $dx
	 * @param unknown $dy
	 * @param unknown $dz
	 */
	public function moveCheckGroundState($movX, $movY, $movZ, $dx, $dy, $dz) {
		$this->checkGroundState($movX, $movY, $movZ, $dx, $dy, $dz);
	}


	/**
	 *
	 * @param unknown $distanceThisTick
	 * @param unknown $onGround
	 */
	public function moveUpdateFallState($distanceThisTick, $onGround) {
		$this->updateFallState($distanceThisTick, $onGround);
	}


	/**
	 *
	 */
	public function moveFlying() { //TODO
	}


	/**
	 *
	 * @param Human   $entityPlayer
	 */
	public function onCollideWithPlayer(Human $entityPlayer) {
	}


	/**
	 * onPlayerAction
	 * use this method in entity classes to handle specific logic
	 * this method is added to remove entity based logic from Player class
	 * can be called in Player for individual logic required for an entity
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
	 * @param unknown $currentTick
	 * @return unknown
	 */
	public function onUpdate($currentTick) {
		if ($this->closed) {
			return false;
		}
		if (!$this->isAlive()) {
			++$this->deadTicks;
			if ($this->deadTicks >= 10) {
				$this->despawnFromAll();
				if (!$this->isPlayer) {
					$this->close();
				}
			}
			return $this->deadTicks < 10;
		}
		$tickDiff = $currentTick - $this->lastUpdate;
		if ($tickDiff <= 0) {
			return false;
		}
		$this->lastUpdate = $currentTick;
		$this->timings->startTiming();
		$hasUpdate = $this->entityBaseTick($tickDiff);
		$this->updateMovement();
		$this->timings->stopTiming();
		return $hasUpdate;
	}


	/**
	 *
	 */
	protected function recalculateEffectColor() {
		$color = [0, 0, 0]; //RGB
		$count = 0;
		$ambient = true;
		foreach ($this->effects as $effect) {
			if ($effect->isVisible()) {
				$c = $effect->getColor();
				$color[0] += $c[0] * ($effect->getAmplifier() + 1);
				$color[1] += $c[1] * ($effect->getAmplifier() + 1);
				$color[2] += $c[2] * ($effect->getAmplifier() + 1);
				$count += $effect->getAmplifier() + 1;
				if (!$effect->isAmbient()) {
					$ambient = false;
				}
			}
		}
		if ($count > 0) {
			$r = ($color[0] / $count) & 0xff;
			$g = ($color[1] / $count) & 0xff;
			$b = ($color[2] / $count) & 0xff;
			$this->setDataProperty(Entity::DATA_POTION_COLOR, Entity::DATA_TYPE_INT, ($r << 16) + ($g << 8) + $b);
			$this->setDataProperty(Entity::DATA_POTION_AMBIENT, Entity::DATA_TYPE_BYTE, $ambient ? 1 : 0);
		}else {
			$this->setDataProperty(Entity::DATA_POTION_COLOR, Entity::DATA_TYPE_INT, 0);
			$this->setDataProperty(Entity::DATA_POTION_AMBIENT, Entity::DATA_TYPE_BYTE, 0);
		}
	}


	/**
	 *
	 * @param unknown $className
	 * @param unknown $force     (optional)
	 * @return unknown
	 */
	public static function registerEntity($className, $force = false) {
		$class = new \ReflectionClass($className);
		if (is_a($className, Entity::class, true) and !$class->isAbstract()) {
			if ($className::NETWORK_ID !== -1) {
				self::$knownEntities[$className::NETWORK_ID] = $className;
			}elseif (!$force) {
				return false;
			}
			self::$knownEntities[$class->getShortName()] = $className;
			self::$shortNames[$className] = $class->getShortName();
			return true;
		}


		return false;
	}


	/**
	 *
	 */
	public function removeAllEffects() {
		foreach ($this->effects as $effect) {
			$this->removeEffect($effect->getId());
		}
	}


	/**
	 *
	 * @param unknown $effectId
	 */
	public function removeEffect($effectId) {
		if (isset($this->effects[$effectId])) {
			$effect = $this->effects[$effectId];
			unset($this->effects[$effectId]);
			$effect->remove($this);
			$this->recalculateEffectColor();
		}
	}


	/**
	 *
	 * @param unknown $metadataKey
	 * @param Plugin  $plugin
	 */
	public function removeMetadata($metadataKey, Plugin $plugin) {
		$this->server->getEntityMetadata()->removeMetadata($this, $metadataKey, $plugin);
	}


	/**
	 *
	 */
	public function resetFallDistance() {
		$this->fallDistance = 0;
	}


	/**
	 *
	 */
	public function respawnToAll() {
		foreach ($this->hasSpawned as $key => $player) {
			unset($this->hasSpawned[$key]);
			$this->spawnTo($player);
		}
	}


	/**
	 *
	 */
	public function saveNBT() {
		if (!($this instanceof Player)) {
			$this->namedtag->id = new StringTag("id", $this->getSaveId());
			if ($this->getNameTag() !== "") {
				$this->namedtag->CustomName = new StringTag("CustomName", $this->getNameTag());
				$this->namedtag->CustomNameVisible = new StringTag("CustomNameVisible", $this->isNameTagVisible());
			}else {
				unset($this->namedtag->CustomName);
				unset($this->namedtag->CustomNameVisible);
			}
		}
		$this->namedtag->Pos = new ListTag("Pos", [
				new DoubleTag(0, $this->x),
				new DoubleTag(1, $this->y),
				new DoubleTag(2, $this->z)
			]);
		$this->namedtag->Motion = new ListTag("Motion", [
				new DoubleTag(0, $this->motionX),
				new DoubleTag(1, $this->motionY),
				new DoubleTag(2, $this->motionZ)
			]);
		$this->namedtag->Rotation = new ListTag("Rotation", [
				new FloatTag(0, $this->yaw),
				new FloatTag(1, $this->pitch)
			]);
		$this->namedtag->FallDistance = new FloatTag("FallDistance", $this->fallDistance);
		$this->namedtag->Fire = new ShortTag("Fire", $this->fireTicks);
		$this->namedtag->Air = new ShortTag("Air", $this->getDataProperty(self::DATA_AIR));
		$this->namedtag->OnGround = new ByteTag("OnGround", $this->onGround == true ? 1 : 0);
		$this->namedtag->Invulnerable = new ByteTag("Invulnerable", $this->invulnerable == true ? 1 : 0);
		if (count($this->effects) > 0) {
			$effects = [];
			foreach ($this->effects as $effect) {
				$effects[$effect->getId()] = new CompoundTag($effect->getId(), [
						"Id" => new ByteTag("Id", $effect->getId()),
						"Amplifier" => new ByteTag("Amplifier", $effect->getAmplifier()),
						"Duration" => new IntTag("Duration", $effect->getDuration()),
						"Ambient" => new ByteTag("Ambient", 0),
						"ShowParticles" => new ByteTag("ShowParticles", $effect->isVisible() ? 1 : 0)
					]);
			}
			$this->namedtag->ActiveEffects = new ListTag("ActiveEffects", $effects);
		}else {
			unset($this->namedtag->ActiveEffects);
		}
	}


	/**
	 *
	 */
	public final function scheduleUpdate() {
		$this->level->updateEntities[$this->id] = $this;
	}


	/**
	 *
	 * @param Player[]|Player $player
	 * @param array           $data   (optional) Properly formatted entity data, defaults to everything
	 */
	public function sendData($player, array $data = null) {
		$pk = new SetEntityDataPacket();
		$pk->eid = ($player === $this ? 0 : $this->getId());
		$pk->metadata = $data === null ? $this->dataProperties : $data;

		if (!is_array($player)) {
			$player->dataPacket($pk);
		}else {
			Server::broadcastPacket($player, $pk);
		}
	}


	/**
	 *
	 * @deprecated
	 * @param unknown $player
	 */
	public function sendMetadata($player) {
		$this->sendData($player);
	}


	/**
	 *
	 * @param Player  $player
	 */
	public function sendPotionEffects(Player $player) {
		foreach ($this->effects as $effect) {
			$pk = new MobEffectPacket();
			$pk->eid = 0;
			$pk->effectId = $effect->getId();
			$pk->amplifier = $effect->getAmplifier();
			$pk->particles = $effect->isVisible();
			$pk->duration = $effect->getDuration();
			$pk->eventId = MobEffectPacket::EVENT_ADD;
			$player->dataPacket($pk);
		}
	}


	/**
	 *
	 * @param int     $propertyId ;
	 * @param int     $id
	 * @param bool    $value      (optional)
	 * @param unknown $type       (optional)
	 */
	public function setDataFlag($propertyId, $id, $value = true, $type = self::DATA_TYPE_BYTE) {
		if ($this->getDataFlag($propertyId, $id) !== $value) {
			$flags = (int) $this->getDataProperty($propertyId);
			$flags ^= 1 << $id;
			$this->setDataProperty($propertyId, $type, $flags);
		}
	}


	/**
	 *
	 * @param int     $id
	 * @param int     $type
	 * @param mixed   $value
	 * @return bool
	 */
	public function setDataProperty($id, $type, $value) {
		if ($this->getDataProperty($id) !== $value) {
			$this->dataProperties[$id] = [$type, $value];
			$this->sendData($this->hasSpawned, [$id => $this->dataProperties[$id]]);
			return true;
		}
		return false;
	}


	/**
	 * Sets the health of the Entity. This won't send any update to the players
	 *
	 * @param int     $amount
	 */
	public function setHealth($amount) {
		$amount = (int) $amount;
		if ($amount === $this->health) {
			return;
		}
		if ($amount <= 0) {
			if ($this->isAlive()) {
				$this->kill();
			}
		}elseif ($amount <= $this->getMaxHealth() or $amount < $this->health) {
			$this->health = (int) $amount;
		}else {
			$this->health = $this->getMaxHealth();
		}
	}


	/**
	 *
	 * @param EntityDamageEvent $type
	 */
	public function setLastDamageCause(EntityDamageEvent $type) {
		$this->lastDamageCause = $type;
	}


	/**
	 *
	 * @param unknown $type   (optional)
	 * @param Entity  $entity (optional)
	 * @return unknown
	 */
	public function setLinked($type = 0, Entity $entity = null) {
		if ($type != 0 and $entity === null) {
			return false;
		}
		if ($entity === $this) {
			return false;
		}
		switch ($type) {
		case 0:
			if ($this->linkedType == 0) {
				return true;
			}
			$this->linkedType = 0;
			$pk = new SetEntityLinkPacket();
			$pk->from = $entity->getId();
			$pk->to = $this->getId();
			$pk->type = 3;
			$this->server->broadcastPacket($this->level->getPlayers(), $pk);
			if ($this instanceof Player) {
				$pk = new SetEntityLinkPacket();
				$pk->from = $entity->getId();
				$pk->to = 0;
				$pk->type = 3;
				$this->dataPacket($pk);
			}
			if ($this->linkedEntity->getLinkedType()) {
				$this->linkedEntity->setLinked(0, $this);
			}
			return true;
		case 1:
			if (!$entity->isAlive()) {
				return false;
			}
			$this->linkedEntity = $entity;
			$this->linkedType = 1;
			$pk = new SetEntityLinkPacket();
			$pk->from = $entity->getId();
			$pk->to = $this->getId();
			$pk->type = 2;
			$this->server->broadcastPacket($this->level->getPlayers(), $pk);
			if ($this instanceof Player) {
				$pk = new SetEntityLinkPacket();
				$pk->from = $entity->getId();
				$pk->to = 0;
				$pk->type = 2;
				$this->dataPacket($pk);
			}
			return true;
		case 2:
			if (!$entity->isAlive()) {
				return false;
			}
			if ($entity->getLinkedEntity() !== $this) {
				return $entity->linkEntity($this);
			}
			$this->linkedEntity = $entity;
			$this->linkedType = 2;
			return true;
		default:
			return false;
		}
	}


	/**
	 *
	 * @param Entity  $target
	 */
	public function setlinkTarget(Entity $target) {
		$this->linkedTarget = $target;
	}


	/**
	 *
	 * @param int     $amount
	 */
	public function setMaxHealth($amount) {
		$this->maxHealth = (int) $amount;
	}


	/**
	 *
	 * @param unknown       $metadataKey
	 * @param MetadataValue $metadataValue
	 */
	public function setMetadata($metadataKey, MetadataValue $metadataValue) {
		$this->server->getEntityMetadata()->setMetadata($this, $metadataKey, $metadataValue);
	}


	/**
	 *
	 * @param Vector3 $motion
	 * @return unknown
	 */
	public function setMotion(Vector3 $motion) {
		if (!$this->justCreated) {
			$this->server->getPluginManager()->callEvent($ev = new EntityMotionEvent($this, $motion));
			if ($ev->isCancelled()) {
				return false;
			}
		}
		$this->motionX = $motion->x;
		$this->motionY = $motion->y;
		$this->motionZ = $motion->z;
		if (!$this->justCreated) {
			$this->updateMovement();
		}
		return true;
	}


	/**
	 *
	 * @param string  $name
	 */
	public function setNameTag($name) {
		$this->setDataProperty(self::DATA_NAMETAG, self::DATA_TYPE_STRING, $name);
	}


	/**
	 *
	 * @param bool    $value (optional)
	 */
	public function setNameTagVisible($value = true) {
		$this->setDataProperty(self::DATA_SHOW_NAMETAG, self::DATA_TYPE_BYTE, $value ? 1 : 0);
	}


	/**
	 *
	 * @param unknown $seconds
	 */
	public function setOnFire($seconds) {
		$ticks = $seconds * 20;
		if ($ticks > $this->fireTicks) {
			$this->fireTicks = $ticks;
		}
	}


	/**
	 *
	 * @param Vector3 $pos
	 * @return unknown
	 */
	public function setPosition(Vector3 $pos) {
		if ($this->closed) {
			return false;
		}
		if ($pos instanceof Position and $pos->level !== null and $pos->level !== $this->level) {
			if ($this->switchLevel($pos->getLevel()) === false) {
				return false;
			}
		}
		$this->x = $pos->x;
		$this->y = $pos->y;
		$this->z = $pos->z;
		$radius = $this->width / 2;
		$this->boundingBox->setBounds($pos->x - $radius, $pos->y, $pos->z - $radius, $pos->x + $radius, $pos->y + $this->height, $pos->z + $radius);
		$this->checkChunks();
		return true;
	}


	/**
	 *
	 * @param Vector3 $pos
	 * @param unknown $yaw
	 * @param unknown $pitch
	 * @return unknown
	 */
	public function setPositionAndRotation(Vector3 $pos, $yaw, $pitch) {
		if ($this->setPosition($pos) === true) {
			$this->setRotation($yaw, $pitch);
			return true;
		}
		return false;
	}


	/**
	 *
	 * @param unknown $yaw
	 * @param unknown $pitch
	 */
	public function setRotation($yaw, $pitch) {
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		$this->scheduleUpdate();
	}


	/**
	 *
	 * @param unknown $value (optional)
	 */
	public function setSneaking($value = true) {
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SNEAKING, (bool) $value);
	}


	/**
	 *
	 * @param unknown $value (optional)
	 */
	public function setSprinting($value = true) {
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SPRINTING, (bool) $value);
	}


	/**
	 *
	 * @param unknown $ySize
	 */
	public function setYsize($ySize) {
		$this->ySize = $ySize;
	}


	/**
	 *
	 * @param Player  $player
	 */
	public function spawnTo(Player $player) {
		if (!isset($this->hasSpawned[$player->getLoaderId()]) and isset($player->usedChunks[Level::chunkHash($this->chunk->getX(), $this->chunk->getZ())])) {
			$this->hasSpawned[$player->getLoaderId()] = $player;
		}
	}


	/**
	 *
	 */
	public function spawnToAll() {
		if ($this->chunk === null or $this->closed) {
			return;
		}
		foreach ($this->level->getChunkPlayers($this->chunk->getX(), $this->chunk->getZ()) as $player) {
			if ($player->isOnline()) {
				$this->spawnTo($player);
			}
		}
	}


	/**
	 *
	 * @param Level   $targetLevel
	 * @return unknown
	 */
	protected function switchLevel(Level $targetLevel) {
		if ($this->closed) {
			return false;
		}
		if ($this->isValid()) {
			$this->server->getPluginManager()->callEvent($ev = new EntityLevelChangeEvent($this, $this->level, $targetLevel));
			if ($ev->isCancelled()) {
				return false;
			}
			$this->level->removeEntity($this);
			if ($this->chunk !== null) {
				$this->chunk->removeEntity($this);
			}
			$this->despawnFromAll();
		}
		$this->setLevel($targetLevel);
		$this->level->addEntity($this);
		$this->chunk = null;
		return true;
	}


	/**
	 *
	 * @param Vector3|Position|Location $pos
	 * @param float                     $yaw   (optional)
	 * @param float                     $pitch (optional)
	 * @return bool
	 */
	public function teleport(Vector3 $pos, $yaw = null, $pitch = null): bool{
		if ($pos instanceof Location) {
			$yaw = $yaw ?? $pos->yaw;
			$pitch = $pitch ?? $pos->pitch;
		}
		$from = Position::fromObject($this, $this->level);
		$to = Position::fromObject($pos, $pos instanceof Position ? $pos->getLevel() : $this->level);
		$this->server->getPluginManager()->callEvent($ev = new EntityTeleportEvent($this, $from, $to));
		if ($ev->isCancelled()) {
			return false;
		}
		$this->ySize = 0;
		$pos = $ev->getTo();
		$this->setMotion($this->temporalVector->setComponents(0, 0, 0));
		if ($this->setPositionAndRotation($pos, $yaw ?? $this->yaw, $pitch ?? $this->pitch) !== false) {
			$this->resetFallDistance();
			$this->onGround = true;
			$this->lastX = $this->x;
			$this->lastY = $this->y;
			$this->lastZ = $this->z;
			$this->lastYaw = $this->yaw;
			$this->lastPitch = $this->pitch;
			$this->updateMovement();
			return true;
		}
		return false;
	}


	/**
	 *
	 * @param Entity  $entity
	 */
	public function unlinkEntity(Entity $entity) {
		if ($this->linkedTarget instanceof Entity) {
			$this->linkedTarget = null;
			$entity->islinked = false;
		}
		$pk = new SetEntityLinkPacket();
		$pk->from = $entity->getId();
		$pk->to = 0;
		$pk->type = 0;
		$this->dataPacket($pk);
		$this->islinked = false;
	}


	/**
	 *
	 * @param unknown $distanceThisTick
	 * @param unknown $onGround
	 */
	protected function updateFallState($distanceThisTick, $onGround) {
		if ($onGround === true) {
			if ($this->fallDistance > 0) {
				if ($this instanceof Living) {
					$this->fall($this->fallDistance);
				}
				$this->resetFallDistance();
			}
		}elseif ($distanceThisTick < 0) {
			$this->fallDistance -= $distanceThisTick;
		}
	}


	/**
	 *
	 */
	protected function updateMovement() {
		Movement::updateMovement($this);
	}


}
