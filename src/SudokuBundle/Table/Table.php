<?php

namespace SudokuBundle\Table;

use SudokuBundle\Grid\Grid;

class Table
{
	protected $grid;

	public function __construct(Grid $grid)
	{
		$this->grid = $grid;
	}

	public function get($drawVariations = false, $coords = false)
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
		/** highlight **/
		table {
		  overflow: hidden;
		}

		tr:hover {
		  background-color: #ffa;
		}

		td, th {
		  position: relative;
		}
		td:hover::after,
		th:hover::after {
		  content: "";
		  position: absolute;
		  background-color: #ffa;
		  left: 0;
		  top: -5000px;
		  height: 10000px;
		  width: 100%;
		  z-index: -1;
		}
		';
		if ($coords) {
			$html .= '
			td:nth-child(3n+2) {
				border-left: 2px solid black;
			}
			tr:nth-child(3n+1) {
				border-bottom: 2px solid black;
			}';
		} else {
			$html .= '
			td:nth-child(3n+1) {
				border-left: 2px solid black;
			}
			tr:nth-child(3n) {
				border-bottom: 2px solid black;
			}';
		}
		$html .='
		</style>';
		$html .= '<table border=1>';
		if ($coords) {
			$html .= '<tr>';
			$html .= '<td>&nbsp;</td>';
			foreach(range(0,8) as $num) {
				$html .= '<td><span style="font-style: italic;">' . $num . '</span></td>';
			}
			$html .= '</tr>';
		}
		for($i = 0; $i < $this->grid->getSize(); $i++) {
			$html .= '<tr>';
			if ($coords) {
				$html .= '<td><span style="font-style: italic;">' . $i . '</span></td>';
			}
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

	public function draw($variations = false, $coords = false)
	{
		echo $this->get($variations, $coords);
	}
}
