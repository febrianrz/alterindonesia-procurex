<?php
namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Interfaces\TaskInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\TransferStats;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SendTask {

    private Client $client;

    public function __construct()
    {
        if(!config('procurex.service_base_url')) {
            return ;
        }
        $this->generateTableIntegrationLogs();
        $this->client = new Client([
            'base_uri' => config('procurex.service_base_url'),
            'on_stats' => function (TransferStats $stats) {
                $this->guzzleLog($stats);
            }
        ]);
    }

    protected function guzzleLog($stats): void
    {
        \Alterindonesia\Procurex\Models\IntegrationLog::create([
            'url'          => $stats->getEffectiveUri()->getPath(),
            'http_code'    => $stats->getResponse()->getStatusCode(),
            'body_request' => $stats->getRequest()->getBody(),
            'response'     => $stats->getResponse()->getBody(),
            'execution'    => $stats->getTransferTime()
        ]);
    }
    public function publishTask(TaskInterface $task, $protocol="http"): void
    {
        if($task instanceof TaskInterface) {
            if($protocol == "http")
                $this->http($task);
            else {
                $rabbit = new RabbitMQProducer();
                $rabbit->publishTask($task);
            }
        }

    }

    private function http(TaskInterface $task): void
    {
        $payload = [
            'type'    => $task->type(),
            'data'    => $task->payload(),
        ];
        try {
            $accessToken = config('procurex.access_token',null);

            $this->client->request('post','/api/task/event',[
                'headers' => [
                    'Content-Type' => "application/json",
                    'Accept' => "application/json",
                    'Authorization' => 'Bearer '.$accessToken
                ],
                'json'    => $payload
            ]);
        } catch (GuzzleException $e) {
            \Log::error("Send Task With payload: ".json_encode($payload)." Error:".$e->getMessage());
        }
    }

    private function generateTableIntegrationLogs(): void
    {
        if(Schema::hasTable('integration_logs')) return;

        Schema::create('integration_logs', function(Blueprint $table){
            $table->id('id');
            $table->string('service')->nullable();
            $table->text('url')->nullable();
            $table->unsignedInteger('http_code')->nullable();
            $table->text('body_request')->nullable();
            $table->text('response')->nullable();
            $table->decimal('execution',16,10);
            $table->timestamps();
        });
    }

    public function whatsapp($to,$message,$type='01'): void
    {
        try {
            $accessToken = config('procurex.access_token',null);
            $payload = [
                'type'    => $type,
                'number'  => $to,
                'message' => $message
            ];
            $this->client->request('post','/api/task/whatsapp',[
                'headers' => [
                    'Content-Type' => "application/json",
                    'Accept' => "application/json",
                    'Authorization' => 'Bearer '.$accessToken
                ],
                'json'    => $payload
            ]);
        } catch (GuzzleException $e) {
            echo Message::toString($e->getMessage()).PHP_EOL;
        }
    }

    public function email($to, $subject, $message, $cc = [], $bcc = [], $attachments = [],): void {
        try {
            $accessToken = config('procurex.access_token',null);
            $payload = [
                'to'=> $to,
                'subject' => $subject,
                'message' => $message,
                'cc' => $cc,
                'bcc' => $bcc,
                'attachments' => $attachments
            ];
            $this->client->request('post','/api/task/email',[
                'headers' => [
                    'Content-Type' => "application/json",
                    'Accept' => "application/json",
                    'Authorization' => 'Bearer '.$accessToken
                ],
                'json'    => $payload
            ]);
        } catch (GuzzleException $e) {
            echo Message::toString($e->getMessage()).PHP_EOL;
        }
    }

    public static function sendWhatsapp($to,$message,$type='01'): void
    {
        (new SendTask())->whatsapp($to,$message,$type);
    }

    public static function sendEmail($to,$subject,$message,$cc = [],$bcc = [],$attachments = []): void
    {
        (new SendTask())->email($to,$subject,$message,$cc,$bcc,$attachments);
    }


}
