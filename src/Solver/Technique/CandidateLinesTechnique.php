<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class CandidateLinesTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// for($i = 0; $i < $grid->getSize(); $i++) {
		$i = 8;
			foreach($grid->getBlockHors($i) as $hor) {
				$blockHorCells = $grid->getBlockHorCells($i, $hor);
				$horCells = $grid->getHorCells($hor);
				$this->runPackAnalysis($blockHorCells, $horCells, $grid);
			}
			foreach($grid->getBlockVers($i) as $hor) {
				$blockHorCells = $grid->getBlockVerCells($i, $hor);
				$horCells = $grid->getVerCells($hor);
				$this->runPackAnalysis($blockHorCells, $horCells, $grid);
			}
		// }
	}

	public function runPackAnalysis(array $definitiveCells, array $packCells, Grid $grid)
	{
		$blockHorEmptyCells = $grid->filterEmptyCells($definitiveCells);
		if (count($blockHorEmptyCells) > 1) {
			$blockHorCellsVariations = array_map(function($cell) {
				return $cell->getVariations();
			}, $blockHorEmptyCells);

			$blockHorCellsUniqueVariations = array_values(array_unique($blockHorCellsVariations));

			if (count($blockHorCellsUniqueVariations) === 1) {
				$blockHorCellsUniqueVariations = array_pop($blockHorCellsUniqueVariations);
				if (count($blockHorEmptyCells) === count($blockHorCellsUniqueVariations)) {
					$horCellsLeft = $grid->filterCells($packCells, $definitiveCells);
					foreach($horCellsLeft as $horCellLeft) {
						foreach($blockHorCellsUniqueVariations as $blockHorCellsUniqueVariation) {
							$horCellLeft->removeVariation($blockHorCellsUniqueVariation);
						}
					}
				}
			}

		}
	}
}
