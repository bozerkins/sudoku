<?php

$grid = array();
for($j = 1; $j < 10; $j++) {
    $box = array();
    for($i = 1; $i < 10; $i++) {
        $box[] = pick($box);
    }
    $grid[] = $box;
}
draw($grid);
exit;


// sudoku solver
$grid = array(
    array(1,5,null,null,6,3,9,null,null,),
    array(6,8,null,9,4,2,3,null,null,),
    array(null,null,null,1,null,null,null,8,null,),
    array(null,null,null,3,1,null,null,4,2,),
    array(null,null,null,5,null,7,null,null,null,),
    array(2,3,null,null,8,4,null,null,null,),
    array(null,2,null,null,null,1,null,null,null,),
    array(null,null,8,2,3,6,null,7,1,),
    array(null,null,3,7,9,null,null,6,4),
);

draw($grid);
echo '<br>';
draw(applyProbability($grid));

function getTakenItems($rowindex, $cellindex, $grid) {
    // get row numbers
    $rowItems = array_values(array_filter($grid[$rowindex]));

    // get cell numbers
    $cellItems = array_reduce($grid, function($result, $item) use ($cellindex){
        if ($item[$cellindex]) {
            $result[] = $item[$cellindex];
        }
        return $result;
    }, array());

    // get box(segment) numbers
    $boxItems = array();
    $segmentRows = getSegmentRange($rowindex);
    $segmentCells = getSegmentRange($cellindex);
    foreach($segmentRows as $rowIndex) {
        foreach($segmentCells as $cellIndex) {
            if ($grid[$rowIndex][$cellIndex]) {
                $boxItems[] = $grid[$rowIndex][$cellIndex];
            }
        }
    }
    $items = array_values(array_unique(array_merge($rowItems, $cellItems, $boxItems)));
    return $items;
}

function getFreeItems($rowindex, $cellindex, $grid)
{
    $allitems = array(1,2,3,4,5,6,7,8,9);
    $taken = getTakenItems($rowindex, $cellindex, $grid);
    $freeitems = array_values(array_diff($allitems, $taken));
    return $freeitems;
}

function getSegmentRange($index, $step = 2)
{
    $segment = intval($index / ($step + 1));
    return range($segment * ($step + 1), $segment * ($step + 1) + $step);
}

function applyProbability($grid) {
    $newGrid = array();
    foreach($grid as $rowindex => $row) {
        $newGrid[$rowindex] = array();
        foreach($row as $cellindex => $cell) {
            if (!$cell) {
                $newGrid[$rowindex][$cellindex] = count(getFreeItems($rowindex, $cellindex, $grid)) . '/' . 9;
            } else {
                $newGrid[$rowindex][$cellindex] = $cell;
            }
        }
    }
    return $newGrid;
}

function draw($grid) {
    echo '<style>
    table {
        text-align: center;
        vertical-align: middle;
        border-collapse: collapse;
        border: 2px solid black;
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
    echo '<table border=1>';
    foreach($grid as $row) {
        echo '<tr>';
        foreach($row as $cell) {
            echo '<td>' . $cell . '</td>';
        }

        echo '</tr>';
    }
    echo '</table>';
}

// box generator
function pick(array $existing)
{
    $numbers = array(1,2,3,4,5,6,7,8,9);

    $numbersDiff = array_values(array_diff($numbers, $existing));
    return $numbersDiff[rand(0,count($numbersDiff) - 1)];
}
