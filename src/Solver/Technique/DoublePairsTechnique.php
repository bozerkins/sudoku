<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class DoublePairsTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. find repeating elements in a block line
		// 2. verify elements that are not repeated in other block lines
		// 3. verify elements are not repeated in the same grid line
		// 4. remove elements from variations in other block lines of the same grid line

		$i = 7;

		$cells = $grid->getBlockCells(7);
		// echo '<pre>'; print_r($cells); echo '</pre>';exit;

		foreach($grid->getBlockVers($i) as $ver) {
			$cells = $grid->getBlockVerCells($i, $ver);
			$variations = array_reduce($cells, function($result, $cell){
				$result = array_merge($result, $cell->getVariations());
				return array_unique($result);
			}, array());
			$sameLineCells = $grid->filterCells($grid->getVerCells($ver), $cells);
			$sameLineVariations = array_reduce($sameLineCells, function($result, $cell){
				$result = array_merge($result, $cell->getVariations());
				return array_unique($result);
			}, array());

			$candidateVariations = array_diff($variations, $sameLineVariations);

			if (!$candidateVariations) {
				continue;
			}

			$otherVers = array_filter($grid->getBlockVers($i), function($item) use ($ver) { return $item !== $ver; });
			$otherCells = array();
			foreach($otherVers as $otherVer) {
				$otherCells = array_merge($otherCells, $grid->filterCells($grid->getVerCells($otherVer), $grid->getBlockVerCells($i, $otherVer)));
			}
			$otherVariations = array_reduce($otherCells, function($result, $cell){
				$result = array_merge($result, $cell->getVariations());
				return array_unique($result);
			}, array());

			$diffVariations = array_diff($candidateVariations, $otherVariations);

			if (!$diffVariations) {
				// remove these variations from same block cell variations
			}
		}
	}

	public function runPackAnalysis(array $blockLineCells, array $blockCells, array $lineCells, Grid $grid)
	{

	}
}
