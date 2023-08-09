<?php

namespace Alterindonesia\Procurex\Illuminate;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HttpProcurex {

    protected Client $client;

    public function __construct(string $baseUrl)
    {
        if(!$baseUrl) {
            return ;
        }
        $this->generateTableIntegrationLogs();
        $this->client = new Client([
            'base_uri' => $baseUrl,
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

    protected function generateTableIntegrationLogs(): void
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

    protected function getAccessToken(): ?string {
        return config('procurex.access_token',request()->bearerToken());
    }

    protected function getHeaders(): array {
        return [
            'Content-Type' => "application/json",
            'Accept' => "application/json",
            'Authorization' => 'Bearer '.$this->getAccessToken()
        ];
    }

}
