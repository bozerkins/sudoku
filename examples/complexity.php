<?php

include __DIR__ . '/../vendor/autoload.php';

$sequence = \Sudoku\Storage\Storage::get(8);

$grid = new \Sudoku\Grid\Grid;
$grid->populate($sequence);

$table = new \Sudoku\Table\Table($grid);
$table->draw(false, false);

$technique = new \Sudoku\Solver\Technique\SingleCandidateTechnique();
$technique->analyze($grid);