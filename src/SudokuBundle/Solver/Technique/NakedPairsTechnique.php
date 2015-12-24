<?php

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\Grid\Grid;

class NakedPairsTechnique extends Technique
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. find pairs in hors / vers
		// 2. ensure the lines exactly match
		// 3. remove variations from other elements

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
		$uniqueCellsPairVariations = array_reduce($cellsPairVariations, function($result, $pair) {
			$exists = false;
			foreach($result as $resultPair) {
				if (count(array_diff($resultPair, $pair)) === 0) {
					$exists = true;
				}
			}

			if (!$exists) {
				$result[] = $pair;
			}
			return $result;
		}, array());


		foreach($uniqueCellsPairVariations as $pairVariations) {
			$pairVariationCells = array();
			foreach($cells as $cell) {
				if (count(array_diff($pairVariations, $cell->getVariations())) === 0 && count(array_diff($cell->getVariations(), $pairVariations)) === 0) {
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
