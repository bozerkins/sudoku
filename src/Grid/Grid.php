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
		$ver = ($number % $this->getBlockSize()) * $this->getBlockSize();
		$hor = ($number - ($number % $this->getBlockSize()));

		$sequence = array();
		foreach(range($hor, $hor + $this->getBlockSize() - 1) as $horIndex) {
			foreach(range($ver, $ver + $this->getBlockSize() - 1) as $verIndex) {
				$sequence[] = $this->getCell($horIndex, $verIndex);
			}
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
