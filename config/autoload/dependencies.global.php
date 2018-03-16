<?php

use \Phpug\Generic\ProductionDependencies;

return [
    'di' => [
        'cache' => sys_get_temp_dir(),
        'class' => ProductionDependencies::class,
    ],
];
