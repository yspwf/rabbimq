<?php 

require_once __DIR__.'/rabbit.php';

$config = [
    'amqp'=>[
        'host'=>'127.0.0.1',
        'port'=>5672,
        'vhost'=>'/',
        'login'=>'ysp',
        'password'=>'123123'
    ],
    'params'=>[
        'type'=>'direct',
        'queuename'=>'queueclass',
        'exchangename'=>'exclass',
        'routekey' => 'classKey'
    ]
];

$obj = RabbitMQ::getInstance($config);
$obj->Publish('hello  hhhhhhhhhh');


?>