<?php

namespace Sudoku\Storage;

class Storage
{
	public static function get($number)
	{
		$list = require __DIR__ . '/data.php';
		return $list[$number];
	}
}
