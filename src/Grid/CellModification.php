<?php

namespace Sudoku\Grid;

class CellModification implements CellModificationInterface
{
    protected $cell = null;
    protected $modifications = array();

    public function setCell(Cell $cell)
    {
        $this->cell = $cell;
        return $this;
    }

    public function getCell()
    {
        return $this->cell;
    }

    public function addModification($method, array $params = array())
    {
        $modification = array();
        $modification['method'] = $method;
        $modification['params'] = $params;
        $this->modifications[] = $modification;
        return $this;
    }

    public function applyModifications()
    {
        foreach($this->modifications as $modification) {
            call_user_func_array(array($this->cell, $modification['method']), $modification['params']);
        }
        return $this;
    }

    public function set($value)
    {
        $this->addModification(__FUNCTION__, array($value));
        return $this;
    }

    public function clear()
    {
        $this->addModification(__FUNCTION__);
        return $this;
    }

    public function addVariation($value)
    {
        $this->addModification(__FUNCTION__, array($value));
        return $this;
    }

    public function removeVariation($value)
    {
        $this->addModification(__FUNCTION__, array($value));
        return $this;
    }

    public function clearVariations()
    {
        $this->addModification(__FUNCTION__);
        return $this;
    }
}