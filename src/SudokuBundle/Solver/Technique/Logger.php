<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 15.9.12
 * Time: 21:43
 */

namespace SudokuBundle\Solver\Technique;


class Logger
{
    protected $list = array();

    public function write($message, $options = null)
    {
        if (is_array($options) || is_object($options)) {
            $message .= print_r($options, true);
        }

        $this->list = $message;
        return $this;
    }

    public function dump()
    {
        return $this->list;
    }
}