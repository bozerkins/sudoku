<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class CandidateLinesTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. take a row or a column
		// 2. get some variation numbers
		// 3. check that numbers exists only within the row
		// 4. clear other blocks for the same variations
		for($i = 0; $i < $grid->getSize(); $i++) {
			foreach($grid->getBlockHors($i) as $hor) {
				$blockLineCells = $grid->getBlockHorCells($i, $hor);
				$blockCells = $grid->getBlockCells($i);
				$lineCells = $grid->getHorCells($hor);
				$this->runPackAnalysis($blockLineCells, $blockCells, $lineCells, $grid);
			}
			foreach($grid->getBlockVers($i) as $ver) {
				$blockLineCells = $grid->getBlockVerCells($i, $ver);
				$blockCells = $grid->getBlockCells($i);
				$lineCells = $grid->getVerCells($ver);
				$this->runPackAnalysis($blockLineCells, $blockCells, $lineCells, $grid);
			}
		}
	}

	public function runPackAnalysis(array $blockLineCells, array $blockCells, array $lineCells, Grid $grid)
	{

		$blockLineVariations = array_reduce($blockLineCells, function($result, $cell){
			$result = array_merge($result, $cell->getVariations());
			return array_unique($result);
		}, array());

		$otherBlockCells = $grid->filterCells($blockCells, $blockLineCells);
		$otherBlockVariations = array_reduce($otherBlockCells, function($result, $cell){
			$result = array_merge($result, $cell->getVariations());
			return array_unique($result);
		}, array());
		$variationsDiff = array_diff($blockLineVariations, $otherBlockVariations);

		$otherLineCells = $grid->filterCells($lineCells, $blockLineCells);
		foreach($otherLineCells as $cell) {
			foreach($variationsDiff as $variation) {
				$cell->removeVariation($variation);
			}
		}

	}
}
