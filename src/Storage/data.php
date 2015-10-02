<?php

$list = array();

// simple sequence
$list[0] = array(
	1,5,null,null,6,3,9,null,null,
    6,8,null,9,4,2,3,null,null,
    null,null,null,1,null,null,null,8,null,
    null,null,null,3,1,null,null,4,2,
    null,null,null,5,null,7,null,null,null,
    2,3,null,null,8,4,null,null,null,
    null,2,null,null,null,1,null,null,null,
    null,null,8,2,3,6,null,7,1,
    null,null,3,7,9,null,null,6,4
);

// simple sequence
$list[1] = array(
	null,null,null,9,null,null,null,4,5,null,null,5,null,
	1,3,null,6,8,null,null,4,null,5,null,null,null,null,8,
	null,2,null,null,7,null,null,1,4,5,null,null,null,null,null,
	3,6,1,null,null,3,null,null,4,null,7,null,null,null,null,7,
	null,3,null,null,9,8,null,5,3,null,6,null,null,7,4,null,null,
	null,1,null,null,null
);

// tricky sequence
$list[2] = array(
	null,3,null,null,4,7,null,null,null,6,null,null,null,2,null,null,7,9,null,null,2,1,
	null,null,null,null,null,3,null,null,2,7,null,6,null,null,2,null,null,8,null,5,null,
	null,1,null,null,8,null,6,1,null,null,5,null,null,null,null,null,3,2,null,null,5,2,
	null,null,1,null,null,null,3,null,null,null,7,8,null,null,4,null
);

// medium sequence
$list[3] = array(
	4,null,null,6,3,8,2,null,null,null,2,null,null,null,1,null,null,null,null,null,7,null,null,null,8,null,6,
	6,7,null,2,null,3,null,null,null,null,4,null,null,null,null,null,6,null,null,null,null,4,null,7,null,5,1,
	3,null,8,null,null,null,5,null,null,null,null,null,3,null,null,null,8,null,null,null,4,8,9,2,null,null,3
);

// double pairs sequence
$list[4] = array(
	9,3,4,null,6,null,null,5,null,null,null,6,null,null,4,9,2,3,null,null,8,9,null,null,null,4,6,8,null,null,5,4,6,null,null,7,
	6,null,null,null,1,null,null,null,5,5,null,null,3,9,null,null,6,2,3,6,null,4,null,1,2,7,null,4,7,null,6,null,null,5,null,null,
	null,8,null,null,null,null,6,3,4
);

// hard sequence
$list[5] = array(
	8,null,9,null,null,4,7,null,null,6,1,7,9,null,null,2,null,3,null,null,null,7,null,3,null,null,null,
	7,null,null,null,null,null,8,null,null,null,9,null,null,null,null,null,1,null,null,null,1,null,null,null,null,null,2,
	null,null,null,8,null,5,null,null,null,1,null,5,null,null,6,9,8,4,null,null,8,2,null,null,3,null,5
);


return $list;
