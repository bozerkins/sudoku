<?php

namespace Sudoku\Grid;

class Grid
{
	protected $sequence;
	protected $size = 9;
	protected $blockSize = 3;

	public function populate(array $valueSequence)
	{
		$this->sequence = array();
		foreach($valueSequence as $value) {
			$cell = new Cell();
			$cell->set($value);
			$this->sequence[] = $cell;
		}
		return $this;
	}

	public function clear()
	{
		$this->sequence = null;
		return $this;
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

	public function getBlockCells($hor, $ver)
	{
		$sequence = array();
		foreach($this->getBlockSegment($hor) as $horIndex) {
			foreach($this->getBlockSegment($ver) as $verIndex) {
				$sequence[] = $this->getCell($horIndex, $verIndex);
			}
		}
		return $sequence;
	}

	protected function getBlockSegment($index)
	{
	    $segment = intval($index / $this->getBlockSize());
	    return range($segment * $this->getBlockSize(), $segment * $this->getBlockSize() + ( $this->getBlockSize() - 1));
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
