<?php

namespace Sudoku;

class Dumper
{
    public static function out($data)
    {
        echo '<pre>'; print_r($data); echo '</pre>';
    }

    public static function dump($data)
    {
        echo '<pre>'; var_dump($data); echo '</pre>';
    }
}
