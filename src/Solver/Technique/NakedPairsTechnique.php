<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class NakedPairsTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. find pairs in hors / vers
		// 2. ensure the lines exactly match
		// 3. remove variations from other elements

		$i = 3;
		for($i = 0; $i < $grid->getSize(); $i++) {
			$cells = $grid->getVerCells($i);
			$this->runPackAnalysis($cells, $grid);

			$cells = $grid->getHorCells($i);
			$this->runPackAnalysis($cells, $grid);
		}
	}

	public function runPackAnalysis(array $cells, Grid $grid)
	{
		$cellsPairVariations = array();
		foreach($cells as $cell) {
			if (count($cell->getVariations()) === 2) {
				$cellsPairVariations[] = $cell->getVariations();
			}
		}
		$uniqueCellsPairVariations = array_unique($cellsPairVariations);

		foreach($uniqueCellsPairVariations as $pairVariations) {
			$pairVariationCells = [];
			foreach($cells as $cell) {
				if ($pairVariations === $cell->getVariations()) {
					$pairVariationCells[] = $cell;
				}
			}
			if (count($pairVariationCells) === 2) {
				// remove variations from other cells
				$otherCells = $grid->filterCells($cells, $pairVariationCells);
				foreach($otherCells as $otherCell) {
					foreach($pairVariations as $pairVariation) {
						$otherCell->removeVariation($pairVariation);
					}
				}
			}
		}
	}
}
