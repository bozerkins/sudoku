<?php

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\Grid\Grid;

class SingleCandidateTechnique extends TechniqueAbstract
{
	public function analyze(Grid $grid)
	{
		exit('analyze! implement!');
	}

	public function fillWhatYouCan(Grid $grid)
	{
		for($i = 0; $i < $grid->getSize(); $i++) {
			$this->runVariationsAnalysis($grid->getHorCells($i), $grid);
		}
		for($i = 0; $i < $grid->getSize(); $i++) {
			$this->runVariationsAnalysis($grid->getVerCells($i), $grid);
		}
		for($i = 0; $i < $grid->getSize(); $i++) {
			$this->runVariationsAnalysis($grid->getBlockCells($i), $grid);
		}
	}

	protected function runVariationsAnalysis(array $cells, Grid $grid)
	{
		$variations = array_reduce($cells, function($result, $cell) {
			$result = array_merge($result, $cell->getVariations());
			return $result;
		}, array());
		$variationsCounts = array_filter(array_count_values($variations), function($item) {
			return $item === 1;
		}) ?: array();
		foreach($variationsCounts as $variation => $count) {
			foreach($cells as $cell) {
				if (in_array($variation, $cell->getVariations())) {
					$grid->setCellValue($cell, $variation);
				}
			}
		}
	}
}
