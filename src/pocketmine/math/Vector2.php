<?php
/**
 * src/pocketmine/math/Vector2.php
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
 * ImagicalMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

namespace pocketmine\math;

/**
 * WARNING: This class is available on the ImagicalMine Zephir project.
 * If this class is modified, remember to modify the PHP C extension.
 */
class Vector2 {
	public $x;
	public $y;

	/**
	 *
	 * @param unknown $x (optional)
	 * @param unknown $y (optional)
	 */
	public function __construct($x = 0, $y = 0) {
		$this->x = $x;
		$this->y = $y;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getX() {
		return $this->x;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getY() {
		return $this->y;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getFloorX() {
		return (int) $this->x;
	}


	/**
	 *
	 * @return unknown
	 */
	public function getFloorY() {
		return (int) $this->y;
	}


	/**
	 *
	 * @param unknown $x
	 * @param unknown $y (optional)
	 * @return unknown
	 */
	public function add($x, $y = 0) {
		if ($x instanceof Vector2) {
			return $this->add($x->x, $x->y);
		}else {
			return new Vector2($this->x + $x, $this->y + $y);
		}
	}


	/**
	 *
	 * @param unknown $x
	 * @param unknown $y (optional)
	 * @return unknown
	 */
	public function subtract($x, $y = 0) {
		if ($x instanceof Vector2) {
			return $this->add(-$x->x, -$x->y);
		}else {
			return $this->add(-$x, -$y);
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function ceil() {
		return new Vector2((int) ($this->x + 1), (int) ($this->y + 1));
	}


	/**
	 *
	 * @return unknown
	 */
	public function floor() {
		return new Vector2((int) $this->x, (int) $this->y);
	}


	/**
	 *
	 * @return unknown
	 */
	public function round() {
		return new Vector2(round($this->x), round($this->y));
	}


	/**
	 *
	 * @return unknown
	 */
	public function abs() {
		return new Vector2(abs($this->x), abs($this->y));
	}


	/**
	 *
	 * @param unknown $number
	 * @return unknown
	 */
	public function multiply($number) {
		return new Vector2($this->x * $number, $this->y * $number);
	}


	/**
	 *
	 * @param unknown $number
	 * @return unknown
	 */
	public function divide($number) {
		return new Vector2($this->x / $number, $this->y / $number);
	}


	/**
	 *
	 * @param unknown $x
	 * @param unknown $y (optional)
	 * @return unknown
	 */
	public function distance($x, $y = 0) {
		if ($x instanceof Vector2) {
			return sqrt($this->distanceSquared($x->x, $x->y));
		}else {
			return sqrt($this->distanceSquared($x, $y));
		}
	}


	/**
	 *
	 * @param unknown $x
	 * @param unknown $y (optional)
	 * @return unknown
	 */
	public function distanceSquared($x, $y = 0) {
		if ($x instanceof Vector2) {
			return $this->distanceSquared($x->x, $x->y);
		}else {
			return pow($this->x - $x, 2) + pow($this->y - $y, 2);
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function length() {
		return sqrt($this->lengthSquared());
	}


	/**
	 *
	 * @return unknown
	 */
	public function lengthSquared() {
		return $this->x * $this->x + $this->y * $this->y;
	}


	/**
	 *
	 * @return unknown
	 */
	public function normalize() {
		$len = $this->lengthSquared();
		if ($len != 0) {
			return $this->divide(sqrt($len));
		}

		return new Vector2(0, 0);
	}


	/**
	 *
	 * @param Vector2 $v
	 * @return unknown
	 */
	public function dot(Vector2 $v) {
		return $this->x * $v->x + $this->y * $v->y;
	}


	/**
	 *
	 * @return unknown
	 */
	public function __toString() {
		return "Vector2(x=" . $this->x . ",y=" . $this->y . ")";
	}


}
