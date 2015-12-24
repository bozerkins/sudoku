<?php

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\EvaluatorStep;
use SudokuBundle\Grid\Grid;

class SinglePositionTechnique extends Technique
{
	public function fillWhatYouCan(Grid $grid)
	{
		$values = array();
		$cells = $grid->getEmptyCells();

		$logger = $this->getLogger();
		$logger->write('cells generated: ' . count($cells));

		foreach($cells as $cell) {
			$logger->write('investigating cell: ' . (string) $cell);

			$variations = $cell->getVariations();
			if (count($variations) === 1) {
				$logger->write('found only one variation');

				$variation = array_pop(array_values($variations));
				$cell->set($variation);

				$logger->write('saving change to cell value as: ' . $variation);

				$value = array();
				$value['cell'] = $cell;
				$value['variation'] = $cell->get();
				$values[] = $value;
			}
		}

		$logger->write('changing the values');

		foreach($values as $value) {
			$grid->setCellValue($value['cell'], $value['variation']);
		}
	}
}
