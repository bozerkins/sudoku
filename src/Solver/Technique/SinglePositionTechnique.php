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
				$value['hor'] = $cell->getHor();
				$value['ver'] = $cell->getVer();
				$value['variation'] = $cell->get();
				$values[] = $value;
			}
		}

		foreach($values as $value) {
			foreach($grid->getHorCells($value['hor']) as $cell) {
				$cell->removeVariation($value['variation']);
			}

			foreach($grid->getVerCells($value['ver']) as $cell) {
				$cell->removeVariation($value['variation']);
			}

			foreach($grid->getBlockCells($grid->getBlockNumber($value['hor'], $value['ver'])) as $cell) {
				$cell->removeVariation($value['variation']);
			}
		}
	}
}
