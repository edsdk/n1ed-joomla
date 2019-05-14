<?php

require __DIR__ . '/vendor/autoload.php';

use EdSDK\FlmngrServer\FlmngrServer;

FlmngrServer::flmngrRequest(
    array(
        'dirFiles' => '../../../../images/n1ed',
        'dirTmp'   => '../../../../images/n1ed_tmp',
        'dirCache' => '../../../../images/n1ed_cache'
    )
);