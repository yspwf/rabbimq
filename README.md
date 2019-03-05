# rabbimq
自己封装的rabbitmq操作类:

    1.连接RabbitMQ服务器
    2.开始一个新的 channel
    3.新建一个exchange
    4.新建一个queue
    5.绑定queue和exchange
    6.发布一个消息
    7.建立一个消费者并注册一个回调函数
    8.监听数据

### 类的基本使用
    消息发布：
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
    $obj->Publish('hello rabbimq!');
    
    消息消费：
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

    class A{  
        function process($event,  $q){ 
            $msg = $event->getBody();
            echo $msg;
            $q->ack($event->getDeliveryTag());
        }
    }  
    $a = new A(); 
    
    $obj = RabbitMQ::getInstance($config);
    $obj->consume([$a,'process']);
