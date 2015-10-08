<?php

namespace Sudoku\Solver\Technique;

use Sudoku\Grid\Grid;

class SwordfishTechnique implements TechniqueInterface
{
	public function fillWhatYouCan(Grid $grid)
	{
		// 1. check each variation presence in lines
		// 2. check if found pairs corelate somehow
		// 3. retrieve corelated pairs
		// 4. remove opposite orientation variation occurencies

		$variation = 8;
		$line = 0;
		$pairs = array();
		// get pairs for variation
		foreach(range($line + 1, $grid->getSize() - 1) as $line) {
			$variationCells = array();
			foreach($grid->getVerCells($line) as $cell) {
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
		// find first connected pair
		$connected = null;
		foreach($pairs as $pair) {
			foreach($pairs as $pairToCompare) {
				$lineDiff = array_diff($pair['hors'], $pairToCompare['hors']);
				if (count($lineDiff) === 1) {
					$connected = $pair; break;
				}
			}
			if ($connected) {
				break;
			}
		}
		// find chain
		$chain = array();
		$chain[] = $connected;
		foreach($chain as $pair) {
			foreach($pairs as $pairToCompare) {
				$lineDiff = array_diff($pair['hors'], $pairToCompare['hors']);
				if (count($lineDiff) === 1) {
					$connected = $pairToCompare; break;
				}
			}
			if ($connected) {
				break;
			}
		}
		\Sudoku\Dumper::out($connected);
		exit;

	}

	public function runPackAnalysis(array $cells, Grid $grid, $i, $type)
	{

	}
}
