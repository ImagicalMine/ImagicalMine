<?php
/**
 * src/pocketmine/nbt/NBT.php
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

/**
 * Named Binary Tag handling classes
 */
namespace pocketmine\nbt;

use pocketmine\item\Item;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\Tag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\IntArrayTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\EndTag;
//ifndef COMPILE
use pocketmine\utils\Binary;

//endif


//include <rules/NBT.h>

/**
 * Named Binary Tag encoder/decoder
 */
class NBT
{

    const LITTLE_ENDIAN = 0;
    const BIG_ENDIAN = 1;
    const TAG_End = 0;
    const TAG_Byte = 1;
    const TAG_Short = 2;
    const TAG_Int = 3;
    const TAG_Long = 4;
    const TAG_Float = 5;
    const TAG_Double = 6;
    const TAG_ByteArray = 7;
    const TAG_String = 8;
    const TAG_List = 9;
    const TAG_Compound = 10;
    const TAG_IntArray = 11;

    public $buffer;
    private $offset;
    public $endianness;
    private $data;


    /**
     *
     * @param Item    $item
     * @param int     $slot (optional)
     * @return CompoundTag
     */
    public static function putItemHelper(Item $item, $slot = null)
    {
        $tag = new CompoundTag(null, [
                "id" => new ShortTag("id", $item->getId()),
                "Count" => new ByteTag("Count", $item->getCount()),
                "Damage" => new ShortTag("Damage", $item->getDamage())
            ]);

        if ($slot !== null) {
            $tag->Slot = new ByteTag("Slot", (int) $slot);
        }

        if ($item->hasCompoundTag()) {
            $tag->tag = clone $item->getNamedTag();
            $tag->tag->setName("tag");
        }

        return $tag;
    }


    /**
     *
     * @param CompoundTag $tag
     * @return Item
     */
    public static function getItemHelper(CompoundTag $tag)
    {
        if (!isset($tag->id) or !isset($tag->Count)) {
            return Item::get(0);
        }

        $item = Item::get($tag->id->getValue(), !isset($tag->Damage) ? 0 : $tag->Damage->getValue(), $tag->Count->getValue());

        if (isset($tag->tag) and $tag->tag instanceof CompoundTag) {
            $item->setNamedTag($tag->tag);
        }

        return $item;
    }


    /**
     *
     * @param ListTag $tag1
     * @param ListTag $tag2
     * @return unknown
     */
    public static function matchList(ListTag $tag1, ListTag $tag2)
    {
        if ($tag1->getName() !== $tag2->getName() or $tag1->getCount() !== $tag2->getCount()) {
            return false;
        }

        foreach ($tag1 as $k => $v) {
            if (!($v instanceof Tag)) {
                continue;
            }

            if (!isset($tag2->{$k}) or !($tag2->{$k} instanceof $v)) {
                return false;
            }

            if ($v instanceof CompoundTag) {
                if (!self::matchTree($v, $tag2->{$k})) {
                    return false;
                }
            } elseif ($v instanceof ListTag) {
                if (!self::matchList($v, $tag2->{$k})) {
                    return false;
                }
            } else {
                if ($v->getValue() !== $tag2->{$k}->getValue()) {
                    return false;
                }
            }
        }

        return true;
    }


    /**
     *
     * @param CompoundTag $tag1
     * @param CompoundTag $tag2
     * @return unknown
     */
    public static function matchTree(CompoundTag $tag1, CompoundTag $tag2)
    {
        if ($tag1->getName() !== $tag2->getName() or $tag1->getCount() !== $tag2->getCount()) {
            return false;
        }

        foreach ($tag1 as $k => $v) {
            if (!($v instanceof Tag)) {
                continue;
            }

            if (!isset($tag2->{$k}) or !($tag2->{$k} instanceof $v)) {
                return false;
            }

            if ($v instanceof CompoundTag) {
                if (!self::matchTree($v, $tag2->{$k})) {
                    return false;
                }
            } elseif ($v instanceof ListTag) {
                if (!self::matchList($v, $tag2->{$k})) {
                    return false;
                }
            } else {
                if ($v->getValue() !== $tag2->{$k}->getValue()) {
                    return false;
                }
            }
        }

        return true;
    }


    /**
     *
     * @param unknown $data
     * @param unknown $offset (optional, reference)
     * @return unknown
     */
    public static function parseJSON($data, &$offset = 0)
    {
        $len = strlen($data);
        for (; $offset < $len; ++$offset) {
            $c = $data{$offset};
            if ($c === "{") {
                ++$offset;
                $data = self::parseCompound($data, $offset);
                return new CompoundTag("", $data);
            } elseif ($c !== " " and $c !== "\r" and $c !== "\n" and $c !== "\t") {
                throw new \Exception("Syntax error: unexpected '$c' at offset $offset");
            }
        }

        return null;
    }


    /**
     *
     * @param unknown $str
     * @param unknown $offset (optional, reference)
     * @return unknown
     */
    private static function parseList($str, &$offset = 0)
    {
        $len = strlen($str);


        $key = 0;
        $value = null;

        $data = [];

        for (; $offset < $len; ++$offset) {
            if ($str{$offset - 1} === "]") {
                break;
            } elseif ($str{$offset} === "]") {
                ++$offset;
                break;
            }

            $value = self::readValue($str, $offset, $type);

            switch ($type) {
            case NBT::TAG_Byte:
                $data[$key] = new ByteTag($key, $value);
                break;
            case NBT::TAG_Short:
                $data[$key] = new ShortTag($key, $value);
                break;
            case NBT::TAG_Int:
                $data[$key] = new IntTag($key, $value);
                break;
            case NBT::TAG_Long:
                $data[$key] = new LongTag($key, $value);
                break;
            case NBT::TAG_Float:
                $data[$key] = new FloatTag($key, $value);
                break;
            case NBT::TAG_Double:
                $data[$key] = new DoubleTag($key, $value);
                break;
            case NBT::TAG_ByteArray:
                $data[$key] = new ByteArrayTag($key, $value);
                break;
            case NBT::TAG_String:
                $data[$key] = new ByteTag($key, $value);
                break;
            case NBT::TAG_List:
                $data[$key] = new ListTag($key, $value);
                break;
            case NBT::TAG_Compound:
                $data[$key] = new CompoundTag($key, $value);
                break;
            case NBT::TAG_IntArray:
                $data[$key] = new IntArrayTag($key, $value);
                break;
            }

            $key++;
        }

        return $data;
    }


    /**
     *
     * @param unknown $str
     * @param unknown $offset (optional, reference)
     * @return unknown
     */
    private static function parseCompound($str, &$offset = 0)
    {
        $len = strlen($str);

        $data = [];

        for (; $offset < $len; ++$offset) {
            if ($str{$offset - 1} === "}") {
                break;
            } elseif ($str{$offset} === "}") {
                ++$offset;
                break;
            }

            $key = self::readKey($str, $offset);
            $value = self::readValue($str, $offset, $type);

            switch ($type) {
            case NBT::TAG_Byte:
                $data[$key] = new ByteTag($key, $value);
                break;
            case NBT::TAG_Short:
                $data[$key] = new ShortTag($key, $value);
                break;
            case NBT::TAG_Int:
                $data[$key] = new IntTag($key, $value);
                break;
            case NBT::TAG_Long:
                $data[$key] = new LongTag($key, $value);
                break;
            case NBT::TAG_Float:
                $data[$key] = new FloatTag($key, $value);
                break;
            case NBT::TAG_Double:
                $data[$key] = new DoubleTag($key, $value);
                break;
            case NBT::TAG_ByteArray:
                $data[$key] = new ByteArrayTag($key, $value);
                break;
            case NBT::TAG_String:
                $data[$key] = new StringTag($key, $value);
                break;
            case NBT::TAG_List:
                $data[$key] = new ListTag($key, $value);
                break;
            case NBT::TAG_Compound:
                $data[$key] = new CompoundTag($key, $value);
                break;
            case NBT::TAG_IntArray:
                $data[$key] = new IntArrayTag($key, $value);
                break;
            }
        }

        return $data;
    }


    /**
     *
     * @param unknown $data
     * @param unknown $offset (reference)
     * @param unknown $type   (optional, reference)
     * @return unknown
     */
    private static function readValue($data, &$offset, &$type = null)
    {
        $value = "";
        $type = null;
        $inQuotes = false;

        $len = strlen($data);
        for (; $offset < $len; ++$offset) {
            $c = $data{$offset};

            if (!$inQuotes and ($c === " " or $c === "\r" or $c === "\n" or $c === "\t" or $c === "," or $c === "}" or $c === "]")) {
                if ($c === "," or $c === "}" or $c === "]") {
                    break;
                }
            } elseif ($c === '"') {
                $inQuotes = !$inQuotes;
                if ($type === null) {
                    $type = self::TAG_String;
                } elseif ($inQuotes) {
                    throw new \Exception("Syntax error: invalid quote at offset $offset");
                }
            } elseif ($c === "\\") {
                $value .= isset($data{$offset + 1}) ? $data{$offset + 1}
                : "";
                ++$offset;
            } elseif ($c === "{" and !$inQuotes) {
                if ($value !== "") {
                    throw new \Exception("Syntax error: invalid compound start at offset $offset");
                }
                ++$offset;
                $value = self::parseCompound($data, $offset);
                $type = self::TAG_Compound;
                break;
            } elseif ($c === "[" and !$inQuotes) {
                if ($value !== "") {
                    throw new \Exception("Syntax error: invalid list start at offset $offset");
                }
                ++$offset;
                $value = self::parseList($data, $offset);
                $type = self::TAG_List;
                break;
            } else {
                $value .= $c;
            }
        }

        if ($value === "") {
            throw new \Exception("Syntax error: invalid empty value at offset $offset");
        }

        if ($type === null and strlen($value) > 0) {
            $value = trim($value);
            $last = strtolower(substr($value, -1));
            $part = substr($value, 0, -1);

            if ($last !== "b" and $last !== "s" and $last !== "l" and $last !== "f" and $last !== "d") {
                $part = $value;
                $last = null;
            }

            if ($last !== "f" and $last !== "d" and ((string) ((int) $part)) === $part) {
                if ($last === "b") {
                    $type = self::TAG_Byte;
                } elseif ($last === "s") {
                    $type = self::TAG_Short;
                } elseif ($last === "l") {
                    $type = self::TAG_Long;
                } else {
                    $type = self::TAG_Int;
                }
                $value = (int) $part;
            } elseif (is_numeric($part)) {
                if ($last === "f" or $last === "d" or strpos($part, ".") !== false) {
                    if ($last === "f") {
                        $type = self::TAG_Float;
                    } elseif ($last === "d") {
                        $type = self::TAG_Double;
                    } else {
                        $type = self::TAG_Float;
                    }
                    $value = (float) $part;
                } else {
                    if ($last === "l") {
                        $type = self::TAG_Long;
                    } else {
                        $type = self::TAG_Int;
                    }

                    $value = $part;
                }
            } else {
                $type = self::TAG_String;
            }
        }

        return $value;
    }


    /**
     *
     * @param unknown $data
     * @param unknown $offset (reference)
     * @return unknown
     */
    private static function readKey($data, &$offset)
    {
        $key = "";

        $len = strlen($data);
        for (; $offset < $len; ++$offset) {
            $c = $data{$offset};

            if ($c === ":") {
                ++$offset;
                break;
            } elseif ($c !== " " and $c !== "\r" and $c !== "\n" and $c !== "\t") {
                $key .= $c;
            }
        }

        if ($key === "") {
            throw new \Exception("Syntax error: invalid empty key at offset $offset");
        }

        return $key;
    }


    /**
     *
     * @param unknown $len
     * @return unknown
     */
    public function get($len)
    {
        if ($len < 0) {
            $this->offset = strlen($this->buffer) - 1;
            return "";
        } elseif ($len === true) {
            return substr($this->buffer, $this->offset);
        }

        return $len === 1 ? $this->buffer{$this->offset++}
        : substr($this->buffer, ($this->offset += $len) - $len, $len);
    }


    /**
     *
     * @param unknown $v
     */
    public function put($v)
    {
        $this->buffer .= $v;
    }


    /**
     *
     * @return unknown
     */
    public function feof()
    {
        return !isset($this->buffer{$this->offset});
    }


    /**
     *
     * @param unknown $endianness (optional)
     */
    public function __construct($endianness = self::LITTLE_ENDIAN)
    {
        $this->offset = 0;
        $this->endianness = $endianness & 0x01;
    }


    /**
     *
     * @param unknown $buffer
     * @param unknown $doMultiple (optional)
     */
    public function read($buffer, $doMultiple = false)
    {
        $this->offset = 0;
        $this->buffer = $buffer;
        $this->data = $this->readTag();
        if ($doMultiple and $this->offset < strlen($this->buffer)) {
            $this->data = [$this->data];
            do {
                $this->data[] = $this->readTag();
            } while ($this->offset < strlen($this->buffer));
        }
        $this->buffer = "";
    }


    /**
     *
     * @param unknown $buffer
     * @param unknown $compression (optional)
     */
    public function readCompressed($buffer, $compression = ZLIB_ENCODING_GZIP)
    {
        $this->read(zlib_decode($buffer));
    }


    /**
     *
     * @return string|bool
     */
    public function write()
    {
        $this->offset = 0;
        $this->buffer = "";

        if ($this->data instanceof CompoundTag) {
            $this->writeTag($this->data);

            return $this->buffer;
        } elseif (is_array($this->data)) {
            foreach ($this->data as $tag) {
                $this->writeTag($tag);
            }
            return $this->buffer;
        }

        return false;
    }


    /**
     *
     * @param unknown $compression (optional)
     * @param unknown $level       (optional)
     * @return unknown
     */
    public function writeCompressed($compression = ZLIB_ENCODING_GZIP, $level = 7)
    {
        if (($write = $this->write()) !== false) {
            return zlib_encode($write, $compression, $level);
        }

        return false;
    }


    /**
     *
     * @return unknown
     */
    public function readTag()
    {
        switch ($this->getByte()) {
        case NBT::TAG_Byte:
            $tag = new ByteTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_Short:
            $tag = new ShortTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_Int:
            $tag = new IntTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_Long:
            $tag = new LongTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_Float:
            $tag = new FloatTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_Double:
            $tag = new DoubleTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_ByteArray:
            $tag = new ByteArrayTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_String:
            $tag = new StringTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_List:
            $tag = new ListTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_Compound:
            $tag = new CompoundTag($this->getString());
            $tag->read($this);
            break;
        case NBT::TAG_IntArray:
            $tag = new IntArrayTag($this->getString());
            $tag->read($this);
            break;

        case NBT::TAG_End: //No named tag
        default:
            $tag = new EndTag();
            break;
        }
        return $tag;
    }


    /**
     *
     * @param Tag     $tag
     */
    public function writeTag(Tag $tag)
    {
        $this->putByte($tag->getType());
        if ($tag instanceof NamedTag) {
            $this->putString($tag->getName());
        }
        $tag->write($this);
    }


    /**
     *
     * @return unknown
     */
    public function getByte()
    {
        return Binary::readByte($this->get(1));
    }


    /**
     *
     * @param unknown $v
     */
    public function putByte($v)
    {
        $this->buffer .= Binary::writeByte($v);
    }


    /**
     *
     * @return unknown
     */
    public function getShort()
    {
        return $this->endianness === self::BIG_ENDIAN ? Binary::readShort($this->get(2)) : Binary::readLShort($this->get(2));
    }


    /**
     *
     * @param unknown $v
     */
    public function putShort($v)
    {
        $this->buffer .= $this->endianness === self::BIG_ENDIAN ? Binary::writeShort($v) : Binary::writeLShort($v);
    }


    /**
     *
     * @return unknown
     */
    public function getInt()
    {
        return $this->endianness === self::BIG_ENDIAN ? Binary::readInt($this->get(4)) : Binary::readLInt($this->get(4));
    }


    /**
     *
     * @param unknown $v
     */
    public function putInt($v)
    {
        $this->buffer .= $this->endianness === self::BIG_ENDIAN ? Binary::writeInt($v) : Binary::writeLInt($v);
    }


    /**
     *
     * @return unknown
     */
    public function getLong()
    {
        return $this->endianness === self::BIG_ENDIAN ? Binary::readLong($this->get(8)) : Binary::readLLong($this->get(8));
    }


    /**
     *
     * @param unknown $v
     */
    public function putLong($v)
    {
        $this->buffer .= $this->endianness === self::BIG_ENDIAN ? Binary::writeLong($v) : Binary::writeLLong($v);
    }


    /**
     *
     * @return unknown
     */
    public function getFloat()
    {
        return $this->endianness === self::BIG_ENDIAN ? Binary::readFloat($this->get(4)) : Binary::readLFloat($this->get(4));
    }


    /**
     *
     * @param unknown $v
     */
    public function putFloat($v)
    {
        $this->buffer .= $this->endianness === self::BIG_ENDIAN ? Binary::writeFloat($v) : Binary::writeLFloat($v);
    }


    /**
     *
     * @return unknown
     */
    public function getDouble()
    {
        return $this->endianness === self::BIG_ENDIAN ? Binary::readDouble($this->get(8)) : Binary::readLDouble($this->get(8));
    }


    /**
     *
     * @param unknown $v
     */
    public function putDouble($v)
    {
        $this->buffer .= $this->endianness === self::BIG_ENDIAN ? Binary::writeDouble($v) : Binary::writeLDouble($v);
    }


    /**
     *
     * @return unknown
     */
    public function getString()
    {
        return $this->get($this->getShort());
    }


    /**
     *
     * @param unknown $v
     */
    public function putString($v)
    {
        $this->putShort(strlen($v));
        $this->buffer .= $v;
    }


    /**
     *
     */
    public function getArray()
    {
        $data = [];
        self::toArray($data, $this->data);
    }


    /**
     *
     * @param array   $data (reference)
     * @param Tag     $tag
     */
    private static function toArray(array &$data, Tag $tag)
    {
        /** @var CompoundTag[]|ListTag[]|IntArrayTag[] $tag */
        foreach ($tag as $key => $value) {
            if ($value instanceof CompoundTag or $value instanceof ListTag or $value instanceof IntArrayTag) {
                $data[$key] = [];
                self::toArray($data[$key], $value);
            } else {
                $data[$key] = $value->getValue();
            }
        }
    }


    /**
     *
     * @param unknown $key
     * @param unknown $value
     * @return unknown
     */
    public static function fromArrayGuesser($key, $value)
    {
        if (is_int($value)) {
            return new IntTag($key, $value);
        } elseif (is_float($value)) {
            return new FloatTag($key, $value);
        } elseif (is_string($value)) {
            return new StringTag($key, $value);
        } elseif (is_bool($value)) {
            return new ByteTag($key, $value ? 1 : 0);
        }

        return null;
    }


    /**
     *
     * @param Tag     $tag
     * @param array   $data
     * @param unknown $guesser
     */
    private static function fromArray(Tag $tag, array $data, callable $guesser)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $isNumeric = true;
                $isIntArray = true;
                foreach ($value as $k => $v) {
                    if (!is_numeric($k)) {
                        $isNumeric = false;
                        break;
                    } elseif (!is_int($v)) {
                        $isIntArray = false;
                    }
                }
                $tag{$key}
                = $isNumeric ? ($isIntArray ? new IntArrayTag($key, []) : new ListTag($key, [])) : new CompoundTag($key, []);
                self::fromArray($tag->{$key}, $value, $guesser);
            } else {
                $v = call_user_func($guesser, $key, $value);
                if ($v instanceof Tag) {
                    $tag{$key}
                    = $v;
                }
            }
        }
    }


    /**
     *
     * @param array   $data
     * @param unknown $guesser (optional)
     */
    public function setArray(array $data, callable $guesser = null)
    {
        $this->data = new CompoundTag("", []);
        self::fromArray($this->data, $data, $guesser === null ? [self::class, "fromArrayGuesser"] : $guesser);
    }


    /**
     *
     * @return CompoundTag|array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     *
     * @param CompoundTag|array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
