<?php 

class RabbitMQ{

//    public static $config = [
//                         'host'=>'127.0.0.1',
//                         'port'=>5672,
//                         'vhost'=>'/',
//                         'login'=>'ysp',
//                         'password'=>'123123'
//                     ];

    public static $instance;
    public $connection;
    public $channel;
    public $exchange;
    public $queue;
    public $queueName;
    public $exchangeName;
    public $routeKey;
    public $type = ['direct'=>AMQP_EX_TYPE_DIRECT,'fanout'=>AMQP_EX_TYPE_FANOUT,'topic'=>AMQP_EX_TYPE_TOPIC];
    public $exchangeType;
    
    public static function getInstance($config=''){
        if(!self::$instance instanceof self){
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * 创建amqp链接
     */
    public function __construct($config){
        try{
            $this->queueName = $config['params']['queuename'];
            $this->exchangeName = $config['params']['exchangename'];
            $this->routeKey = $config['params']['routekey'];
            $this->exchangeType = $this->type[$config['params']['type']];

            $this->connection = new AMQPConnection($config['amqp']);
            $this->connection->connect();
            $this->Exchange();
            $this->Queue();
        }catch(Exception $e){
            echo $e->getMessage();
           // throw new Exception($e->getMessage());
        }
    }

    public function Exchange(){
        $this->channel = new AMQPChannel($this->connection);
        $this->exchange = new AMQPExchange($this->channel);
        $this->exchange->setName($this->exchangeName);
        $this->exchange->setType( $this->exchangeType);
        $this->exchange->setFlags(AMQP_DURABLE);
        $this->exchange->declareExchange();
    }


    public function Queue(){
        $this->queue = new AMQPQueue($this->channel);
        $this->queue->setName($this->queueName);
        $this->queue->setFlags(AMQP_DURABLE);
        $this->queue->declareQueue();
        $this->queue->bind($this->exchangeName, $this->routeKey);
    }

    public function publish($msg){
        $this->exchange->publish($msg, $this->routeKey);
        
    }



    public function consume($callback){
        while(true){
            $this->queue->consume($callback);
        }
    }

    function __destruct(){
        $this->connection->disconnect();
    }

}

?>