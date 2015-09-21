<?php

namespace Sudoku\Grid;

class Grid
{
	protected $sequence;
	protected $size = 9;
	protected $blockSize = 3;

	public function populate(array $valueSequence)
	{
		// reset sequence
		$this->sequence = array();

		// populate with empty cells
		foreach(array_values($valueSequence) as $index => $value) {
			$ver = $index % $this->getSize();
			$hor = ($index - $ver) / $this->getSize();

			$cell = new Cell();
			$cell->set($value);
			$cell->setHor($hor);
			$cell->setVer($ver);

			$this->sequence[] = $cell;
		}
		// populate with possible variations
		foreach($this->getEmptyCells() as $index => $cell) {
			$variations = $this->getFreeItems($cell->getHor(), $cell->getVer()) ?: array();
			foreach($variations as $variation) {
				$cell->addVariation($variation);
			}
		}
		return $this;
	}

	public function clear()
	{
		$this->sequence = null;
		return $this;
	}

	protected function getTakenItems($hor, $ver) {

		// get row numbers
		$rowItems = array_map(function($cell){ return $cell->get(); }, $this->getHorCells($hor));
		$rowItems = array_values(array_filter($rowItems));

		// get cell numbers
		$cellItems = array_map(function($cell){ return $cell->get(); }, $this->getVerCells($ver));
		$cellItems = array_values(array_filter($cellItems));

		// get block(segment) numbers
		$blockItems = array_map(function($cell){ return $cell->get(); }, $this->getBlockCells($this->getBlockNumber($hor, $ver)));
		$blockItems = array_values(array_filter($blockItems));

		$items = array_values(array_unique(array_merge($rowItems, $cellItems, $blockItems)));
		return $items;
	}

	protected function getFreeItems($hor, $ver)
	{
		$allitems = $this->getPossibleValues();
		$taken = $this->getTakenItems($hor, $ver);
		$freeitems = array_values(array_diff($allitems, $taken));
		return $freeitems;
	}

	public function getEmptyCellVariations()
	{
		return array_filter(
			array_reduce(
				$this->sequence,
				function($result, $cell) {
					$result = array_merge($result, $cell->getVariations());
					return $result;
				},
				array()
			)
		);
	}

	public function getEmptyCells()
	{
		return array_filter($this->sequence, function($cell){ return $cell->isEmpty(); });
	}

	public function getFilledCells()
	{
		return array_filter($this->sequence, function($cell){ return !$cell->isEmpty(); });
	}

	public function getHorCells($hor)
	{
		$sequence = array();
		for($i = 0; $i < $this->getSize(); $i++) {
			$sequence[] = $this->getCell($hor, $i);
		}
		return $sequence;
	}

	public function getVerCells($ver)
	{
		$sequence = array();
		for($i = 0; $i < $this->getSize(); $i++) {
			$sequence[] = $this->getCell($i, $ver);
		}
		return $sequence;
	}

	public function getBlockCells($number)
	{
		$sequence = array();
		foreach($this->getBlockHors($number) as $horIndex) {
			foreach($this->getBlockVers($number) as $verIndex) {
				$sequence[] = $this->getCell($horIndex, $verIndex);
			}
		}
		return $sequence;
	}

	public function filterCells(array $cells, array $filterCells)
	{
		return array_filter($cells, function($cell) use($filterCells) {
			foreach($filterCells as $filterCell) {
				if ($cell->getHor() === $filterCell->getHor() && $cell->getVer() === $filterCell->getVer()) {
					return false;
				}
			}
			return true;
		});
	}

	public function filterEmptyCells(array $cells)
	{
		return array_filter($cells, function($cell) {
			return $cell->isEmpty();
		});
	}

	public function getBlockHors($number)
	{
		$minVer = ($number % $this->getBlockSize()) * $this->getBlockSize();
		$minHor = ($number - ($number % $this->getBlockSize()));
		return range($minHor, $minHor + $this->getBlockSize() - 1);
	}

	public function getBlockVers($number)
	{
		$minVer = ($number % $this->getBlockSize()) * $this->getBlockSize();
		$minHor = ($number - ($number % $this->getBlockSize()));

		return range($minVer, $minVer + $this->getBlockSize() - 1);
	}

	public function getBlockHorCells($number, $hor)
	{
		$sequence = array();
		foreach($this->getBlockHors($number) as $verIndex) {
			$sequence[] = $this->getCell($hor, $verIndex);
		}
		return $sequence;
	}

	public function getBlockVerCells($number, $ver)
	{
		$sequence = array();
		foreach($this->getBlockVers($number) as $horIndex) {
			$sequence[] = $this->getCell($horIndex, $ver);
		}
		return $sequence;
	}

	public function getBlockNumber($hor, $ver)
	{
		$horSegment = intval($hor / $this->getBlockSize());
		$verSegment = intval($ver / $this->getBlockSize());
		return $horSegment * $this->getBlockSize() + $verSegment;
	}

	public function getCell($hor, $ver)
	{
		if (is_null($this->sequence)) {
			throw new \ErrorException('sequence was not populated');
		}
		return $this->sequence[$this->getSequencePosition($hor, $ver)];
	}

	public function setCell($hor, $ver, $value)
	{
		if (is_null($this->sequence)) {
			throw new \ErrorException('sequence was not populated');
		}
		$this->sequence[$this->getSequencePosition($hor, $ver)]->set($value);
		return $this;
	}

	public function setCellValue(Cell $cell, $value)
	{
		$cell->set($value);

		foreach($this->getHorCells($cell->getHor()) as $item) {
			$item->removeVariation($value);
		}

		foreach($this->getVerCells($cell->getVer()) as $item) {
			$item->removeVariation($value);
		}

		foreach($this->getBlockCells($this->getBlockNumber($cell->getHor(), $cell->getVer())) as $item) {
			$item->removeVariation($value);
		}

		return $this;
	}

	public function getPossibleValues()
	{
		return range(1, $this->getSize());
	}

	public function getSequenceSize()
	{
		return count($this->sequence);
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getBlockSize()
	{
		return $this->blockSize;
	}

	protected function getSequencePosition($hor, $ver)
	{
		return $hor * $this->getSize() + $ver;
	}
}
