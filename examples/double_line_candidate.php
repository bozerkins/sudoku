<?php

include __DIR__ . '/../vendor/autoload.php';


$sequence = \Sudoku\Storage\Storage::get(4);

$grid = new \Sudoku\Grid\Grid;
$grid->populate($sequence);


$solver = new \Sudoku\Solver\Solver;
$solver->setGrid($grid);
$solver->addTechnique(new \Sudoku\Solver\Technique\SinglePositionTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\SingleCandidateTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\CandidateLinesTechnique);
$solver->solve();

$table = new \Sudoku\Table\Table($grid);
$table->draw();
$table = new \Sudoku\Table\Table($grid);
$table->draw(true);


$solver = new \Sudoku\Solver\Solver;
$solver->setGrid($grid);
$solver->addTechnique(new \Sudoku\Solver\Technique\DoublePairsTechnique);
$solver->solve();

$table = new \Sudoku\Table\Table($grid);
$table->draw();
$table = new \Sudoku\Table\Table($grid);
$table->draw(true);
