<?php
/**
 * src/pocketmine/block/NetherPortal.php
 *
 * @package default
 */


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityEnterPortalEvent;

class NetherPortal extends Flowable
{
    protected $id = self::NETHER_PORTAL;

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
    public function getLightLevel()
    {
        return 15;
    }


    /**
     *
     * @return unknown
     */
    public function getName()
    {
        return "Nether Portal";
    }


    /**
     *
     * @param Item    $item
     */
    public function getDrops(Item $item)
    {
        return;
    }


    /**
     *
     * @param Entity  $entity
     * @return unknown
     */
    public function onEntityCollide(Entity $entity)
    {
        Server::getInstance()->getPluginManager()->callEvent($ev = new EntityEnterPortalEvent($entity, $this));
        if (!$ev->isCancelled()) {
            return true;
        }
        return false;
    }


    /**
     *
     * @return unknown
     */
    public function canPassThrough()
    {
        return true;
    }


    /*
     * public function canBeReplaced(){
     * return true;
     * }
     */
    // TODO: only source blocks of liquids
}
