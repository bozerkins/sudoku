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
		$techniques = $this->techniques;
		// calculate what you can separately with each technique
		foreach($techniques as $technique) {
			$this->applyGridLoop(function($grid) use ($technique) {
				$technique->fillWhatYouCan($grid);
			});
		}

		// calculate with each technique sequentially
		$this->applyGridLoop(function($grid) use ($techniques) {
			foreach($techniques as $technique) {
				$technique->fillWhatYouCan($grid);
			}
		});
	}

	protected function applyGridLoop(\Closure $callback)
	{
		$filled = null;
		$techniquesFilled = null;
		$variations = null;
		$variationRemoved = null;

		while($filled != $this->getGrid()->getSequenceSize()) {
			$filled = count($this->getGrid()->getEmptyCells());
			$variations = count($this->getGrid()->getEmptyCellVariations());

			$callback($this->getGrid());

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
