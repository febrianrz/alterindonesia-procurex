<?php
namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Illuminate\HttpProcurex;
use GuzzleHttp\Exception\GuzzleException;

class SendDiscord extends HttpProcurex {

    protected string $baseUrl = "https://discord.com";

    public function __construct()
    {
        parent::__construct($this->baseUrl);
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type'=>'application/json'
        ];
    }

    public function send(string $message, string $uri): void {
        try {
            $payload = [
                'content'    => $message
            ];
            $this->client->request('post',$uri,[
                'headers' => $this->getHeaders(),
                'json'    => $payload
            ]);
        } catch (GuzzleException $e) {
            \Log::error("Send Discord: ".json_encode($payload)." Error:".$e->getMessage());
        }
    }

    public function sendError(string $message):void {
        $uri = '/api/webhooks/1137646975685234749/bg2jVge6T-3DLJ2_bHMJikEmOr3N6otXY9XApUNHZEecmc8gUCMp6UywwKipEqmNkwM8';
        $this->send($message, $uri);
    }

    public function sendDeployment(string $message):void {
        $uri = '/api/webhooks/1133918920919744626/0z5Rr7vX5aUYFoetyeMeIfp2GvRtWE8zL2VlxqAC8D67fC7UEfgPuvRBzVhwdQrgiWRl';
        $this->send($message, $uri);
    }

    public function sendInfo(string $message): void {
        $uri = '/api/webhooks/1138858197399117864/1EmRO--FpF4FhRTQ5Xhe6IzlhcNSf6sxHJ5MycJqaJbzTulBkRccbvohDyco99gRpIFo';
        dd($uri);
        $this->send($message, $uri);
    }

}
