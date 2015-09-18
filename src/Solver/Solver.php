<?php

namespace Sudoku\Solver;

use Sudoku\Grid\Grid;

class Solver
{
	protected $grid;
	protected $techniques = array();

	public function setGrid(Grid $grid)
	{
		$this->grid = $grid;
		return $this;
	}

	public function getGrid()
	{
		return $this->grid;
	}

	public function addTechnique(Technique\TechniqueInterface $technique)
	{
		$this->techniques[] = $technique;
		return $this;
	}

	public function solve()
	{
		$filled = null;
		$techniquesFilled = null;
		$variations = null;
		$variationRemoved = null;

		while($filled != $this->getGrid()->getSequenceSize()) {
			$filled = count($this->getGrid()->getEmptyCells());
			$variations = count($this->getGrid()->getEmptyCellVariations());

			$this->applyTechniques();

			$techniquesFilled = count($this->getGrid()->getEmptyCells()) - $filled;
			if (!$techniquesFilled) {
				$variationRemoved = $variations - count($this->getGrid()->getEmptyCellVariations());
				if (!$variationRemoved) {
					break;
				}
				break;
			}
		}
	}

	protected function applyTechniques()
	{
		foreach($this->techniques as $technique) {
			$technique->fillWhatYouCan($this->getGrid());
		}

		return $this;
	}
}
