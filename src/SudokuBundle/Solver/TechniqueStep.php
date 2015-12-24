<?php

namespace SudokuBundle\Solver;

class TechniqueStep
{
    protected $initiators = array();
    protected $influenced = array();

    public function getInitiators()
    {
        return $this->initiators;
    }

    public function setInitiators(array $items)
    {
        $this->initiators = $items;
        return $this;
    }

    public function addInitiator(array $item)
    {
        $this->initiators[] = $item;
        return $this;
    }

    public function getInfluenced()
    {
        return $this->influenced;
    }

    public function setInfluenced(array $items)
    {
        $this->influenced = $items;
        return $this;
    }

    public function addInfluenced(array $item)
    {
        $this->influenced[] = $item;
        return $this;
    }
}