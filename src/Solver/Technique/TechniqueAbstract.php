<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

abstract class TechniqueAbstract
{
    abstract public function analyze(Grid $grid);
}