<?php
namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Illuminate\HttpProcurex;
use Alterindonesia\Procurex\Interfaces\TaskInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\TransferStats;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SendTask extends HttpProcurex {
    protected string $baseUriEvent = '/api/task/event';
    protected string $baseUriWhatsapp = '/api/task/whatsapp';
    protected string $baseUriEmail = '/api/task/email';

    public function __construct()
    {
        parent::__construct(config('procurex.service_base_url'));
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
            $this->client->request('post',$this->baseUriEvent,[
                'headers' => $this->getHeaders(),
                'json'    => $payload
            ]);
        } catch (GuzzleException $e) {
            \Log::error("Send Task With payload: ".json_encode($payload)." Error:".$e->getMessage());
        }
    }

    public function whatsapp($to,$message,$type='01'): void
    {
        try {
            $payload = [
                'type'    => $type,
                'number'  => $to,
                'message' => $message
            ];
            $this->client->request('post', $this->baseUriWhatsapp,[
                'headers' => $this->getHeaders(),
                'json'    => $payload
            ]);
        } catch (GuzzleException $e) {
            echo Message::toString($e->getMessage()).PHP_EOL;
        }
    }

    public function email($to, $subject, $message, $cc = [], $bcc = [], $attachments = [],): void {
        try {
            $payload = [
                'to'=> $to,
                'subject' => $subject,
                'message' => $message,
                'cc' => $cc,
                'bcc' => $bcc,
                'attachments' => $attachments
            ];
            $this->client->request('post',$this->baseUriEmail,[
                'headers' => $this->getHeaders(),
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
