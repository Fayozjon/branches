<?php

return [
    [
        'name' => 'Branches',
        'flag' => 'branches.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'branches.create',
        'parent_flag' => 'branches.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'branches.edit',
        'parent_flag' => 'branches.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'branches.destroy',
        'parent_flag' => 'branches.index',
    ],
];
