<?php

$array_test = [
    'level_1_1' => 'value_1_1',
    'Level_1_2' => 'value_1_2',

    'level_1_3' => [
        'level_2_1' => 'value_2_1',
        'level_2_2' => 'value_2_2'
    ],
    'level_1_4' => [
        'level_2_3' => 'value_2_3',
        'level_2_4' => 'value_2_4'
    ],

    'level_1_5' => [
        'level_2_5' => [
            'level_3_1' => 'value_3_1',
            'level_3_2' => 'value_3_2',
            'level_3_3' => [
                0 => 'zero_0',
                1 => 'zero_1',
                2 => 'zero_2'
            ]
        ],
        'level_2_6' => [
            'level_3_4' => 'value_3_4',
            'level_3_5' => 'value_3_5',
            'level_3_6' => [
                0 => 'zero_3',
                1 => 'zero_4',
            ]
        ]
    ]
];
require_once '../../vendor/libs/utils.php';

function removeDuplicates($arr) {
    if (is_array($arr)) {

        foreach ($arr as $child) {
            if (is_array($child)) {
                if (!empty($child)) {
                    if (is_int(array_keys($child)[0])) {
                        $child = $child[0];
                        return $child[0];
                    } else {
                        $child = removeDuplicates($child);
                        return $child;
                    }
                }
            }
            return $arr;
        }
        return $child;
    } else {
        return $arr;
    }
}

function visitArray($arr) {
    foreach ($arr as$item) {
        if (is_array($item)) {
            visitArray($item);
        } else {
            echo $item . "<br>";
        }
    }
}

debug($array_test);

foreach ($array_test as $key => $item) {
    $array_test[$key] = removeDuplicates($array_test[$key]);
}

debug($array_test, 'after');
//debug(removeDuplicates($array_test));
//$array_test['level_1_5']['level_2_5']['level_3_3'] = removeDuplicates($array_test['level_1_5']['level_2_5']['level_3_3']);






