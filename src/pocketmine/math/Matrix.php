<?php
/**
 * src/pocketmine/math/Matrix.php
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

namespace pocketmine\math;

class Matrix implements \ArrayAccess
{
    private $matrix = [];
    private $rows = 0;
    private $columns = 0;

    /**
     *
     * @param unknown $offset
     * @return unknown
     */
    public function offsetExists($offset)
    {
        return isset($this->matrix[(int) $offset]);
    }


    /**
     *
     * @param unknown $offset
     * @return unknown
     */
    public function offsetGet($offset)
    {
        return $this->matrix[(int) $offset];
    }


    /**
     *
     * @param unknown $offset
     * @param unknown $value
     */
    public function offsetSet($offset, $value)
    {
        $this->matrix[(int) $offset] = $value;
    }


    /**
     *
     * @param unknown $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->matrix[(int) $offset]);
    }


    /**
     *
     * @param unknown $rows
     * @param unknown $columns
     * @param array   $set     (optional)
     */
    public function __construct($rows, $columns, array $set = [])
    {
        $this->rows = max(1, (int) $rows);
        $this->columns = max(1, (int) $columns);
        $this->set($set);
    }


    /**
     *
     * @param array   $m
     */
    public function set(array $m)
    {
        for ($r = 0; $r < $this->rows; ++$r) {
            $this->matrix[$r] = [];
            for ($c = 0; $c < $this->columns; ++$c) {
                $this->matrix[$r][$c] = isset($m[$r][$c]) ? $m[$r][$c] : 0;
            }
        }
    }


    /**
     *
     * @return unknown
     */
    public function getRows()
    {
        return $this->rows;
    }


    /**
     *
     * @return unknown
     */
    public function getColumns()
    {
        return $this->columns;
    }


    /**
     *
     * @param unknown $row
     * @param unknown $column
     * @param unknown $value
     * @return unknown
     */
    public function setElement($row, $column, $value)
    {
        if ($row > $this->rows or $row < 0 or $column > $this->columns or $column < 0) {
            return false;
        }
        $this->matrix[(int) $row][(int) $column] = $value;

        return true;
    }


    /**
     *
     * @param unknown $row
     * @param unknown $column
     * @return unknown
     */
    public function getElement($row, $column)
    {
        if ($row > $this->rows or $row < 0 or $column > $this->columns or $column < 0) {
            return false;
        }

        return $this->matrix[(int) $row][(int) $column];
    }


    /**
     *
     * @return unknown
     */
    public function isSquare()
    {
        return $this->rows === $this->columns;
    }


    /**
     *
     * @param Matrix  $matrix
     * @return unknown
     */
    public function add(Matrix $matrix)
    {
        if ($this->rows !== $matrix->getRows() or $this->columns !== $matrix->getColumns()) {
            return false;
        }
        $result = new Matrix($this->rows, $this->columns);
        for ($r = 0; $r < $this->rows; ++$r) {
            for ($c = 0; $c < $this->columns; ++$c) {
                $result->setElement($r, $c, $this->matrix[$r][$c] + $matrix->getElement($r, $c));
            }
        }

        return $result;
    }


    /**
     *
     * @param Matrix  $matrix
     * @return unknown
     */
    public function substract(Matrix $matrix)
    {
        if ($this->rows !== $matrix->getRows() or $this->columns !== $matrix->getColumns()) {
            return false;
        }
        $result = clone $this;
        for ($r = 0; $r < $this->rows; ++$r) {
            for ($c = 0; $c < $this->columns; ++$c) {
                $result->setElement($r, $c, $this->matrix[$r][$c] - $matrix->getElement($r, $c));
            }
        }

        return $result;
    }


    /**
     *
     * @param unknown $number
     * @return unknown
     */
    public function multiplyScalar($number)
    {
        $result = clone $this;
        for ($r = 0; $r < $this->rows; ++$r) {
            for ($c = 0; $c < $this->columns; ++$c) {
                $result->setElement($r, $c, $this->matrix[$r][$c] * $number);
            }
        }

        return $result;
    }


    /**
     *
     * @param unknown $number
     * @return unknown
     */
    public function divideScalar($number)
    {
        $result = clone $this;
        for ($r = 0; $r < $this->rows; ++$r) {
            for ($c = 0; $c < $this->columns; ++$c) {
                $result->setElement($r, $c, $this->matrix[$r][$c] / $number);
            }
        }

        return $result;
    }


    /**
     *
     * @return unknown
     */
    public function transpose()
    {
        $result = new Matrix($this->columns, $this->rows);
        for ($r = 0; $r < $this->rows; ++$r) {
            for ($c = 0; $c < $this->columns; ++$c) {
                $result->setElement($c, $r, $this->matrix[$r][$c]);
            }
        }

        return $result;
    }


    /**
     * Naive Matrix product, O(n^3)
     *
     * @param Matrix  $matrix
     * @return unknown
     */
    public function product(Matrix $matrix)
    {
        if ($this->columns !== $matrix->getRows()) {
            return false;
        }
        $c = $matrix->getColumns();
        $result = new Matrix($this->rows, $c);
        for ($i = 0; $i < $this->rows; ++$i) {
            for ($j = 0; $j < $c; ++$j) {
                $sum = 0;
                for ($k = 0; $k < $this->columns; ++$k) {
                    $sum += $this->matrix[$i][$k] * $matrix->getElement($k, $j);
                }
                $result->setElement($i, $j, $sum);
            }
        }

        return $result;
    }


    /**
     * Computation of the determinant of 2x2 and 3x3 matrices
     *
     * @return unknown
     */
    public function determinant()
    {
        if ($this->isSquare() !== true) {
            return false;
        }
        switch ($this->rows) {
        case 1:
            return 0;
        case 2:
            return $this->matrix[0][0] * $this->matrix[1][1] - $this->matrix[0][1] * $this->matrix[1][0];
        case 3:
            return $this->matrix[0][0] * $this->matrix[1][1] * $this->matrix[2][2] + $this->matrix[0][1] * $this->matrix[1][2] * $this->matrix[2][0] + $this->matrix[0][2] * $this->matrix[1][0] * $this->matrix[2][1] - $this->matrix[2][0] * $this->matrix[1][1] * $this->matrix[0][2] - $this->matrix[2][1] * $this->matrix[1][2] * $this->matrix[0][0] - $this->matrix[2][2] * $this->matrix[1][0] * $this->matrix[0][1];
        }

        return false;
    }


    /**
     *
     * @return unknown
     */
    public function __toString()
    {
        $s = "";
        for ($r = 0; $r < $this->rows; ++$r) {
            $s .= implode(",", $this->matrix[$r]) . ";";
        }

        return "Matrix({$this->rows}x{$this->columns};" . substr($s, 0, -1) . ")";
    }
}
