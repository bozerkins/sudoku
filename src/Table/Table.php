<?php

namespace Sudoku\Table;

use Sudoku\Grid\Grid;

class Table
{
	protected $grid;

	public function __construct(Grid $grid)
	{
		$this->grid = $grid;
	}

	public function get($drawVariations = false)
	{
		$html = '';
		$html .= '<style>
		table {
			text-align: center;
			vertical-align: middle;
			border-collapse: collapse;
			border: 2px solid black;
			display: inline-table;
			margin: 5px;
		}
		td {
			width: 30px;
			height: 30px;
		}
		td:nth-child(3n+1) {
			border-left: 2px solid black;
		}
		tr:nth-child(3n) {
			border-bottom: 2px solid black;
		}
		</style>';
		$html .= '<table border=1>';
		for($i = 0; $i < $this->grid->getSize(); $i++) {
			$html .= '<tr>';
			for($j = 0; $j < $this->grid->getSize(); $j++) {
				$cell = $this->grid->getCell($i, $j);
				$tdHtml = $cell->get();
				if ($drawVariations) {
					$variations = $cell->getVariations();
					if ($variations) {
						$tdHtml .= '<span style="font-size: 10px;">' . implode(', ', $cell->getVariations()) .'</span>';
					}
				}
				$html .= '<td>' . $tdHtml . '</td>';
			}

			$html .= '</tr>';
		}
		$html .= '</table>';

		return $html;
	}

	public function draw($variations = false)
	{
		echo $this->get($variations);
	}
}
