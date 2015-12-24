<?php

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\Grid\Grid;

abstract class TechniqueAbstract
{
    abstract public function analyze(Grid $grid);
}