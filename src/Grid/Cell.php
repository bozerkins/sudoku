<?php

namespace Sudoku\Grid;

class Cell
{
	protected $hor;
	protected $ver;
	protected $value;
	protected $variations = array();

	public function setHor($hor)
	{
		$this->hor = $hor;
		return $this;
	}

	public function getHor()
	{
		return $this->hor;
	}

	public function setVer($ver)
	{
		$this->ver = $ver;
		return $this;
	}

	public function getVer()
	{
		return $this->ver;
	}

	public function set($value)
	{
		$this->value = $value;

		$this->clearVariations();

		return $this;
	}

	public function get()
	{
		return $this->value;
	}

	public function clear()
	{
		$this->value = null;
		$this->clearVariations();
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
		return $this;
	}

	public function hasVariation($value)
	{
		return in_array($value, $this->variations);
	}

	public function removeVariation($value)
	{
		$key = array_search($value, $this->variations);
		if ($key !== false) {
			unset($this->variations[$key]);
		}
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
