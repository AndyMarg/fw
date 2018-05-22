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

function removeDuplicates($parent, $child) {
    debug($parent,'parent before');
    debug($child,'child before');

    if (is_array($child)) {
        if (!empty($child)) {
            if (is_int(array_keys($child)[0])) {
                return $child[0];
            } else {
                $parent = removeDuplicates($child, $child[array_keys($child)[0]]);
            }
        }
    }
    debug($parent,'parent after');
    debug($child,'child after');
    return $child;
}

require_once '../../vendor/libs/utils.php';
//debug($array_test['level_1_5'],'parent');
//debug($array_test['level_1_5']['level_2_5'],'child');
//debug(removeDuplicates($array_test['level_1_5'], $array_test['level_1_5']['level_2_5']), 'result');

removeDuplicates($array_test['level_1_5'], $array_test['level_1_5']['level_2_5']);



//debug(removeDuplicates($array_test['level_1_5']['level_2_6']['level_3_6']));