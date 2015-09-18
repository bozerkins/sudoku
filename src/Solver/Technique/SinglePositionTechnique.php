<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class SinglePositionTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		$values = array();
		for($rowindex = 0; $rowindex < $grid->getSize(); $rowindex++) {
			for($cellindex = 0; $cellindex < $grid->getSize(); $cellindex++) {
				$cell = $grid->getCell($rowindex, $cellindex);
				if ($cell->get()) {
					continue;
				}
				$freeItems = $this->getFreeItems($rowindex, $cellindex, $grid);
				if (count($freeItems) !== 1) {
					continue;
				}

				$value = array();
				$value['hor'] = $rowindex;
				$value['ver'] = $cellindex;
				$value['value'] = array_pop(array_values($freeItems));
				$values[] = $value;
			}
		}
		foreach($values as $value) {
			$grid->getCell($value['hor'], $value['ver'])->set($value['value']);
		}
	}

	protected function getTakenItems($rowindex, $cellindex, $grid) {

		// get row numbers
		$rowItems = array_map(function($cell){ return $cell->get(); }, $grid->getHorCells($rowindex));
		$rowItems = array_values(array_filter($rowItems));

		// get cell numbers
		$cellItems = array_map(function($cell){ return $cell->get(); }, $grid->getVerCells($cellindex));
		$cellItems = array_values(array_filter($cellItems));

		// get block(segment) numbers
		$blockItems = array_map(function($cell){ return $cell->get(); }, $grid->getBlockCells($rowindex, $cellindex));
		$blockItems = array_values(array_filter($blockItems));

		$items = array_values(array_unique(array_merge($rowItems, $cellItems, $blockItems)));
		return $items;
	}

	protected function getFreeItems($rowindex, $cellindex, $grid)
	{
		$allitems = array(1,2,3,4,5,6,7,8,9);
		$taken = $this->getTakenItems($rowindex, $cellindex, $grid);
		$freeitems = array_values(array_diff($allitems, $taken));
		return $freeitems;
	}
}
