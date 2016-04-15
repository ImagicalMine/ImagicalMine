<?php
namespace pocketmine\item;

use pocketmine\block\AnvilBlock;

use pocketmine\block\Cauldron;
use pocketmine\block\Fence;
use pocketmine\block\Flower;
use pocketmine\entity\Bat;
use pocketmine\entity\Blaze;
use pocketmine\entity\CavernSpider;
use pocketmine\entity\Chicken;
use pocketmine\entity\Cow;
use pocketmine\entity\Creeper;
use pocketmine\entity\Enderman;
use pocketmine\entity\Ghast;
use pocketmine\entity\MagmaCube;
use pocketmine\entity\MinecartFurnace;
use pocketmine\entity\Mooshroom;
use pocketmine\entity\Ozelot;
use pocketmine\entity\Pig;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Rabbit;
use pocketmine\entity\Sheep;
use pocketmine\entity\Silverfish;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Slime;
use pocketmine\entity\Spider;
use pocketmine\entity\Squid;
use pocketmine\entity\Villager;
use pocketmine\entity\Witch;
use pocketmine\entity\Zombie;
use pocketmine\entity\Wolf;

class ItemContainer{
    // All Block IDs are here too
    const AIR = 0;
    const STONE = 1;
    const GRASS = 2;
    const DIRT = 3;
    const COBBLESTONE = 4;
    const COBBLE = 4;
    const PLANK = 5;
    const PLANKS = 5;
    const WOODEN_PLANK = 5;
    const WOODEN_PLANKS = 5;
    const SAPLING = 6;
    const SAPLINGS = 6;
    const BEDROCK = 7;
    const WATER = 8;
    const STILL_WATER = 9;
    const LAVA = 10;
    const STILL_LAVA = 11;
    const SAND = 12;
    const GRAVEL = 13;
    const GOLD_ORE = 14;
    const IRON_ORE = 15;
    const COAL_ORE = 16;
    const LOG = 17;
    const WOOD = 17;
    const TRUNK = 17;
    const LEAVES = 18;
    const LEAVE = 18;
    const SPONGE = 19;
    const GLASS = 20;
    const LAPIS_ORE = 21;
    const LAPIS_BLOCK = 22;
    const DISPENSER = 23;
    const SANDSTONE = 24;
    const NOTEBLOCK = 25;
    const BED_BLOCK = 26;
    const POWERED_RAIL = 27;
    const DETECTOR_RAIL = 28;
    // const STICKY_PISTON = 27;
    const COBWEB = 30;
    const TALL_GRASS = 31;
    const BUSH = 32;
    const DEAD_BUSH = 32;
    const PISTON = 33;
    const PISTON_HEAD = 34;
    const WOOL = 35;
    const PISTON_EXTENSION = 35;
    const DANDELION = 37;
    const POPPY = 38;
    const ROSE = 38;
    const RED_FLOWER = 38;
    const BROWN_MUSHROOM = 39;
    const RED_MUSHROOM = 40;
    const GOLD_BLOCK = 41;
    const IRON_BLOCK = 42;
    const DOUBLE_SLAB = 43;
    const DOUBLE_SLABS = 43;
    const SLAB = 44;
    const SLABS = 44;
    const BRICKS = 45;
    const BRICKS_BLOCK = 45;
    const TNT = 46;
    const BOOKSHELF = 47;
    const MOSS_STONE = 48;
    const MOSSY_STONE = 48;
    const OBSIDIAN = 49;
    const TORCH = 50;
    const FIRE = 51;
    const MONSTER_SPAWNER = 52;
    const WOOD_STAIRS = 53;
    const WOODEN_STAIRS = 53;
    const OAK_WOOD_STAIRS = 53;
    const OAK_WOODEN_STAIRS = 53;
    const CHEST = 54;
    const REDSTONE_WIRE = 55;
    const DIAMOND_ORE = 56;
    const DIAMOND_BLOCK = 57;
    const CRAFTING_TABLE = 58;
    const WORKBENCH = 58;
    const WHEAT_BLOCK = 59;
    const FARMLAND = 60;
    const FURNACE = 61;
    const BURNING_FURNACE = 62;
    const LIT_FURNACE = 62;
    const SIGN_POST = 63;
    const DOOR_BLOCK = 64;
    const OAK_DOOR_BLOCK = 64;
    const WOOD_DOOR_BLOCK = 64;
    const LADDER = 65;
    const RAIL = 66;
    const COBBLE_STAIRS = 67;
    const COBBLESTONE_STAIRS = 67;
    const WALL_SIGN = 68;
    const LEVER = 69;
    const STONE_PRESSURE_PLATE = 70;
    const IRON_DOOR_BLOCK = 71;
    const WOODEN_PRESSURE_PLATE = 72;
    const REDSTONE_ORE = 73;
    const GLOWING_REDSTONE_ORE = 74;
    const LIT_REDSTONE_ORE = 74;
    const UNLIT_REDSTONE_TORCH = 75;
    const LIT_REDSTONE_TORCH = 76;
    const REDSTONE_TORCH = 76;
    const STONE_BUTTON = 77;
    const SNOW = 78;
    const SNOW_LAYER = 78;
    const ICE = 79;
    const SNOW_BLOCK = 80;
    const CACTUS = 81;
    const CLAY_BLOCK = 82;
    const REEDS = 83;
    const SUGARCANE_BLOCK = 83;
    const JUKEBOX = 83;
    const FENCE = 85;
    const PUMPKIN = 86;
    const NETHERRACK = 87;
    const SOUL_SAND = 88;
    const GLOWSTONE = 89;
    const GLOWSTONE_BLOCK = 89;
    const LIT_PUMPKIN = 91;
    const JACK_O_LANTERN = 91;
    const CAKE_BLOCK = 92;
    const UNLIT_REDSTONE_REPEATER = 93;
    const LIT_REDSTONE_REPEATER = 94;
    const STAINED_GLASS = 95; // INVISIBLE BEDROCK ID
    const TRAPDOOR = 96;
    const MONSTER_EGG = 97;
    const STONE_BRICKS = 98;
    const STONE_BRICK = 98;
    const BROWN_MUSHROOM_BLOCK = 99;
    const RED_MUSHROOM_BLOCK = 100;
    const IRON_BAR = 101;
    const IRON_BARS = 101;
    const GLASS_PANE = 102;
    const GLASS_PANEL = 102;
    const MELON_BLOCK = 103;
    const PUMPKIN_STEM = 104;
    const MELON_STEM = 105;
    const VINE = 106;
    const VINES = 106;
    const FENCE_GATE = 107;
    const BRICK_STAIRS = 108;
    const STONE_BRICK_STAIRS = 109;
    const MYCELIUM = 110;
    const WATER_LILY = 111;
    const LILY_PAD = 111;
    const NETHER_BRICKS = 112;
    const NETHER_BRICK_BLOCK = 112;
    const NETHER_BRICK_FENCE = 113;
    const NETHER_BRICKS_STAIRS = 114;
    const NETHER_WART_BLOCK = 115;
    const ENCHANTING_TABLE = 116;
    const ENCHANT_TABLE = 116;
    const ENCHANTMENT_TABLE = 116;
    const BREWING_STAND_BLOCK = 117;
    const CAULDRON_ITEM = 118;
    const END_PORTAL = 119;
    const END_PORTAL_FRAME = 120;
    const END_STONE = 121;
    const DRAGON_EGG = 122;
    const REDSTONE_LAMP = 123;
    const LIT_REDSTONE_LAMP = 124;
	const DROPPER = 125;
    // const DOUBLE_WOODEN_SLAB = 125;
    // const WOODEN_SLAB = 126;
    const ACTIVATOR_RAIL = 126;
    const COCOA_POD = 127;
    const COCOA_BEANS = 127;
    const SANDSTONE_STAIRS = 128;
    const EMERALD_ORE = 129;
    const ENDERCHEST = 130;
    const TRIPWIRE_HOOK = 131;
    const TRIPWIRE = 132;
    const EMERALD_BLOCK = 133;
    const SPRUCE_WOOD_STAIRS = 134;
    const SPRUCE_WOODEN_STAIRS = 134;
    const BIRCH_WOOD_STAIRS = 135;
    const BIRCH_WOODEN_STAIRS = 135;
    const JUNGLE_WOOD_STAIRS = 136;
    const JUNGLE_WOODEN_STAIRS = 136;
    const COMMAND_BLOCK = 136;
    const BEACON = 136;
    const COBBLE_WALL = 139;
    const STONE_WALL = 139;
    const COBBLESTONE_WALL = 139;
    const FLOWER_POT_BLOCK = 140;
    const CARROT_BLOCK = 141;
    const POTATO_BLOCK = 142;
    const WOODEN_BUTTON = 143;
    const SKULL_BLOCK = 144;
    const HEAD_BLOCK = 144;
    const MOB_HEAD_BLOCK = 144;
    const ANVIL_BLOCK = 145;
    const TRAPPED_CHEST = 146;
    const LIGHT_WEIGHTED_PRESSURE_PLATE = 147;
    const HEAVY_WEIGHTED_PRESSURE_PLATE = 148;
    const UNLIT_REDSTONE_COMPARATOR = 149;
    const LIT_REDSTONE_COMPARATOR = 150;
    const DAYLIGHT_DETECTOR = 151;
    const REDSTONE_BLOCK = 152;
    const NETHER_QUARTZ_ORE = 153;
    const QUARTZ_ORE = 153;
    const HOPPER_BLOCK = 154;
    const QUARTZ_BLOCK = 155;
    const QUARTZ_STAIRS = 156;
    const DOUBLE_WOOD_SLAB = 157;
    const DOUBLE_WOODEN_SLAB = 157;
    const DOUBLE_WOOD_SLABS = 157;
    const DOUBLE_WOODEN_SLABS = 157;
    const WOOD_SLAB = 158;
    const WOODEN_SLAB = 158;
    const WOOD_SLABS = 158;
    const WOODEN_SLABS = 158;
    const STAINED_CLAY = 159;
    const STAINED_HARDENED_CLAY = 159;
    const STAINED_GLASS_PANE = 160;
    const LEAVES2 = 161;
    const LEAVE2 = 161;
    const WOOD2 = 162;
    const TRUNK2 = 162;
    const LOG2 = 162;
    const ACACIA_WOOD_STAIRS = 163;
    const ACACIA_WOODEN_STAIRS = 163;
    const DARK_OAK_WOOD_STAIRS = 164;
    const DARK_OAK_WOODEN_STAIRS = 164;
    const SLIME_BLOCK = 165;
    const BARRIER = 166;
    const IRON_TRAPDOOR = 167;
    const PRISMARINE = 168;
    const SEA_LANTERN = 169;
    const HAY_BALE = 170;
    const CARPET = 171;
    const HARDENED_CLAY = 172;
    const COAL_BLOCK = 173;
    const PACKED_ICE = 174;
    const DOUBLE_PLANT = 175;
    const STANDING_BANNER = 176;
    const WALL_BANNER = 177;
    const DAYLIGHT_DETECTOR_INVERTED = 178;
    const RED_SANDSTONE = 179;
    const RED_SANDSTONE_STAIRS = 180;
    const DOUBLE_STONE_SLAB2 = 181;
    const STONE_SLAB2 = 182;
    const FENCE_GATE_SPRUCE = 183;
    const FENCE_GATE_BIRCH = 184;
    const FENCE_GATE_JUNGLE = 185;
    const FENCE_GATE_DARK_OAK = 186;
    const FENCE_GATE_ACACIA = 187;
    const SPRUCE_DOOR_BLOCK = 193;
    const BIRCH_DOOR_BLOCK = 194;
    const JUNGLE_DOOR_BLOCK = 195;
    const ACACIA_DOOR_BLOCK = 196;
    const DARK_OAK_DOOR_BLOCK = 197;
    const GRASS_PATH = 198;
    const PODZOL = 243;
    const BEETROOT_BLOCK = 244;
    const STONECUTTER = 245;
    const GLOWING_OBSIDIAN = 246;
    const NETHER_REACTOR = 247;
    const RESERVED = 255;

    //Normal Item IDs

    const IRON_SHOVEL= 256;
    const IRON_PICKAXE = 257;
    const IRON_AXE = 258;
    const FLINT_STEEL = 259;
    const FLINT_AND_STEEL = 259;
    const APPLE = 260;
    const BOW = 261;
    const ARROW = 262;
    const COAL = 263;
    const DIAMOND = 264;
    const IRON_INGOT = 265;
    const GOLD_INGOT = 266;
    const IRON_SWORD = 267;
    const WOODEN_SWORD = 268;
    const WOODEN_SHOVEL = 269;
    const WOODEN_PICKAXE = 270;
    const WOODEN_AXE = 271;
    const STONE_SWORD = 272;
    const STONE_SHOVEL = 273;
    const STONE_PICKAXE = 274;
    const STONE_AXE = 275;
    const DIAMOND_SWORD = 276;
    const DIAMOND_SHOVEL = 277;
    const DIAMOND_PICKAXE = 278;
    const DIAMOND_AXE = 279;
    const STICK = 280;
    const STICKS = 280;
    const BOWL = 281;
    const MUSHROOM_STEW = 282;
    const GOLD_SWORD = 283;
    const GOLD_SHOVEL = 284;
    const GOLD_PICKAXE = 285;
    const GOLD_AXE = 286;
    const GOLDEN_SWORD = 283;
    const GOLDEN_SHOVEL = 284;
    const GOLDEN_PICKAXE = 285;
    const GOLDEN_AXE = 286;
    const STRING = 287;
    const FEATHER = 288;
    const GUNPOWDER = 289;
    const WOODEN_HOE = 290;
    const STONE_HOE = 291;
    const IRON_HOE = 292;
    const DIAMOND_HOE = 293;
    const GOLD_HOE = 294;
    const GOLDEN_HOE = 294;
    const SEEDS = 295;
    const WHEAT_SEEDS = 295;
    const WHEAT = 296;
    const BREAD = 297;
    const LEATHER_CAP = 298;
    const LEATHER_TUNIC = 299;
    const LEATHER_PANTS = 300;
    const LEATHER_BOOTS = 301;
    const CHAIN_HELMET = 302;
    const CHAIN_CHESTPLATE = 303;
    const CHAIN_LEGGINGS = 304;
    const CHAIN_BOOTS = 305;
    const IRON_HELMET = 306;
    const IRON_CHESTPLATE = 307;
    const IRON_LEGGINGS = 308;
    const IRON_BOOTS = 309;
    const DIAMOND_HELMET = 310;
    const DIAMOND_CHESTPLATE = 311;
    const DIAMOND_LEGGINGS = 312;
    const DIAMOND_BOOTS = 313;
    const GOLD_HELMET = 314;
    const GOLD_CHESTPLATE = 315;
    const GOLD_LEGGINGS = 316;
    const GOLD_BOOTS = 317;
    const FLINT = 318;
    const RAW_PORKCHOP = 319;
    const COOKED_PORKCHOP = 320;
    const PAINTING = 321;
    const GOLDEN_APPLE = 322;
    const SIGN = 323;
    const OAK_DOOR = 324;
    const BUCKET = 325;
    const MINECART = 328;
    const SADDLE = 329;
    const IRON_DOOR = 330;
    const REDSTONE = 331;
    const REDSTONE_DUST = 331;
    const SNOWBALL = 332;
    const BOAT = 333;
    const LEATHER = 334;
    const BRICK = 336;
    const CLAY = 337;
    const SUGARCANE = 338;
    const SUGAR_CANE = 338;
    const SUGAR_CANES = 338;
    const PAPER = 339;
    const BOOK = 340;
    const SLIMEBALL = 341;
    const MINECART_CHEST = 342;
    const MINECART_FURNACE = 343;
    const EGG = 344;
    const COMPASS = 345;
    const FISHING_ROD = 346;
    const CLOCK = 347;
    const GLOWSTONE_DUST = 348;
    const RAW_FISH = 349;
    const COOKED_FISH = 350;
    const DYE = 351;
    const BONE = 352;
    const SUGAR = 353;
    const CAKE = 354;
    const BED = 355;
    const REDSTONE_REPEATER_ITEM = 356;
    const COOKIE = 357;
    const SHEARS = 359;
    const MELON = 360;
    const MELON_SLICE = 360;
    const PUMPKIN_SEEDS = 361;
    const MELON_SEEDS = 362;
    const RAW_BEEF = 363;
    const STEAK = 364;
    const COOKED_BEEF = 364;
    const RAW_CHICKEN = 365;
    const COOKED_CHICKEN = 366;
    const ROTTEN_FLESH = 367;
    const ENDER_PEARL = 368;
    const BLAZE_ROD = 369;
    const GHAST_TEAR = 370;
    const GOLD_NUGGET = 371;
    const GOLDEN_NUGGET = 371;
    const NETHER_WART = 372;
    const POTION = 373;
    const GLASS_BOTTLE = 374;
    const SPIDER_EYE = 375;
    const FERMENTED_SPIDER_EYE = 376;
    const BLAZE_POWDER = 377;
    const MAGMA_CREAM = 378;
    const BREWING_STAND = 379;
    const CAULDRON = 380;
    //const ENDER_EYE =  381;
    const GLISTERING_MELON = 382;
    const SPAWN_EGG = 383;
    const EXP_BOTTLE = 384;
    const EMERALD = 388;
    const ITEM_FRAME = 389;
    const FLOWER_POT = 390;
    const CARROT = 391;
    const CARROTS = 391;
    const POTATO = 392;
    const POTATOES = 392;
    const BAKED_POTATO = 393;
    const BAKED_POTATOES = 393;
    const POISONOUS_POTATO = 394;
    const MAP = 395;
    const GOLDEN_CARROT = 396;
    const MOB_HEAD = 397;
    const SKULL = 397;
    //const STICK_CARROT = 398;
    //const NETHER_STAR = 399;
    const PUMPKIN_PIE = 400;
    const REDSTONE_COMPARATOR_ITEM = 404;
    const ENCHANTED_BOOK = 403;
    const NETHER_BRICK = 405;
    const QUARTZ = 406;
    const NETHER_QUARTZ = 406;
    const MINECART_TNT = 407;
    const MINECART_HOPPER = 408;
	const HOPPER = 410;
    const RAW_RABBIT = 411;
    const COOKED_RABBIT = 412;
    const RABBIT_STEW = 413;
    const RABBIT_FOOT = 414;
    const RABBIT_HIDE = 415;
    //const MINECART_COMMAND_BLOCK = 422;
    const SPRUCE_DOOR = 427;
    const BIRCH_DOOR = 428;
    const JUNGLE_DOOR = 429;
    const ACACIA_DOOR = 430;
    const DARK_OAK_DOOR = 431;
    const SPLASH_POTION = 438;
    // const SPRUCE_BOAT = 444;
    // const BIRCH_BOAT = 445;
    // const JUNGLE_BOAT = 446;
    // const ACACIA_BOAT = 447;
    // const DARK_OAK_BOAT = 448;
    const CAMERA = 456;
    const BEETROOT = 457;
    const BEETROOT_SEEDS = 458;
    const BEETROOT_SEED = 458;
    const BEETROOT_SOUP = 459;

    protected static $cachedParser = null;
    protected static $creative = array();
    
    /**
     * @var array
     * converted to \SplFixedArray in Item::init()
     */
    public static $list = array(
        self::IRON_SHOVEL => IronShovel::class,
        self::IRON_PICKAXE => IronPickaxe::class,
        self::IRON_AXE => IronAxe::class,
        self::FLINT_STEEL => FlintSteel::class,
        self::APPLE => Apple::class,
        self::BOW => Bow::class,
        self::ANVIL_BLOCK => AnvilBlock::class,
        self::ARROW => Arrow::class,
        self::COAL => Coal::class,
        self::DIAMOND => Diamond::class,
        self::IRON_INGOT => IronIngot::class,
        self::GOLD_INGOT => GoldIngot::class,
        self::IRON_SWORD => IronSword::class,
        self::WOODEN_SWORD => WoodenSword::class,
        self::WOODEN_SHOVEL => WoodenShovel::class,
        self::WOODEN_PICKAXE => WoodenPickaxe::class,
        self::WOODEN_AXE => WoodenAxe::class,
        self::STONE_SWORD => StoneSword::class,
        self::STONE_SHOVEL => StoneShovel::class,
        self::STONE_PICKAXE => StonePickaxe::class,
        self::STONE_AXE => StoneAxe::class,
        self::DIAMOND_SWORD => DiamondSword::class,
        self::DIAMOND_SHOVEL => DiamondShovel::class,
        self::DIAMOND_PICKAXE => DiamondPickaxe::class,
        self::DIAMOND_AXE => DiamondAxe::class,
        self::STICK => Stick::class,
        self::BOWL => Bowl::class,
        self::MUSHROOM_STEW => MushroomStew::class,
        self::GOLD_SWORD => GoldSword::class,
        self::GOLD_SHOVEL => GoldShovel::class,
        self::GOLD_PICKAXE => GoldPickaxe::class,
        self::GOLD_AXE => GoldAxe::class,
        self::STRING => StringItem::class,
        self::FEATHER => Feather::class,
        self::GUNPOWDER => Gunpowder::class,
        self::WOODEN_HOE => WoodenHoe::class,
        self::STONE_HOE => StoneHoe::class,
        self::IRON_HOE => IronHoe::class,
        self::DIAMOND_HOE => DiamondHoe::class,
        self::GOLD_HOE => GoldHoe::class,
        self::WHEAT_SEEDS => WheatSeeds::class,
        self::WHEAT => Wheat::class,
        self::BREAD => Bread::class,
        self::LEATHER_CAP => LeatherCap::class,
        self::LEATHER_TUNIC => LeatherTunic::class,
        self::LEATHER_PANTS => LeatherPants::class,
        self::LEATHER_BOOTS => LeatherBoots::class,
        self::CHAIN_HELMET => ChainHelmet::class,
        self::CHAIN_CHESTPLATE => ChainChestplate::class,
        self::CHAIN_LEGGINGS => ChainLeggings::class,
        self::CHAIN_BOOTS => ChainBoots::class,
        self::IRON_HELMET => IronHelmet::class,
        self::IRON_CHESTPLATE => IronChestplate::class,
        self::IRON_LEGGINGS => IronLeggings::class,
        self::IRON_BOOTS => IronBoots::class,
        self::DIAMOND_HELMET => DiamondHelmet::class,
        self::DIAMOND_CHESTPLATE => DiamondChestplate::class,
        self::DIAMOND_LEGGINGS => DiamondLeggings::class,
        self::DIAMOND_BOOTS => DiamondBoots::class,
        self::GOLD_HELMET => GoldHelmet::class,
        self::GOLD_CHESTPLATE => GoldChestplate::class,
        self::GOLD_LEGGINGS => GoldLeggings::class,
        self::GOLD_BOOTS => GoldBoots::class,
        self::FLINT => Flint::class,
        self::RAW_PORKCHOP => RawPorkchop::class,
        self::COOKED_PORKCHOP => CookedPorkchop::class,
        self::PAINTING => Painting::class,
        self::GOLDEN_APPLE => GoldenApple::class,
        self::SIGN => Sign::class,
        self::OAK_DOOR => OakDoor::class,
        self::ACACIA_DOOR => AcaciaDoor::class,
        self::BIRCH_DOOR => BirchDoor::class,
        self::DARK_OAK_DOOR => DarkOakDoor::class,
        self::JUNGLE_DOOR => JungleDoor::class,
        self::SPRUCE_DOOR => SpruceDoor::class,
        self::IRON_DOOR => IronDoor::class,
        self::MAP => Map::class,
        self::BUCKET => Bucket::class,
        self::MINECART => Minecart::class,
        // self::MINECART_CHEST => MinecartChest::class,
        // self::MINECART_TNT => MinecartTNT::class,
        // self::MINECART_HOPPER => MinecartHopper::class,
        // self::SADDLE => Saddle::class,
        self::IRON_DOOR => IronDoor::class,
        self::REDSTONE => Redstone::class,
        self::SNOWBALL => Snowball::class,
        self::BOAT => Boat::class,
        self::LEATHER => Leather::class,
        self::BRICK => Brick::class,
        self::CLAY => Clay::class,
        self::SUGARCANE => Sugarcane::class,
        self::PAPER => Paper::class,
        self::BOOK => Book::class,
        self::SLIMEBALL => Slimeball::class,
        self::EGG => Egg::class,
        self::COMPASS => Compass::class,
        self::FISHING_ROD => FishingRod::class,
        self::CLOCK => Clock::class,
        self::GLOWSTONE_DUST => GlowstoneDust::class,
        self::RAW_FISH => Fish::class,
        self::COOKED_FISH => CookedFish::class,
        self::DYE => Dye::class,
        self::BONE => Bone::class,
        self::SUGAR => Sugar::class,
        self::CAKE => Cake::class,
        self::BED => Bed::class,
        self::COOKIE => Cookie::class,
        self::SHEARS => Shears::class,
        self::MELON => Melon::class,
        self::PUMPKIN_SEEDS => PumpkinSeeds::class,
        self::MELON_SEEDS => MelonSeeds::class,
        self::RAW_BEEF => RawBeef::class,
        self::STEAK => Steak::class,
        self::RAW_CHICKEN => RawChicken::class,
        self::COOKED_CHICKEN => CookedChicken::class,
        self::ROTTEN_FLESH => RottenFlesh::class,
        self::BLAZE_ROD => BlazeRod::class,
        self::GHAST_TEAR => GhastTear::class,
        self::GOLD_NUGGET => GoldNugget::class,
        self::NETHER_WART => NetherWart::class,
        self::POTION => Potion::class,
        self::GLASS_BOTTLE => GlassBottle::class,
        self::SPIDER_EYE => Spidereye::class,
        self::FERMENTED_SPIDER_EYE => FermentedSpiderEye::class,
        self::BLAZE_POWDER => BlazePowder::class,
        self::MAGMA_CREAM => MagmaCream::class,
        self::BREWING_STAND => BrewingStand::class,
        self::GLISTERING_MELON => GlisteringMelon::class,
        self::CAULDRON_ITEM => Cauldron::class,
        self::SPAWN_EGG => SpawnEgg::class,
        self::EXP_BOTTLE => EXPBottle::class,
        self::EMERALD => Emerald::class,
        self::FLOWER_POT => FlowerPot::class,
        self::CARROT => Carrot::class,
        self::POTATO => Potato::class,
        self::BAKED_POTATO => BakedPotato::class,
        self::POISONOUS_POTATO => PoisonousPotato::class,
        self::GOLDEN_CARROT => GoldenCarrot::class,
        self::SKULL => Skull::class,
        self::PUMPKIN_PIE => PumpkinPie::class,
        self::ENCHANTED_BOOK => EnchantedBook::class,
        self::NETHER_BRICK => NetherBrick::class,
        // self::QUARTZ => Quartz::class,
        self::QUARTZ => NetherQuartz::class,
		self::HOPPER => Hopper::class,
        self::RAW_RABBIT => RawRabbit::class,
        self::COOKED_RABBIT => CookedRabbit::class,
        self::RABBIT_STEW => RabbitStew::class,
        self::RABBIT_FOOT => RabbitFoot::class,
        self::SPLASH_POTION => SplashPotion::class,
        // self::CAMERA => Camera::class,
        self::BEETROOT => Beetroot::class,
        self::BEETROOT_SEEDS => BeetrootSeeds::class,
        self::BEETROOT_SOUP => BeetrootSoup::class
    );

    public static function addCreativeItem(Item $item) {
        self::$creative[] = self::get($item->getId(), $item->getDamage());
    }

    public static function get($id, $meta = 0, $count = 1, $tags = ""){
        try{
            $class = self::$list[$id];
            if($class === null){
                return (new Item($id, $meta, $count))->setCompoundTag($tags);
            }elseif($id < 256){
                return (new ItemBlock(new $class($meta), $meta, $count))->setCompoundTag($tags);
            }else{
                return (new $class($meta, $count))->setCompoundTag($tags);
            }
        }catch(\RuntimeException $e){
            return (new Item($id, $meta, $count))->setCompoundTag($tags);
        }
    }

    protected static function buildingTab(){
        //Building
        self::addCreativeItem(self::get(self::COBBLESTONE, 0));
        self::addCreativeItem(self::get(self::STONE_BRICKS, 0));
        self::addCreativeItem(self::get(self::STONE_BRICKS, 1));
        self::addCreativeItem(self::get(self::STONE_BRICKS, 2));
        self::addCreativeItem(self::get(self::STONE_BRICKS, 3));
        self::addCreativeItem(self::get(self::MOSS_STONE, 0));
        self::addCreativeItem(self::get(self::WOODEN_PLANKS, 0));
        self::addCreativeItem(self::get(self::WOODEN_PLANKS, 1));
        self::addCreativeItem(self::get(self::WOODEN_PLANKS, 2));
        self::addCreativeItem(self::get(self::WOODEN_PLANKS, 3));
        self::addCreativeItem(self::get(self::WOODEN_PLANKS, 4));
        self::addCreativeItem(self::get(self::WOODEN_PLANKS, 5));
        self::addCreativeItem(self::get(self::BRICKS, 0));
        self::addCreativeItem(self::get(self::STONE, 0));
        self::addCreativeItem(self::get(self::STONE, 1));
        self::addCreativeItem(self::get(self::STONE, 2));
        self::addCreativeItem(self::get(self::STONE, 3));
        self::addCreativeItem(self::get(self::STONE, 4));
        self::addCreativeItem(self::get(self::STONE, 5));
        self::addCreativeItem(self::get(self::STONE, 6));
        self::addCreativeItem(self::get(self::DIRT, 0));
        self::addCreativeItem(self::get(self::PODZOL, 0));
        self::addCreativeItem(self::get(self::GRASS, 0));
        self::addCreativeItem(self::get(self::MYCELIUM, 0));
        self::addCreativeItem(self::get(self::CLAY_BLOCK, 0));
        self::addCreativeItem(self::get(self::HARDENED_CLAY, 0));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 0));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 1));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 2));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 3));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 4));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 5));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 6));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 7));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 8));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 9));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 10));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 11));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 12));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 13));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 14));
        self::addCreativeItem(self::get(self::STAINED_CLAY, 15));
        self::addCreativeItem(self::get(self::SANDSTONE, 0));
        self::addCreativeItem(self::get(self::SANDSTONE, 1));
        self::addCreativeItem(self::get(self::SANDSTONE, 2));
        self::addCreativeItem(self::get(self::RED_SANDSTONE, 0));
        self::addCreativeItem(self::get(self::RED_SANDSTONE, 1));
        self::addCreativeItem(self::get(self::RED_SANDSTONE, 2));
        self::addCreativeItem(self::get(self::SAND, 0));
        self::addCreativeItem(self::get(self::SAND, 1));
        self::addCreativeItem(self::get(self::GRAVEL, 0));
        self::addCreativeItem(self::get(self::TRUNK, 0));
        self::addCreativeItem(self::get(self::TRUNK, 1));
        self::addCreativeItem(self::get(self::TRUNK, 2));
        self::addCreativeItem(self::get(self::TRUNK, 3));
        self::addCreativeItem(self::get(self::TRUNK2, 0));
        self::addCreativeItem(self::get(self::TRUNK2, 1));
        self::addCreativeItem(self::get(self::NETHER_BRICKS, 0));
        self::addCreativeItem(self::get(self::NETHERRACK, 0));
        self::addCreativeItem(self::get(self::SOUL_SAND, 0));
        self::addCreativeItem(self::get(self::BEDROCK, 0));
        self::addCreativeItem(self::get(self::COBBLESTONE_STAIRS, 0));
        self::addCreativeItem(self::get(self::OAK_WOODEN_STAIRS, 0));
        self::addCreativeItem(self::get(self::SPRUCE_WOODEN_STAIRS, 0));
        self::addCreativeItem(self::get(self::BIRCH_WOODEN_STAIRS, 0));
        self::addCreativeItem(self::get(self::JUNGLE_WOODEN_STAIRS, 0));
        self::addCreativeItem(self::get(self::ACACIA_WOODEN_STAIRS, 0));
        self::addCreativeItem(self::get(self::DARK_OAK_WOODEN_STAIRS, 0));
        self::addCreativeItem(self::get(self::BRICK_STAIRS, 0));
        self::addCreativeItem(self::get(self::SANDSTONE_STAIRS, 0));
        self::addCreativeItem(self::get(self::RED_SANDSTONE_STAIRS, 0));
        self::addCreativeItem(self::get(self::STONE_BRICK_STAIRS, 0));
        self::addCreativeItem(self::get(self::NETHER_BRICKS_STAIRS, 0));
        self::addCreativeItem(self::get(self::QUARTZ_STAIRS, 0));
        self::addCreativeItem(self::get(self::SLAB, 0));
        self::addCreativeItem(self::get(self::SLAB, 3));
        self::addCreativeItem(self::get(self::WOODEN_SLAB, 0));
        self::addCreativeItem(self::get(self::WOODEN_SLAB, 1));
        self::addCreativeItem(self::get(self::WOODEN_SLAB, 2));
        self::addCreativeItem(self::get(self::WOODEN_SLAB, 3));
        self::addCreativeItem(self::get(self::WOODEN_SLAB, 4));
        self::addCreativeItem(self::get(self::WOODEN_SLAB, 5));
        self::addCreativeItem(self::get(self::SLAB, 4));
        self::addCreativeItem(self::get(self::SLAB, 1));
        self::addCreativeItem(self::get(self::SLAB, 5));
        self::addCreativeItem(self::get(self::SLAB, 6));
        self::addCreativeItem(self::get(self::SLAB, 7));
        self::addCreativeItem(self::get(self::QUARTZ_BLOCK, 0));
        self::addCreativeItem(self::get(self::QUARTZ_BLOCK, 1));
        self::addCreativeItem(self::get(self::QUARTZ_BLOCK, 2));
        self::addCreativeItem(self::get(self::COAL_ORE, 0));
        self::addCreativeItem(self::get(self::IRON_ORE, 0));
        self::addCreativeItem(self::get(self::GOLD_ORE, 0));
        self::addCreativeItem(self::get(self::DIAMOND_ORE, 0));
        self::addCreativeItem(self::get(self::LAPIS_ORE, 0));
        self::addCreativeItem(self::get(self::REDSTONE_ORE, 0));
        self::addCreativeItem(self::get(self::EMERALD_ORE, 0));
        self::addCreativeItem(self::get(self::NETHER_QUARTZ_ORE, 0));
        self::addCreativeItem(self::get(self::OBSIDIAN, 0));
        self::addCreativeItem(self::get(self::ICE, 0));
        self::addCreativeItem(self::get(self::PACKED_ICE, 0));
        self::addCreativeItem(self::get(self::SNOW_BLOCK, 0));
        self::addCreativeItem(self::get(self::END_STONE, 0));
    }

    protected static function decorationTab(){
        //Decoration
        self::addCreativeItem(self::get(self::COBBLESTONE_WALL, 0));
        self::addCreativeItem(self::get(self::COBBLESTONE_WALL, 1));
        self::addCreativeItem(self::get(self::WATER_LILY, 0));
        self::addCreativeItem(self::get(self::GOLD_BLOCK, 0));
        self::addCreativeItem(self::get(self::IRON_BLOCK, 0));
        self::addCreativeItem(self::get(self::DIAMOND_BLOCK, 0));
        self::addCreativeItem(self::get(self::LAPIS_BLOCK, 0));
        self::addCreativeItem(self::get(self::COAL_BLOCK, 0));
        self::addCreativeItem(self::get(self::EMERALD_BLOCK, 0));
        self::addCreativeItem(self::get(self::REDSTONE_BLOCK, 0));
        self::addCreativeItem(self::get(self::SNOW_LAYER, 0));
        self::addCreativeItem(self::get(self::GLASS, 0));
        self::addCreativeItem(self::get(self::GLOWSTONE_BLOCK, 0));
        self::addCreativeItem(self::get(self::VINES, 0));
        //self::addCreativeItem(self::get(self::NETHER_REACTOR, 0));
        self::addCreativeItem(self::get(self::LADDER, 0));
        self::addCreativeItem(self::get(self::SPONGE, 0));
        self::addCreativeItem(self::get(self::GLASS_PANE, 0));
        self::addCreativeItem(self::get(self::OAK_DOOR, 0));
        self::addCreativeItem(self::get(self::SPRUCE_DOOR, 0));
        self::addCreativeItem(self::get(self::BIRCH_DOOR, 0));
        self::addCreativeItem(self::get(self::JUNGLE_DOOR, 0));
        self::addCreativeItem(self::get(self::ACACIA_DOOR, 0));
        self::addCreativeItem(self::get(self::DARK_OAK_DOOR, 0));
        self::addCreativeItem(self::get(self::IRON_DOOR, 0));
        self::addCreativeItem(self::get(self::TRAPDOOR, 0));
        self::addCreativeItem(self::get(self::IRON_TRAPDOOR, 0));
        self::addCreativeItem(self::get(self::FENCE, Fence::FENCE_OAK));
        self::addCreativeItem(self::get(self::FENCE, Fence::FENCE_SPRUCE));
        self::addCreativeItem(self::get(self::FENCE, Fence::FENCE_BIRCH));
        self::addCreativeItem(self::get(self::FENCE, Fence::FENCE_JUNGLE));
        self::addCreativeItem(self::get(self::FENCE, Fence::FENCE_ACACIA));
        self::addCreativeItem(self::get(self::FENCE, Fence::FENCE_DARKOAK));
        self::addCreativeItem(self::get(self::NETHER_BRICK_FENCE, 0));
        self::addCreativeItem(self::get(self::FENCE_GATE, 0));
        self::addCreativeItem(self::get(self::FENCE_GATE_BIRCH, 0));
        self::addCreativeItem(self::get(self::FENCE_GATE_SPRUCE, 0));
        self::addCreativeItem(self::get(self::FENCE_GATE_DARK_OAK, 0));
        self::addCreativeItem(self::get(self::FENCE_GATE_JUNGLE, 0));
        self::addCreativeItem(self::get(self::FENCE_GATE_ACACIA, 0));
        self::addCreativeItem(self::get(self::IRON_BARS, 0));
        self::addCreativeItem(self::get(self::BED, 0));
        self::addCreativeItem(self::get(self::BOOKSHELF, 0));
        self::addCreativeItem(self::get(self::PAINTING, 0));
        self::addCreativeItem(self::get(self::ITEM_FRAME, 0));
        self::addCreativeItem(self::get(self::WORKBENCH, 0));
        self::addCreativeItem(self::get(self::STONECUTTER, 0));
        self::addCreativeItem(self::get(self::CHEST, 0));
        self::addCreativeItem(self::get(self::TRAPPED_CHEST, 0));
        self::addCreativeItem(self::get(self::FURNACE, 0));
        self::addCreativeItem(self::get(self::BREWING_STAND, 0));
        self::addCreativeItem(self::get(self::CAULDRON, 0));
        self::addCreativeItem(self::get(self::NOTEBLOCK, 0));
        self::addCreativeItem(self::get(self::END_PORTAL_FRAME, 0));
        self::addCreativeItem(self::get(self::ANVIL_BLOCK, AnvilBlock::TYPE_ANVIL));
        self::addCreativeItem(self::get(self::ANVIL_BLOCK, AnvilBlock::TYPE_SLIGHTLY_DAMAGED_ANVIL));
        self::addCreativeItem(self::get(self::ANVIL_BLOCK, AnvilBlock::TYPE_VERY_DAMAGED_ANVIL));
        self::addCreativeItem(self::get(self::DANDELION, 0));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_POPPY));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_BLUE_ORCHID));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_ALLIUM));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_AZURE_BLUET));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_RED_TULIP));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_ORANGE_TULIP));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_WHITE_TULIP));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_PINK_TULIP));
        self::addCreativeItem(self::get(self::RED_FLOWER, Flower::TYPE_OXEYE_DAISY));

        self::addCreativeItem(self::get(self::DOUBLE_PLANT, 0)); // SUNFLOWER ?
        self::addCreativeItem(self::get(self::DOUBLE_PLANT, 1)); // Lilac ?
        self::addCreativeItem(self::get(self::DOUBLE_PLANT, 2)); // Double TALL_GRASS
        self::addCreativeItem(self::get(self::DOUBLE_PLANT, 3)); // Large fern
        self::addCreativeItem(self::get(self::DOUBLE_PLANT, 4)); // Rose bush
        self::addCreativeItem(self::get(self::DOUBLE_PLANT, 5)); // Peony

        self::addCreativeItem(self::get(self::BROWN_MUSHROOM, 0));
        self::addCreativeItem(self::get(self::RED_MUSHROOM, 0));
        self::addCreativeItem(self::get(self::BROWN_MUSHROOM_BLOCK, 0));
        self::addCreativeItem(self::get(self::RED_MUSHROOM_BLOCK, 0));
        self::addCreativeItem(self::get(self::BROWN_MUSHROOM_BLOCK, 15));
        self::addCreativeItem(self::get(self::RED_MUSHROOM_BLOCK, 15));
        self::addCreativeItem(self::get(self::CACTUS, 0));
        self::addCreativeItem(self::get(self::MELON_BLOCK, 0));
        self::addCreativeItem(self::get(self::PUMPKIN, 0));
        self::addCreativeItem(self::get(self::LIT_PUMPKIN, 0));
        self::addCreativeItem(self::get(self::COBWEB, 0));
        self::addCreativeItem(self::get(self::HAY_BALE, 0));
        self::addCreativeItem(self::get(self::TALL_GRASS, 1)); // Grass
        self::addCreativeItem(self::get(self::TALL_GRASS, 2)); // Fern
        self::addCreativeItem(self::get(self::DEAD_BUSH, 0));

        self::addCreativeItem(self::get(self::SAPLING, 0)); // Oak
        self::addCreativeItem(self::get(self::SAPLING, 1)); // Spruce
        self::addCreativeItem(self::get(self::SAPLING, 2)); // Birtch
        self::addCreativeItem(self::get(self::SAPLING, 3)); // Jungle
        self::addCreativeItem(self::get(self::SAPLING, 4)); // Acacia
        self::addCreativeItem(self::get(self::SAPLING, 5)); // Dark oak

        self::addCreativeItem(self::get(self::LEAVES, 0)); // Oak
        self::addCreativeItem(self::get(self::LEAVES, 1)); // Spruce
        self::addCreativeItem(self::get(self::LEAVES, 2)); // Birtch
        self::addCreativeItem(self::get(self::LEAVES, 3)); // Jungle
        self::addCreativeItem(self::get(self::LEAVES2, 0)); // Acacia
        self::addCreativeItem(self::get(self::LEAVES2, 1)); // Dark oak

        self::addCreativeItem(self::get(self::CAKE, 0));

        self::addCreativeItem(self::get(self::SKULL, 0)); // Skeleton
        self::addCreativeItem(self::get(self::SKULL, 1)); // Wither Skeleton
        self::addCreativeItem(self::get(self::SKULL, 2)); // Zombie
        self::addCreativeItem(self::get(self::SKULL, 3)); // Head (Steve)
        self::addCreativeItem(self::get(self::SKULL, 4)); // Creeper

        self::addCreativeItem(self::get(self::SIGN, 0));
        self::addCreativeItem(self::get(self::FLOWER_POT, 0));
        self::addCreativeItem(self::get(self::MONSTER_SPAWNER, 0));
        self::addCreativeItem(self::get(self::ENCHANTING_TABLE, 0));
        self::addCreativeItem(self::get(self::SLIME_BLOCK, 0));
        self::addCreativeItem(self::get(self::WOOL, 0));
        self::addCreativeItem(self::get(self::WOOL, 8));
        self::addCreativeItem(self::get(self::WOOL, 7));
        self::addCreativeItem(self::get(self::WOOL, 15));
        self::addCreativeItem(self::get(self::WOOL, 12));
        self::addCreativeItem(self::get(self::WOOL, 14));
        self::addCreativeItem(self::get(self::WOOL, 1));
        self::addCreativeItem(self::get(self::WOOL, 4));
        self::addCreativeItem(self::get(self::WOOL, 5));
        self::addCreativeItem(self::get(self::WOOL, 13));
        self::addCreativeItem(self::get(self::WOOL, 9));
        self::addCreativeItem(self::get(self::WOOL, 3));
        self::addCreativeItem(self::get(self::WOOL, 11));
        self::addCreativeItem(self::get(self::WOOL, 10));
        self::addCreativeItem(self::get(self::WOOL, 2));
        self::addCreativeItem(self::get(self::WOOL, 6));


        self::addCreativeItem(self::get(self::CARPET, 0));
        self::addCreativeItem(self::get(self::CARPET, 8));
        self::addCreativeItem(self::get(self::CARPET, 7));
        self::addCreativeItem(self::get(self::CARPET, 15));
        self::addCreativeItem(self::get(self::CARPET, 12));
        self::addCreativeItem(self::get(self::CARPET, 14));
        self::addCreativeItem(self::get(self::CARPET, 1));
        self::addCreativeItem(self::get(self::CARPET, 4));
        self::addCreativeItem(self::get(self::CARPET, 5));
        self::addCreativeItem(self::get(self::CARPET, 13));
        self::addCreativeItem(self::get(self::CARPET, 9));
        self::addCreativeItem(self::get(self::CARPET, 3));
        self::addCreativeItem(self::get(self::CARPET, 11));
        self::addCreativeItem(self::get(self::CARPET, 10));
        self::addCreativeItem(self::get(self::CARPET, 2));
        self::addCreativeItem(self::get(self::CARPET, 6));
    }

    protected static function toolsTab(){
        //Tools
        self::addCreativeItem(self::get(self::RAIL, 0));
        self::addCreativeItem(self::get(self::POWERED_RAIL, 0));
        self::addCreativeItem(self::get(self::DETECTOR_RAIL, 0));
        self::addCreativeItem(self::get(self::ACTIVATOR_RAIL, 0));
        self::addCreativeItem(self::get(self::TORCH, 0));
        self::addCreativeItem(self::get(self::BUCKET, 0));
        self::addCreativeItem(self::get(self::BUCKET, 1)); // milk
        self::addCreativeItem(self::get(self::BUCKET, 8)); // water
        self::addCreativeItem(self::get(self::BUCKET, 10)); // lava
        self::addCreativeItem(self::get(self::TNT, 0));
        self::addCreativeItem(self::get(self::REDSTONE, 0));
        self::addCreativeItem(self::get(self::BOW, 0));
        self::addCreativeItem(self::get(self::FISHING_ROD, 0));
        self::addCreativeItem(self::get(self::FLINT_AND_STEEL, 0));
        self::addCreativeItem(self::get(self::SHEARS, 0));
        self::addCreativeItem(self::get(self::CLOCK, 0));
        self::addCreativeItem(self::get(self::COMPASS, 0));
        self::addCreativeItem(self::get(self::MINECART, 0));
        self::addCreativeItem(self::get(self::MINECART_CHEST, 0));
        self::addCreativeItem(self::get(self::MINECART_HOPPER, 0));
        self::addCreativeItem(self::get(self::MINECART_TNT, 0));
        self::addCreativeItem(self::get(self::BOAT, 0)); // Oak
        self::addCreativeItem(self::get(self::BOAT, 1)); // Spruce
        self::addCreativeItem(self::get(self::BOAT, 2)); // Birch
        self::addCreativeItem(self::get(self::BOAT, 3)); // Jungle
        self::addCreativeItem(self::get(self::BOAT, 4)); // Acacia
        self::addCreativeItem(self::get(self::BOAT, 5)); // Dark Oak

        self::addCreativeItem(self::get(self::SPAWN_EGG, Villager::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Chicken::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Cow::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Pig::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Sheep::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Wolf::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Ozelot::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Mooshroom::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Bat::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Rabbit::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Creeper::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Enderman::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Silverfish::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Skeleton::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Slime::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Spider::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Zombie::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, PigZombie::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Squid::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Witch::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, CavernSpider::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, MagmaCube::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Ghast::NETWORK_ID));
        self::addCreativeItem(self::get(self::SPAWN_EGG, Blaze::NETWORK_ID));

        //self::addCreativeItem(self::get(self::SPAWN_EGG, 20)); //Iron Golem
        //self::addCreativeItem(self::get(self::SPAWN_EGG, 21)); //Snow Golem
        //self::addCreativeItem(self::get(self::SPAWN_EGG, 44)); //Zombie Villager

        self::addCreativeItem(self::get(self::WOODEN_SWORD));
        self::addCreativeItem(self::get(self::WOODEN_HOE));
        self::addCreativeItem(self::get(self::WOODEN_SHOVEL));
        self::addCreativeItem(self::get(self::WOODEN_PICKAXE));
        self::addCreativeItem(self::get(self::WOODEN_AXE));

        self::addCreativeItem(self::get(self::STONE_SWORD));
        self::addCreativeItem(self::get(self::STONE_HOE));
        self::addCreativeItem(self::get(self::STONE_SHOVEL));
        self::addCreativeItem(self::get(self::STONE_PICKAXE));
        self::addCreativeItem(self::get(self::STONE_AXE));

        self::addCreativeItem(self::get(self::IRON_SWORD));
        self::addCreativeItem(self::get(self::IRON_HOE));
        self::addCreativeItem(self::get(self::IRON_SHOVEL));
        self::addCreativeItem(self::get(self::IRON_PICKAXE));
        self::addCreativeItem(self::get(self::IRON_AXE));

        self::addCreativeItem(self::get(self::DIAMOND_SWORD));
        self::addCreativeItem(self::get(self::DIAMOND_HOE));
        self::addCreativeItem(self::get(self::DIAMOND_SHOVEL));
        self::addCreativeItem(self::get(self::DIAMOND_PICKAXE));
        self::addCreativeItem(self::get(self::DIAMOND_AXE));

        self::addCreativeItem(self::get(self::GOLD_SWORD));
        self::addCreativeItem(self::get(self::GOLD_HOE));
        self::addCreativeItem(self::get(self::GOLD_SHOVEL));
        self::addCreativeItem(self::get(self::GOLD_PICKAXE));
        self::addCreativeItem(self::get(self::GOLD_AXE));

        self::addCreativeItem(self::get(self::LEATHER_CAP));
        self::addCreativeItem(self::get(self::LEATHER_TUNIC));
        self::addCreativeItem(self::get(self::LEATHER_PANTS));
        self::addCreativeItem(self::get(self::LEATHER_BOOTS));

        self::addCreativeItem(self::get(self::CHAIN_HELMET));
        self::addCreativeItem(self::get(self::CHAIN_CHESTPLATE));
        self::addCreativeItem(self::get(self::CHAIN_LEGGINGS));
        self::addCreativeItem(self::get(self::CHAIN_BOOTS));

        self::addCreativeItem(self::get(self::IRON_HELMET));
        self::addCreativeItem(self::get(self::IRON_CHESTPLATE));
        self::addCreativeItem(self::get(self::IRON_LEGGINGS));
        self::addCreativeItem(self::get(self::IRON_BOOTS));

        self::addCreativeItem(self::get(self::DIAMOND_HELMET));
        self::addCreativeItem(self::get(self::DIAMOND_CHESTPLATE));
        self::addCreativeItem(self::get(self::DIAMOND_LEGGINGS));
        self::addCreativeItem(self::get(self::DIAMOND_BOOTS));

        self::addCreativeItem(self::get(self::GOLD_HELMET));
        self::addCreativeItem(self::get(self::GOLD_CHESTPLATE));
        self::addCreativeItem(self::get(self::GOLD_LEGGINGS));
        self::addCreativeItem(self::get(self::GOLD_BOOTS));

        self::addCreativeItem(self::get(self::LEVER));
        self::addCreativeItem(self::get(self::REDSTONE_LAMP));
        self::addCreativeItem(self::get(self::REDSTONE_TORCH));
        self::addCreativeItem(self::get(self::WOODEN_PRESSURE_PLATE));
        self::addCreativeItem(self::get(self::STONE_PRESSURE_PLATE));
        self::addCreativeItem(self::get(self::LIGHT_WEIGHTED_PRESSURE_PLATE));
        self::addCreativeItem(self::get(self::HEAVY_WEIGHTED_PRESSURE_PLATE));
        self::addCreativeItem(self::get(self::WOODEN_BUTTON, 5));
        self::addCreativeItem(self::get(self::STONE_BUTTON, 5));
        self::addCreativeItem(self::get(self::DAYLIGHT_DETECTOR));
        self::addCreativeItem(self::get(self::TRIPWIRE_HOOK));
        self::addCreativeItem(self::get(self::REDSTONE_REPEATER_ITEM, 0));
        self::addCreativeItem(self::get(self::REDSTONE_COMPARATOR_ITEM, 0));
        self::addCreativeItem(self::get(self::DISPENSER, 3));
        //self::addCreativeItem(self::get(self::DROPPER, 3));
        self::addCreativeItem(self::get(self::HOPPER, 0));

        self::addCreativeItem(self::get(self::SNOWBALL));
    }

    protected static function seedsTab(){
        //Seeds
        /*
         Im gonna make it so you can do:
        self::addCreativeItem(self::get(self::ENCHANTED_BOOK, EnchantedBook::'ENCHANTMENT'));
        */
        self::addCreativeItem(self::get(self::COAL, 0));
        self::addCreativeItem(self::get(self::COAL, 1)); // charcoal
        self::addCreativeItem(self::get(self::DIAMOND, 0));
        self::addCreativeItem(self::get(self::IRON_INGOT, 0));
        self::addCreativeItem(self::get(self::GOLD_INGOT, 0));
        self::addCreativeItem(self::get(self::EMERALD, 0));
        self::addCreativeItem(self::get(self::STICK, 0));
        self::addCreativeItem(self::get(self::BOWL, 0));
        self::addCreativeItem(self::get(self::STRING, 0));
        self::addCreativeItem(self::get(self::FEATHER, 0));
        self::addCreativeItem(self::get(self::FLINT, 0));
        self::addCreativeItem(self::get(self::LEATHER, 0));
        self::addCreativeItem(self::get(self::RABBIT_HIDE, 0));
        self::addCreativeItem(self::get(self::CLAY, 0));
        self::addCreativeItem(self::get(self::SUGAR, 0));
        self::addCreativeItem(self::get(self::NETHER_QUARTZ, 0));
        self::addCreativeItem(self::get(self::PAPER, 0));
        self::addCreativeItem(self::get(self::BOOK, 0));
        self::addCreativeItem(self::get(self::ARROW, 0));
        self::addCreativeItem(self::get(self::BONE, 0));
        self::addCreativeItem(self::get(self::MAP, 0));
        self::addCreativeItem(self::get(self::SUGARCANE, 0));
        self::addCreativeItem(self::get(self::WHEAT, 0));
        self::addCreativeItem(self::get(self::SEEDS, 0));
        self::addCreativeItem(self::get(self::PUMPKIN_SEEDS, 0));
        self::addCreativeItem(self::get(self::MELON_SEEDS, 0));
        self::addCreativeItem(self::get(self::BEETROOT_SEEDS, 0));
        self::addCreativeItem(self::get(self::EGG, 0));
        self::addCreativeItem(self::get(self::APPLE, 0));
        self::addCreativeItem(self::get(self::GOLDEN_APPLE, 0));
        self::addCreativeItem(self::get(self::GOLDEN_APPLE, 1)); // Enchanted golden apple
        self::addCreativeItem(self::get(self::RAW_FISH, 0));
        self::addCreativeItem(self::get(self::RAW_FISH, 1)); // Salmon
        self::addCreativeItem(self::get(self::RAW_FISH, 2)); // Clownfish
        self::addCreativeItem(self::get(self::RAW_FISH, 3)); // Pufferfish
        self::addCreativeItem(self::get(self::COOKED_FISH, 0));
        self::addCreativeItem(self::get(self::COOKED_FISH, 1)); // Salmon
        self::addCreativeItem(self::get(self::ROTTEN_FLESH, 0));
        self::addCreativeItem(self::get(self::MUSHROOM_STEW, 0));
        self::addCreativeItem(self::get(self::BREAD, 0));
        self::addCreativeItem(self::get(self::RAW_PORKCHOP, 0));
        self::addCreativeItem(self::get(self::COOKED_PORKCHOP, 0));
        self::addCreativeItem(self::get(self::RAW_CHICKEN, 0));
        self::addCreativeItem(self::get(self::COOKED_CHICKEN, 0));
        self::addCreativeItem(self::get(self::RAW_BEEF, 0));
        self::addCreativeItem(self::get(self::COOKED_BEEF, 0));
        self::addCreativeItem(self::get(self::MELON, 0));
        self::addCreativeItem(self::get(self::CARROT, 0));
        self::addCreativeItem(self::get(self::POTATO, 0));
        self::addCreativeItem(self::get(self::BAKED_POTATO, 0));
        self::addCreativeItem(self::get(self::POISONOUS_POTATO, 0));
        self::addCreativeItem(self::get(self::BEETROOT, 0));
        self::addCreativeItem(self::get(self::COOKIE, 0));
        self::addCreativeItem(self::get(self::PUMPKIN_PIE, 0));
        self::addCreativeItem(self::get(self::RAW_RABBIT, 0));
        self::addCreativeItem(self::get(self::COOKED_RABBIT, 0));
        self::addCreativeItem(self::get(self::RABBIT_STEW, 0));
        self::addCreativeItem(self::get(self::MAGMA_CREAM, 0));
        self::addCreativeItem(self::get(self::BLAZE_ROD, 0));
        self::addCreativeItem(self::get(self::GOLD_NUGGET, 0));
        self::addCreativeItem(self::get(self::GOLDEN_CARROT, 0));
        self::addCreativeItem(self::get(self::GLISTERING_MELON, 0));
        self::addCreativeItem(self::get(self::RABBIT_FOOT, 0));
        self::addCreativeItem(self::get(self::GHAST_TEAR, 0));
        self::addCreativeItem(self::get(self::SLIMEBALL, 0));
        self::addCreativeItem(self::get(self::BLAZE_POWDER, 0));
        self::addCreativeItem(self::get(self::NETHER_WART, 0));
        self::addCreativeItem(self::get(self::GUNPOWDER, 0));
        self::addCreativeItem(self::get(self::GLOWSTONE_DUST, 0));
        self::addCreativeItem(self::get(self::SPIDER_EYE, 0));
        self::addCreativeItem(self::get(self::FERMENTED_SPIDER_EYE, 0));
        self::addCreativeItem(self::get(self::EXP_BOTTLE, 0));
        // enchanted books

        self::addCreativeItem(self::get(self::DYE, 0));
        self::addCreativeItem(self::get(self::DYE, 8));
        self::addCreativeItem(self::get(self::DYE, 7));
        self::addCreativeItem(self::get(self::DYE, 15));
        self::addCreativeItem(self::get(self::DYE, 12));
        self::addCreativeItem(self::get(self::DYE, 14));
        self::addCreativeItem(self::get(self::DYE, 1));
        self::addCreativeItem(self::get(self::DYE, 4));
        self::addCreativeItem(self::get(self::DYE, 5));
        self::addCreativeItem(self::get(self::DYE, 13));
        self::addCreativeItem(self::get(self::DYE, 9));
        self::addCreativeItem(self::get(self::DYE, 3));
        self::addCreativeItem(self::get(self::DYE, 11));
        self::addCreativeItem(self::get(self::DYE, 10));
        self::addCreativeItem(self::get(self::DYE, 2));
        self::addCreativeItem(self::get(self::DYE, 6));

        self::addCreativeItem(self::get(self::GLASS_BOTTLE, 0));

        self::addCreativeItem(self::get(self::POTION, Potion::WATER_BOTTLE));
        self::addCreativeItem(self::get(self::POTION, Potion::AWKWARD));
        self::addCreativeItem(self::get(self::POTION, Potion::THICK));
        self::addCreativeItem(self::get(self::POTION, Potion::MUNDANE_EXTENDED));
        self::addCreativeItem(self::get(self::POTION, Potion::MUNDANE));
        self::addCreativeItem(self::get(self::POTION, Potion::NIGHT_VISION));
        self::addCreativeItem(self::get(self::POTION, Potion::NIGHT_VISION_T));
        self::addCreativeItem(self::get(self::POTION, Potion::INVISIBILITY));
        self::addCreativeItem(self::get(self::POTION, Potion::INVISIBILITY_T));
        self::addCreativeItem(self::get(self::POTION, Potion::LEAPING));
        self::addCreativeItem(self::get(self::POTION, Potion::LEAPING_T));
        self::addCreativeItem(self::get(self::POTION, Potion::LEAPING_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::FIRE_RESISTANCE));
        self::addCreativeItem(self::get(self::POTION, Potion::FIRE_RESISTANCE_T));
        self::addCreativeItem(self::get(self::POTION, Potion::SPEED));
        self::addCreativeItem(self::get(self::POTION, Potion::SPEED_T));
        self::addCreativeItem(self::get(self::POTION, Potion::SPEED_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::SLOWNESS));
        self::addCreativeItem(self::get(self::POTION, Potion::SLOWNESS_T));
        self::addCreativeItem(self::get(self::POTION, Potion::WATER_BREATHING));
        self::addCreativeItem(self::get(self::POTION, Potion::WATER_BREATHING_T));
        self::addCreativeItem(self::get(self::POTION, Potion::HEALING));
        self::addCreativeItem(self::get(self::POTION, Potion::HEALING_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::HARMING));
        self::addCreativeItem(self::get(self::POTION, Potion::HARMING_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::POISON));
        self::addCreativeItem(self::get(self::POTION, Potion::POISON_T));
        self::addCreativeItem(self::get(self::POTION, Potion::POISON_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::REGENERATION));
        self::addCreativeItem(self::get(self::POTION, Potion::REGENERATION_T));
        self::addCreativeItem(self::get(self::POTION, Potion::REGENERATION_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::STRENGTH));
        self::addCreativeItem(self::get(self::POTION, Potion::STRENGTH_T));
        self::addCreativeItem(self::get(self::POTION, Potion::STRENGTH_TWO));
        self::addCreativeItem(self::get(self::POTION, Potion::WEAKNESS));
        self::addCreativeItem(self::get(self::POTION, Potion::WEAKNESS_T));

        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::WATER_BOTTLE));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::AWKWARD));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::THICK));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::MUNDANE_EXTENDED));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::MUNDANE));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::NIGHT_VISION));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::NIGHT_VISION_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::INVISIBILITY));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::INVISIBILITY_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::LEAPING));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::LEAPING_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::LEAPING_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::FIRE_RESISTANCE));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::FIRE_RESISTANCE_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::SPEED));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::SPEED_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::SPEED_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::SLOWNESS));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::SLOWNESS_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::WATER_BREATHING));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::WATER_BREATHING_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::HEALING));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::HEALING_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::HARMING));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::HARMING_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::POISON));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::POISON_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::POISON_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::REGENERATION));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::REGENERATION_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::REGENERATION_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::STRENGTH));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::STRENGTH_T));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::STRENGTH_TWO));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::WEAKNESS));
        self::addCreativeItem(self::get(self::SPLASH_POTION, Potion::WEAKNESS_T));
    }
}