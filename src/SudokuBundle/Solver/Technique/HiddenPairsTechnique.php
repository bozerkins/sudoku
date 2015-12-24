<?php

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\Grid\Grid;

class HiddenPairsTechnique extends Technique
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
		$variations = array_values(array_reduce($cells, function($result, $cell) {
			$result = array_unique(array_merge($result, $cell->getVariations()));
			return $result;
		}, array()));

		// form variation pairs
		$pairs = array();
		// for each variation
		for($a = 0; $a < count($variations); $a++) {
			// match with not yet matched variations
			for($b = $a + 1; $b < count($variations); $b++) {
				// record a pair
				$pairs[] = array($variations[$a], $variations[$b]);
			}
		}

		// check each pair if it's hidden
		foreach($pairs as $pair) {
			$matchCells = array();
			$partialCells = array();

			foreach($cells as $cell) {
				$diffCount = count(array_diff($pair, $cell->getVariations()));

				if (!$diffCount) {
					$matchCells[] = $cell;
				} else if ($diffCount < count($pair)) {
					$partialCells[] = $cell;
				}
			}
			// this pair is hidden
			if (count($matchCells) === 2 && count($partialCells) === 0) {
				foreach($matchCells as $matchCell) {
					$otherVariations = array_diff($matchCell->getVariations(), $pair);
					foreach($otherVariations as $otherVarition) {
						$matchCell->removeVariation($otherVarition);
					}
				}
			}
		}
	}
}
