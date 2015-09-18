<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

interface TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid);
}
