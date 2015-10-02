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

		for($i = 0; $i < $grid->getSize(); $i++) {
			$lines = $grid->getBlockVers($i);
			foreach($lines as $line) {
				$this->runPackAnalysis($lines, $line, $i, $grid, 'ver');
			}

			$lines = $grid->getBlockHors($i);
			foreach($lines as $line) {
				$this->runPackAnalysis($lines, $line, $i, $grid, 'hor');
			}
		}
	}

	public function runPackAnalysis(array $lines, $line, $blockNumber, Grid $grid, $type)
	{
		$otherLines = array_filter($lines, function($item) use ($line) { return $item !== $line; });
		if ($type == 'ver') {
			$lineCells = $grid->getVerCells($line);
		}
		if ($type == 'hor') {
			$lineCells = $grid->getHorCells($line);
		}

		if ($type == 'ver') {
			$blockLineCells = $grid->getBlockVerCells($blockNumber, $line);
		}
		if ($type == 'hor') {
			$blockLineCells = $grid->getBlockHorCells($blockNumber, $line);
		}

		$variations = array_reduce($blockLineCells, function($result, $cell){
			$result = array_merge($result, $cell->getVariations());
			return array_unique($result);
		}, array());

		$sameLineCells = $grid->filterCells($lineCells, $blockLineCells);
		$sameLineVariations = array_reduce($sameLineCells, function($result, $cell){
			$result = array_merge($result, $cell->getVariations());
			return array_unique($result);
		}, array());

		$candidateVariations = array_diff($variations, $sameLineVariations);
		if (!$candidateVariations) {
			return;
		}

		$otherCells = array();
		foreach($otherLines as $otherLine) {
			if ($type == 'ver') {
				$lineCellsExcludeBlock = $grid->getVerCellsExcludeBlock($otherLine, $blockNumber);
			}
			if ($type == 'hor') {
				$lineCellsExcludeBlock = $grid->getHorCellsExcludeBlock($otherLine, $blockNumber);
			}
			$otherCells = array_merge($otherCells, $lineCellsExcludeBlock);
		}

		$otherVariations = array_reduce($otherCells, function($result, $cell){
			$result = array_merge($result, $cell->getVariations());
			return array_unique($result);
		}, array());

		$diffVariations = array_diff($candidateVariations, $otherVariations);
		if (!$diffVariations) {
			// remove these variations from same block cell variations
			foreach($candidateVariations as $variation) {
				foreach($otherLines as $otherLine) {
					if ($type == 'ver') {
						$blockOtherVerCells = $grid->getBlockVerCells($blockNumber, $otherLine);
					}
					if ($type == 'hor') {
						$blockOtherVerCells = $grid->getBlockHorCells($blockNumber, $otherLine);
					}
					foreach($blockOtherVerCells as $blockOtherVerCell) {
						$blockOtherVerCell->removeVariation($variation);
					}
				}
			}
		}
	}
}
