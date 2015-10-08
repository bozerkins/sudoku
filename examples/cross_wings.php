<?php

include __DIR__ . '/../vendor/autoload.php';

$sequence = \Sudoku\Storage\Storage::get(7);

$grid = new \Sudoku\Grid\Grid;
$grid->populate($sequence);

$table = new \Sudoku\Table\Table($grid);
$table->draw(true, false);

$solver = new \Sudoku\Solver\Solver;
$solver->setGrid($grid);
$solver->addTechnique(new \Sudoku\Solver\Technique\SinglePositionTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\SingleCandidateTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\CandidateLinesTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\DoublePairsTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\NakedPairsTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\HiddenPairsTechnique);
$solver->addTechnique(new \Sudoku\Solver\Technique\CrossWingsTechnique);
$solver->solve();

// $table = new \Sudoku\Table\Table($grid);
// $table->draw();
$table = new \Sudoku\Table\Table($grid);
$table->draw(true, false);

// $solver = new \Sudoku\Solver\Solver;
// $solver->setGrid($grid);
// $solver->solve();


// $table = new \Sudoku\Table\Table($grid);
// $table->draw();
// $table = new \Sudoku\Table\Table($grid);
// $table->draw(true, true);
