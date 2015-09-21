<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class SinglePositionTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		$values = array();

		foreach($grid->getEmptyCells() as $cell) {
			$variations = $cell->getVariations();
			if (count($variations) === 1) {
				$variation = array_pop(array_values($variations));
				$cell->set($variation);

				$value = array();
				$value['cell'] = $cell;
				$value['variation'] = $cell->get();
				$values[] = $value;
			}
		}

		foreach($values as $value) {
			$grid->setCellValue($value['cell'], $value['variation']);
		}
	}
}
