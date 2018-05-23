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

$keys = [];
$level = 0;

function visitArray($arr) {
    global $keys;
    global $level;
    $level++;
    foreach ($arr as $key => $value) {
        if (!is_array($value))
            continue;
        $keys[] = [$level,$key];
        if (array_keys($value)[0] === 0) {
            $keys[] = [$level, '>>>>>>>>>>'];
        } else {
            visitArray($value);
            $level--;
        }
    }
}

debug($array_test);
visitArray($array_test);
debug($keys);





