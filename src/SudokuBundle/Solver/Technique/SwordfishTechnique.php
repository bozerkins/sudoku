<?php

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\Grid\Grid;

class SwordfishTechnique extends Technique
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. check each variation presence in lines
		// 2. check if found pairs corelate somehow
		// 3. retrieve corelated pairs
		// 4. remove opposite orientation variation occurencies

		foreach($grid->getPossibleValues() as $variation) {
			$this->runPackAnalysis($variation, $grid, 'ver');
			$this->runPackAnalysis($variation, $grid, 'hor');
		}
	}

	public function runPackAnalysis($variation, Grid $grid, $type)
	{
		$pairs = array();
		// get pairs for variation
		for($i = 0; $i < $grid->getSize(); $i++) {
			$variationCells = array();
			if ($type === 'ver') {
				$cells = $grid->getVerCells($i);
			}
			if ($type === 'hor') {
				$cells = $grid->getHorCells($i);
			}
			foreach($cells as $cell) {
				if (in_array($variation, $cell->getVariations())) {
					$variationCells[] = $cell;
				}
			}
			if (count($variationCells) === 2) {
				$pair = array();
				$pair['cells'] = $variationCells;
				$pair['hors'] = array_map(function($cell){ return $cell->getHor(); }, $variationCells);
				$pair['vers'] = array_map(function($cell){ return $cell->getVer(); }, $variationCells);
				$pairs[] = $pair;
			}
		}

		// form connections
		$chains = array();
		foreach($pairs as $pair) {
			$matched = false;
			foreach($chains as &$chain) {
				if ($type === 'ver' && count(array_diff($pair['hors'], $chain['hors'])) >= 2) {
					continue;
				}
				if ($type === 'hor' && count(array_diff($pair['vers'], $chain['vers'])) >= 2) {
					continue;
				}

				$chain['cells'] = array_merge($chain['cells'], $pair['cells']);
				$chain['hors'] = array_merge($chain['hors'], $pair['hors']);
				$chain['vers'] = array_merge($chain['vers'], $pair['vers']);
				$matched = true;
			}
			unset($chain);

			if (!$matched) {
				$chain = array();
				$chain['cells'] = $pair['cells'];
				$chain['hors'] = $pair['hors'];
				$chain['vers'] = $pair['vers'];
				$chains[] = $chain;
			}
		}

		// verify each chain
		$validChains = array();
		foreach($chains as $chain) {
			if ($type === 'ver') {
				$lines = $chain['hors'];
			}
			if ($type === 'hor') {
				$lines = $chain['vers'];
			}
			$invalidCounts = array_filter(array_count_values($lines), function($amount){
				return $amount % 2 !== 0;
			});
			if (!$invalidCounts) {
				$validChains[] =  $chain;
			}
		}

		// find chain with max pairs, where count > 2
		$theChain = null;
		$maxCount = 0;
		foreach($validChains as $chain) {
			$amount = count($chain['cells']);
			if ($amount > 2 && $amount > $maxCount) {
				$maxCount = $amount;
				$theChain = $chain;
			}
		}

		// if chain exists - do the clearing
		if ($theChain) {
			if ($type === 'ver') {
				$lines = array_unique($theChain['hors']);
			}
			if ($type === 'hor') {
				$lines = array_unique($theChain['vers']);
			}
			foreach($lines as $line) {
				if ($type === 'ver') {
					$lineCells = $grid->getHorCells($line);
				}
				if ($type === 'hor') {
					$lineCells = $grid->getVerCells($line);
				}
				$otherCells = $grid->filterCells($lineCells, $theChain['cells']);
				foreach($otherCells as $cell) {
					$cell->removeVariation($variation);
				}
			}
		}
	}
}
