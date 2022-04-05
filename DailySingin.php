<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingService;
use Carbon\Carbon;
use ratchet\pawl;
use React\MySQL\Factory;

class DailySingin extends Command
{
    // 命令名稱
    protected $signature = 'daemon:run';

    // 說明文字
    protected $description = '接受WebSocket';
    protected $aaa = '';

    public function __construct(SettingService $settingService)
    {
        parent::__construct();
        $this->settingService = $settingService;
    }

    // Console 執行的程式
    public function handle()
    {
        $loop = \React\EventLoop\Loop::get();
        $connector = new \Ratchet\Client\Connector($loop);

        $app = function (\Ratchet\Client\WebSocket $conn) use ($connector, $loop, &$app) {
            $conn->on('message', function (\Ratchet\RFC6455\Messaging\MessageInterface $msg) use ($conn) {
                //echo "Received: {$msg}\n";
                //$conn->close();
                $received = json_decode($msg , true);

                //$this->dodo($received);
                echo $received["S"];
            });
        
            $conn->on('close', function ($code = null, $reason = null) use ($connector, $loop, $app) {
                echo "Connection closed ({$code} - {$reason})\n";

                //in 3 seconds the app will reconnect
                $loop->addTimer(3, function () use ($connector, $loop, $app) {
                    $this->connectToServer($connector, $loop, $app);
                });

            });

            $data['method'] = "SUBSCRIBE";
            $data['params'] = array('btcusdt@aggTrade','btcusdt@depth');
            $data['id'] = 1;
            //var_dump($data);
            $conn->send(json_encode($data));
           
        };

        echo "--------------------------------";
        // while (1) {
        //     echo $this->$aaa;
        // }


        // \Ratchet\Client\connect('wss://stream.binance.com:9443/ws/bnbusdt@depth@100ms')->then(function($conn) {
        //     $conn->on('message', function($msg) use ($conn) {
        //         echo "Received: {$msg}\n";
        //         $conn->close();
        //     });

        //     $conn->on('close', function($code = null, $reason = null) {
        //         echo "Connection closed ({$code} - {$reason})\n";
        //     });
        
        //     $conn->send('Hello World!');

        // }, function(\Exception $e) use ($loop) {
        //     echo "Could not connect: {$e->getMessage()}\n";
        // });

        $this->connectToServer($connector, $loop, $app);

        $loop->run();


        

    }

    function dodo(array $received)
    {
        //$this->$aaa = $received["p"];
    }

    function connectToServer($connector, $loop, $app)
    {
        $connector('wss://stream.binance.com:9443/ws')
            ->then($app, function (\Exception $e) use ($loop) {
                echo "Could not connect: {$e->getMessage()}\n";
                $loop->stop();
            });
    }

}
