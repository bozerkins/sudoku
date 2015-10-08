<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class CrossWingsTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. find pairs in hors / vers
		// 2. ensure the lines exactly match
		// 3. remove variations from other elements
		// $i = 0;
		for($i = 0; $i < $grid->getSize() - 1; $i++) {
			$cells = $grid->getVerCells($i);
			$this->runPackAnalysis($cells, $grid, $i, 'ver');

			$cells = $grid->getHorCells($i);
			$this->runPackAnalysis($cells, $grid, $i, 'hor');
		}

	}

	public function runPackAnalysis(array $cells, Grid $grid, $i, $type)
	{
		// $table = new \Sudoku\Table\Table($grid);
		// $table->draw(true, true);

		$variations = array_reduce($cells, function($result, $cell) {
			$result = array_merge($result, $cell->getVariations());
			return $result;
		}, array());

		$crossWingVariations = array_keys(array_filter(array_count_values($variations), function($count) { return $count === 2; }));
		foreach($crossWingVariations as $crossWingVariation) {
			$crossWingCells = array_values(array_filter($cells, function($cell) use ($crossWingVariation) {
				return in_array($crossWingVariation, $cell->getVariations());
			}));

			$linePositions = range($i + 1, $grid->getSize() - 1);

			foreach($linePositions as $linePosition) {

				$crossWingMatchCells = array();

				foreach($crossWingCells as $crossWingCell) {
					if ($type === 'ver') {
						$crossWingMatchCell = $grid->getCell($crossWingCell->getHor(), $linePosition);
					}
					if ($type === 'hor') {
						$crossWingMatchCell = $grid->getCell($linePosition, $crossWingCell->getVer());
					}
					if (in_array($crossWingVariation, $crossWingMatchCell->getVariations())) {
						$crossWingMatchCells[] = $crossWingMatchCell;
					}
				}
				if (count($crossWingMatchCells) === 2) {
					// check the line
					if ($type === 'ver') {
						$crossWingMatchLineCells = $grid->getVerCells($linePosition);
					}
					if ($type === 'hor') {
						$crossWingMatchLineCells = $grid->getHorCells($linePosition);
					}
					$crossWingMatchLineVariations = array_reduce($crossWingMatchLineCells, function($result, $cell) {
						$result = array_merge($result, $cell->getVariations());
						return $result;
					}, array());

					$crossWingMatchVariations = array_keys(array_filter(array_count_values($crossWingMatchLineVariations), function($count) { return $count === 2; }));

					if (!in_array($crossWingVariation, $crossWingMatchVariations)) {
						continue;
					}
					// clear opposite dirrection from both cell pairs lines
					foreach($crossWingCells as $crossWingCell) {
						if ($type === 'ver') {
							$oppositeLineCells = $grid->getHorCells($crossWingCell->getHor());
						}
						if ($type === 'hor') {
							$oppositeLineCells = $grid->getVerCells($crossWingCell->getVer());
						}
						$oppositeLineCellsFiltered = $grid->filterCells($oppositeLineCells, array_merge($crossWingCells, $crossWingMatchCells));
						foreach($oppositeLineCellsFiltered as $oppositeLineCellFiltered) {
							// if (in_array($crossWingVariation, $oppositeLineCellFiltered->getVariations())) {
								// echo 'removed: ' . $crossWingVariation, '<br>';
							// }
							$oppositeLineCellFiltered->removeVariation($crossWingVariation);
						}
						// \Sudoku\Dumper::out(['removing', $oppositeLineCellsFiltered]);
					}
				}
			}
		}
	}
}
