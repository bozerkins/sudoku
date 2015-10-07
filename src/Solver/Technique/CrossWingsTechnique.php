<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class CrossWingsTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. find pairs in hors / vers
		// 2. ensure the lines exactly match
		// 3. remove variations from other elements


	}

	public function runPackAnalysis(array $cells, Grid $grid)
	{

	}
}
