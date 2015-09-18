<?php

namespace Sudoku\Grid;

class Cell
{
	protected $value;
	protected $variations = array();

	public function set($value)
	{
		$this->value = $value;
		return $this;
	}

	public function get()
	{
		return $this->value;
	}

	public function clear()
	{
		$this->value = null;
		return $this;
	}

	public function getVariations()
	{
		return $this->variations;
	}

	public function addVariation($value)
	{
		if (!$this->hasVariation($value)) {
			$this->variations[] = $value;
		}
	}

	public function hasVariation($value)
	{
		return in_array($value, $this->variations);
	}

	public function removeVariation($value)
	{
		$key = array_search($value, $this->variations);
		unset($this->variations[$key]);
		return $this;
	}

	public function clearVariations()
	{
		$variations = $this->getVariations();
		foreach($variations as $variation) {
			$this->removeVariation($variation);
		}
		return $this;
	}

	public function isEmpty()
	{
		return is_null($this->value);
	}
}
