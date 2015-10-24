<?php

namespace Sudoku\Grid;

interface CellModificationInterface
{
    public function set($value);

    public function clear();

    public function addVariation($value);

    public function removeVariation($value);

    public function clearVariations();
}