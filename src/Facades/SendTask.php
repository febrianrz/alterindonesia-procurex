<?php
namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Interfaces\TaskInterface;
use App\Models\IntegrationLog;
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
        $this->generateTableIntegrationLogs();
        $this->client = new Client([
            'base_uri' => config('procurex.service.base_url'),
            'on_stats' => function (TransferStats $stats) {
                $this->guzzleLog($stats);
            }
        ]);
    }

    protected function guzzleLog($stats)
    {
        \Alterindonesia\Procurex\Models\IntegrationLog::create([
            'url'          => $stats->getEffectiveUri()->getPath(),
            'http_code'    => $stats->getResponse()->getStatusCode(),
            'body_request' => $stats->getRequest()->getBody(),
            'response'     => $stats->getResponse()->getBody(),
            'execution'    => $stats->getTransferTime()
        ]);
    }
    public function publishTask(TaskInterface $task, $protocol="http"){
        if($protocol == "http")
            $this->http($task);
        else {
            $rabbit = new RabbitMQProducer();
            $rabbit->publishTask($task);
        }
    }

    private function http(TaskInterface $task){
        try {
            $accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBc2VhM2I3Q0FqREd3M1pZRWR5Tk5BZzMzRm84bkdWTSIsInZhbHVlIjoiZXlKcGRpSTZJbVJFV25GeWNtZFFjWHA0ZEUweVpVUmpPR3BzTlhjOVBTSXNJblpoYkhWbElqb2lhMUU0ZDNoNFZHNVpSME01YkZaYVNYUkVTelJ2YldRNWVETndWVlU1ZDA5aFVXWk9VWGd2UWpGc1pFcFpaak5ZT1daelpqVm5NMUExT1hseVZUUkJlbUppTm5WWFRuWmlNM2xEZUc1TGJuZGFUSGRSUVVWUVdESXdhMFl2YUVka2NFZFFibFZYUTI5QkwwaHNUakJXYzNaRlkyUlRVR2RVZFdobE5pczRjVTFHVjBWNFJYQnZVa3BoTVV4eU0yazRRbGxxUlZWS2JuRkRRVXRRV0dKdVJIRmhSblJRWlhKV0swWmhVWEJ1VEZsd09XUlZVMWhJY0ZseE0xazRlQ3RDYXpSWFMyMUNVMlJEY0hsRloyMTFOMllyTHpkRFoxZDVNRmdyT0dGMlNtaHVaRGwyYmpGNWVFcDNWak5VYVZWTU9YWkVTV3huVm5KcU5VcG5SaXRNYVdOT1J6aEdXazVNZUhScmJXdE1SR2xPUWxkb2FrY3ZUaTl3Tm1kWFNDc3lZV0kyY0dGRWIxRnFjMFl2UkVOdVZGRnFRUzgzWVcxYU5rTk5Xa3hIUldWclRFVm1hMU52TDNweFpYWlNURTFyUXk5SWIyZG9XR054VWpkYWVEWklZbU5FUzFodmVtZFFOMDgzYTBzM1JUUlJLMnN6WkdWSE5tUnliRkpWZEc0MGMwUTNNU3RSY1VoV1RFOHdXWFJKV1cxc2RrSjBOR0ZFTnpNNVZtSjRibVJpYUhjemRESllRamRMTVhScmQyWkZUelE0ZVVGUE9HRkNTSEJoVHpGbWFtZGtVVkF6VWxWWWNIUlRZVE5yVTJ4bWRsRkxMelJCYzJsVmIyNWhhRFZUTDJoRFowWTBOa0Z0VXk5d1ZYWk1NamR6VWpaT00zY3dOR3BUV2tkbk9HbFlkRVZoU0dKSWRHdDBlak0yYkc4eFdrWTFWV2cyZURsNFJEUlFWV2RTU2tGNWIxTm1WbEJIWkZOWE9XZzBXRGQxYm1vclNGbzNkbkV3WTI1ek4zWlhZWEJrTVRsMFZETkRhblpNYTFaalJrcFBkamh5THk5RWNqUm1SemtyU1RkMVExQlRjRzk1Wkd0ck1EaE9NMm8yTVZBNFZHSkVVVGR1Wm5odU1HUlNRemczVEdackwzWmhkVkYzU2t0TlVVazRObTFzUmpOc2IxcFJhVUV3WjJ0UmNHNU9LMFo0ZVZCaldsUm9ZblZ1U2t0eWJucElXblppYWtodldtUnRXbU12SzBab1NFWXJlRUo1WVdKT2FIZFBjVVpCVjNCVFJqUlNSREpLV2pOamIzbFlaRTQyYUZoM1FWZHRWRk5LUW1OeU9FSmlUVFYxZEZaelVrMDVjRFZOUnpkSlNrZFJZWEYzTWtodFNTdDBRVUl2WlVsQllXNW1kWEI2WWxwUGQzaHVka3cwU1dWUE5FZG5SME5vYkhGbmN6WkxPRUphZEU5Mk56WjZWV3AzU0dwbVUxbFlZaTlOYUVKdlVVSkxWakJtU1ZnclpIRktWelJtWkRRdlZYZzRReTlFVDNsblpHMTRaM2t6Wm5oTGFuWXlNbk00WTA0MllsZDBPVk54YWxWM1EzaElVeTkyYlRNeWVGaHRXVUp1UVRWTFdFNHdRMVJyU1ROeU9UTXhRWFZ3ZUZaS1ZrRjVSamhzUlUxU09EQjJWMjV6WTJacWRIcFBjM0JHVUU5cE9VYzFhRnBKZG1kamVsSjRUR014Y1V4RU5IWXhPVFpwYVRGUWIwSjZTbElyTWtZeVQzVm1XR1p6S3lzMU5IZFBPWGxRSzFaa2ExTlBOVFIzYldKSWMwTmFPRkV4VGs1V1VsWnhhRU1yWldOYVFtNU1jVzlKV2tsb1dtRnJVM1JPWkN0Qk1HdDVaMkZMZEUxdlZuSm5TVVUxZUhFMVdtcDZSelJUVUdsWWJIRlVSbEZwVkdOYVoyMVRNR1pTYUd4QllWTkxUMU13YjJkWE1FVjJiaTlMVHpneFVUbEZjMHN2VDFCaVYyTXZieTh4WWtOaEsxcDNRVGxWYmxrelZ6aGxSM296VFV0SmVFNUVOM0UwV214eWQwZDVVMXBZVjJGeE5FRkdNVnBxTlRReGVrSk1VSEJ6V0Zkbk4yNXFTU3RyT1hseFZ6aFJUbEpGYUhsSmJFOHpiVUZzVVhSTlFVOVVlVzkyTkZGcVEyUTJZM3BsY0VReFZrMTZSVGgwU0RnMmQxTk5hRTU2YlZOTU5rdFhSblpIVjFvNVNrVkJiMUJaVVRSc05reHlTWGhMWm5OcWFEaDRZbWx0Vmt0TFlsQlZTSFJOUkZSQ1RGaGpNWGh4VUZaclptUkNNMmcwY0hvd2MzaERWVTVqWTNVdmIwWmxUMlIxZHpGSksxbHpZVXREVTJKMlNscFFXbmd4WjNWcmMyOXdjbnBhVkZaNldUUmpWakJQVDJkbGIwbGlZbVZMT1dOU2NrVlpOMHR6VWxkcWExQk5haXRLVDFCRldYUndVMVpOTkRaNlVqZEhMemhaTUZWcU5IbGxlVkJMWkV0SlVGaE9OMGRoU2tkeGJqTjJORGxpV1dzNWVXODNRVEI0T0hGaGJFMVVWVFpLYWxsemVUQmpkRmxSUzBRMU5FTkxkbmxhZFdWb2NIQkdRWE5NUldwMllUaDFOMUZVY0d0Uk5GRTBSbHBFVUVaT1NHSmtOWE4yUVhSMk9GVnZTekZOVlUxbk1sQmxTa1UzYVRGVk9VaHVNV3B6V0ZsdE5WWjFOSHBIWkRKQmJYRnFLekpzVTFSeFF6SmpabVZsWTJONVR6azJkV2R2YzNaR1JHSlJXamhvTW5RclVWZFVkRVJaVUZCSk5IUlZVa1pNUnk5WFQzaFBiMlY0WTBWRWFtZ3dObTFuVjBGcVVUaHhOV3gwUVVZd2FuWkpRa0k1TURCM1RtTm5lRlJhUTBRM2NVVnhiMjgzY1dOdlFtOWtkVXQ1YTFwc1RHWXhOMnRSWmpabE4wRlJVSGxHUml0b05tMUxVUzlKUTJSdlpWaFBkWFlyUVhvMlJXa3lWV3RQTHpOQ1NsSnRTbFJhTnpkeVFXRlRTamw2YWpsQmFtTjNUQ3RzTXpGMlUwSjRVR1ZDVlUxYWJFNUhTblZCVERJd2VtWTViV0VyVG1jM09VdzRaMWRpVWpsUlJFdFhlakU1ZVhwTWVGQndUbWs1WmxwcWNDdG1OeXR2U21WRFkyUjZjRUprYW1KWFVEVXljRE5oUzJSMWEzTTBSamRMVkZZclZrd3hlVzl3V1RsV05tRlBSRlJTUnpkQldUSXdSRXhUWVZFMVZVeEtSVzAzZVVkTWJUVnFSMjh4Y0hSQ1ltZDZNRE4wVW10TFFYVmFObGx4UTFwTGNIcFROVGt6VTI1VFpERkRUVmxRUWpCa00yUXZjRkJ4Y3pBeFNTOWFTV1ZzWWpWYWRGaGpUREJ4ZUZOc09EVXZVRXhHV0dSUGRFdHlZVk5hVGs5TlNTOVpUVXR1Um05WGFFeHdlVkkyYVhjMGIwSkdhRTV4WWpSaVMxVm9Zbk5SWldWd1NUSm1VR2hFYlU0clRUWTFWbWxrWlZkelMyTkRUalZOZW5Oc09DOTZLMHhUTW01c2NFeEdNRU5GZWtGVVZtaDZkR1I2ZUdSSWRIQlVUMmgyU1d3eUwzZFpLelpRTnpOU1lXRk1WVWx4YVNzNEt6ZzFhbEpRTDNoTU9YZFJSV1pDTlhGeWNVeFBibWs0YW1Gc1dWUTFXRzVKUVRCbVpWSkVaRTV0Wm5CS2VtNUtUbXB3UTJ0dFFrOXBZVVpEU1hwR05HcHdlRzg0Y200M2VsUnJiMGt2VkdOcU5UbEpWRk42UW5ObFVqRm1kVzB6ZW5FeU5VWlpablpKVlV0SlJHVXdXR2xaWWtSYU5uVmhiblpMU1ZkMU5pdDVWWGRPYnpJME1VVmlSM0ZZVWxvd0wxSktTSE5GTVUxa1oxSk1jMWxPWVZCMWJGVXdUMmxEY1ZFMGJUQjJZMGhKYmtsU2JVNWhVVVJOWm1OelZGaFdUMXAxV0V0VWIwdEpNRE5aWVc5eGQzVmtZVFpFVEdWcmVrZFRTV3MyUjFCYWEwNUZNblpLWVVaMFJrVmxTVGRJV0dOUFptY3pVMkpWU1dWQk4xaGxZa2hUUW5sWFJIVlhkQ3Q1ZDAxRlZXb3JZV3BCZDNGWVVrMVZPVlJuVFdOMmNqSktVVzVsVEhsMGFuVnpiMjlCTmpWQk1FNXNjWGhVSzIwMmJuazVVRXhzVG1SRVoxWkdXbHBLVkVGbWJuZFFNMFJaV0VsQk0zWklRVEl4Y3pWRGJ6WkJjemhpYmxacVlUSnBSRmgxYUZnNGQzTndTV3huTTFkRmFXcFNhRW8xU1N0NU5reG9SSFl5V1daRGVtSk1ibUprTVhKVlozZHJhM2Q2Um5BMmNuUkdibXR4UTBsaFZqRlFlSEZOZFROSlRFOU5SbEZRUzBGSFNuZExTVmhKTkZkQlExTXhiMkZNT1ROd2NXc3JOM2haUVV3d2VHSm1Temd2UzNwV1NUWTVhelEyUlRCNWJUUndOSEl6ZVhsWEwycHBTVzlqTVc1UFlrNUJPRWx3YlZOcmJWVkpWR2c1VVdaa09Hb3hTVkkyZW1Sc2RWSXllbWQ0YWtGSk1sTnBTamxKWmxZeWFXWmFlbUZ0YWtWbU1DOVZjMFJpTTJGNGJUWXdUM2xMUlRKdVFuUlNXR0p6Y1VkNksxaEJSamRrWkROTVdFWTRlRzVSTHpCMlQwTmpSekZTVGpRMU5YWnZjMmt6VnpSVVMxUnZSMjlqVTJWR1NUTjNkejA5SWl3aWJXRmpJam9pTkRreU9HVmxZVGd4T0RRMk1qWTNPV0pqWXpreU5UWTROVEUzTkRObU56TTFZalU0WVRobE9EVmhPVEU1T0RRek1XUXhPREUwTVRobU9HRTFNR1V5TWlJc0luUmhaeUk2SWlKOSIsImV4cGlyZWRfYXQiOjE2OTEwMzY4MzZ9.QLhi9Nub-2VuBuAH3tv1qhmHPqPgIysUammBA2RGWIk";
            $payload = [
                'type'    => $task->type(),
                'data'    => $task->payload(),
            ];
            $this->client->request('post','/api/task/event',[
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

    private function generateTableIntegrationLogs() {
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

    public function whatsapp($to,$message,$type='01'){
        try {
            $accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBc2VhM2I3Q0FqREd3M1pZRWR5Tk5BZzMzRm84bkdWTSIsInZhbHVlIjoiZXlKcGRpSTZJbVJFV25GeWNtZFFjWHA0ZEUweVpVUmpPR3BzTlhjOVBTSXNJblpoYkhWbElqb2lhMUU0ZDNoNFZHNVpSME01YkZaYVNYUkVTelJ2YldRNWVETndWVlU1ZDA5aFVXWk9VWGd2UWpGc1pFcFpaak5ZT1daelpqVm5NMUExT1hseVZUUkJlbUppTm5WWFRuWmlNM2xEZUc1TGJuZGFUSGRSUVVWUVdESXdhMFl2YUVka2NFZFFibFZYUTI5QkwwaHNUakJXYzNaRlkyUlRVR2RVZFdobE5pczRjVTFHVjBWNFJYQnZVa3BoTVV4eU0yazRRbGxxUlZWS2JuRkRRVXRRV0dKdVJIRmhSblJRWlhKV0swWmhVWEJ1VEZsd09XUlZVMWhJY0ZseE0xazRlQ3RDYXpSWFMyMUNVMlJEY0hsRloyMTFOMllyTHpkRFoxZDVNRmdyT0dGMlNtaHVaRGwyYmpGNWVFcDNWak5VYVZWTU9YWkVTV3huVm5KcU5VcG5SaXRNYVdOT1J6aEdXazVNZUhScmJXdE1SR2xPUWxkb2FrY3ZUaTl3Tm1kWFNDc3lZV0kyY0dGRWIxRnFjMFl2UkVOdVZGRnFRUzgzWVcxYU5rTk5Xa3hIUldWclRFVm1hMU52TDNweFpYWlNURTFyUXk5SWIyZG9XR054VWpkYWVEWklZbU5FUzFodmVtZFFOMDgzYTBzM1JUUlJLMnN6WkdWSE5tUnliRkpWZEc0MGMwUTNNU3RSY1VoV1RFOHdXWFJKV1cxc2RrSjBOR0ZFTnpNNVZtSjRibVJpYUhjemRESllRamRMTVhScmQyWkZUelE0ZVVGUE9HRkNTSEJoVHpGbWFtZGtVVkF6VWxWWWNIUlRZVE5yVTJ4bWRsRkxMelJCYzJsVmIyNWhhRFZUTDJoRFowWTBOa0Z0VXk5d1ZYWk1NamR6VWpaT00zY3dOR3BUV2tkbk9HbFlkRVZoU0dKSWRHdDBlak0yYkc4eFdrWTFWV2cyZURsNFJEUlFWV2RTU2tGNWIxTm1WbEJIWkZOWE9XZzBXRGQxYm1vclNGbzNkbkV3WTI1ek4zWlhZWEJrTVRsMFZETkRhblpNYTFaalJrcFBkamh5THk5RWNqUm1SemtyU1RkMVExQlRjRzk1Wkd0ck1EaE9NMm8yTVZBNFZHSkVVVGR1Wm5odU1HUlNRemczVEdackwzWmhkVkYzU2t0TlVVazRObTFzUmpOc2IxcFJhVUV3WjJ0UmNHNU9LMFo0ZVZCaldsUm9ZblZ1U2t0eWJucElXblppYWtodldtUnRXbU12SzBab1NFWXJlRUo1WVdKT2FIZFBjVVpCVjNCVFJqUlNSREpLV2pOamIzbFlaRTQyYUZoM1FWZHRWRk5LUW1OeU9FSmlUVFYxZEZaelVrMDVjRFZOUnpkSlNrZFJZWEYzTWtodFNTdDBRVUl2WlVsQllXNW1kWEI2WWxwUGQzaHVka3cwU1dWUE5FZG5SME5vYkhGbmN6WkxPRUphZEU5Mk56WjZWV3AzU0dwbVUxbFlZaTlOYUVKdlVVSkxWakJtU1ZnclpIRktWelJtWkRRdlZYZzRReTlFVDNsblpHMTRaM2t6Wm5oTGFuWXlNbk00WTA0MllsZDBPVk54YWxWM1EzaElVeTkyYlRNeWVGaHRXVUp1UVRWTFdFNHdRMVJyU1ROeU9UTXhRWFZ3ZUZaS1ZrRjVSamhzUlUxU09EQjJWMjV6WTJacWRIcFBjM0JHVUU5cE9VYzFhRnBKZG1kamVsSjRUR014Y1V4RU5IWXhPVFpwYVRGUWIwSjZTbElyTWtZeVQzVm1XR1p6S3lzMU5IZFBPWGxRSzFaa2ExTlBOVFIzYldKSWMwTmFPRkV4VGs1V1VsWnhhRU1yWldOYVFtNU1jVzlKV2tsb1dtRnJVM1JPWkN0Qk1HdDVaMkZMZEUxdlZuSm5TVVUxZUhFMVdtcDZSelJUVUdsWWJIRlVSbEZwVkdOYVoyMVRNR1pTYUd4QllWTkxUMU13YjJkWE1FVjJiaTlMVHpneFVUbEZjMHN2VDFCaVYyTXZieTh4WWtOaEsxcDNRVGxWYmxrelZ6aGxSM296VFV0SmVFNUVOM0UwV214eWQwZDVVMXBZVjJGeE5FRkdNVnBxTlRReGVrSk1VSEJ6V0Zkbk4yNXFTU3RyT1hseFZ6aFJUbEpGYUhsSmJFOHpiVUZzVVhSTlFVOVVlVzkyTkZGcVEyUTJZM3BsY0VReFZrMTZSVGgwU0RnMmQxTk5hRTU2YlZOTU5rdFhSblpIVjFvNVNrVkJiMUJaVVRSc05reHlTWGhMWm5OcWFEaDRZbWx0Vmt0TFlsQlZTSFJOUkZSQ1RGaGpNWGh4VUZaclptUkNNMmcwY0hvd2MzaERWVTVqWTNVdmIwWmxUMlIxZHpGSksxbHpZVXREVTJKMlNscFFXbmd4WjNWcmMyOXdjbnBhVkZaNldUUmpWakJQVDJkbGIwbGlZbVZMT1dOU2NrVlpOMHR6VWxkcWExQk5haXRLVDFCRldYUndVMVpOTkRaNlVqZEhMemhaTUZWcU5IbGxlVkJMWkV0SlVGaE9OMGRoU2tkeGJqTjJORGxpV1dzNWVXODNRVEI0T0hGaGJFMVVWVFpLYWxsemVUQmpkRmxSUzBRMU5FTkxkbmxhZFdWb2NIQkdRWE5NUldwMllUaDFOMUZVY0d0Uk5GRTBSbHBFVUVaT1NHSmtOWE4yUVhSMk9GVnZTekZOVlUxbk1sQmxTa1UzYVRGVk9VaHVNV3B6V0ZsdE5WWjFOSHBIWkRKQmJYRnFLekpzVTFSeFF6SmpabVZsWTJONVR6azJkV2R2YzNaR1JHSlJXamhvTW5RclVWZFVkRVJaVUZCSk5IUlZVa1pNUnk5WFQzaFBiMlY0WTBWRWFtZ3dObTFuVjBGcVVUaHhOV3gwUVVZd2FuWkpRa0k1TURCM1RtTm5lRlJhUTBRM2NVVnhiMjgzY1dOdlFtOWtkVXQ1YTFwc1RHWXhOMnRSWmpabE4wRlJVSGxHUml0b05tMUxVUzlKUTJSdlpWaFBkWFlyUVhvMlJXa3lWV3RQTHpOQ1NsSnRTbFJhTnpkeVFXRlRTamw2YWpsQmFtTjNUQ3RzTXpGMlUwSjRVR1ZDVlUxYWJFNUhTblZCVERJd2VtWTViV0VyVG1jM09VdzRaMWRpVWpsUlJFdFhlakU1ZVhwTWVGQndUbWs1WmxwcWNDdG1OeXR2U21WRFkyUjZjRUprYW1KWFVEVXljRE5oUzJSMWEzTTBSamRMVkZZclZrd3hlVzl3V1RsV05tRlBSRlJTUnpkQldUSXdSRXhUWVZFMVZVeEtSVzAzZVVkTWJUVnFSMjh4Y0hSQ1ltZDZNRE4wVW10TFFYVmFObGx4UTFwTGNIcFROVGt6VTI1VFpERkRUVmxRUWpCa00yUXZjRkJ4Y3pBeFNTOWFTV1ZzWWpWYWRGaGpUREJ4ZUZOc09EVXZVRXhHV0dSUGRFdHlZVk5hVGs5TlNTOVpUVXR1Um05WGFFeHdlVkkyYVhjMGIwSkdhRTV4WWpSaVMxVm9Zbk5SWldWd1NUSm1VR2hFYlU0clRUWTFWbWxrWlZkelMyTkRUalZOZW5Oc09DOTZLMHhUTW01c2NFeEdNRU5GZWtGVVZtaDZkR1I2ZUdSSWRIQlVUMmgyU1d3eUwzZFpLelpRTnpOU1lXRk1WVWx4YVNzNEt6ZzFhbEpRTDNoTU9YZFJSV1pDTlhGeWNVeFBibWs0YW1Gc1dWUTFXRzVKUVRCbVpWSkVaRTV0Wm5CS2VtNUtUbXB3UTJ0dFFrOXBZVVpEU1hwR05HcHdlRzg0Y200M2VsUnJiMGt2VkdOcU5UbEpWRk42UW5ObFVqRm1kVzB6ZW5FeU5VWlpablpKVlV0SlJHVXdXR2xaWWtSYU5uVmhiblpMU1ZkMU5pdDVWWGRPYnpJME1VVmlSM0ZZVWxvd0wxSktTSE5GTVUxa1oxSk1jMWxPWVZCMWJGVXdUMmxEY1ZFMGJUQjJZMGhKYmtsU2JVNWhVVVJOWm1OelZGaFdUMXAxV0V0VWIwdEpNRE5aWVc5eGQzVmtZVFpFVEdWcmVrZFRTV3MyUjFCYWEwNUZNblpLWVVaMFJrVmxTVGRJV0dOUFptY3pVMkpWU1dWQk4xaGxZa2hUUW5sWFJIVlhkQ3Q1ZDAxRlZXb3JZV3BCZDNGWVVrMVZPVlJuVFdOMmNqSktVVzVsVEhsMGFuVnpiMjlCTmpWQk1FNXNjWGhVSzIwMmJuazVVRXhzVG1SRVoxWkdXbHBLVkVGbWJuZFFNMFJaV0VsQk0zWklRVEl4Y3pWRGJ6WkJjemhpYmxacVlUSnBSRmgxYUZnNGQzTndTV3huTTFkRmFXcFNhRW8xU1N0NU5reG9SSFl5V1daRGVtSk1ibUprTVhKVlozZHJhM2Q2Um5BMmNuUkdibXR4UTBsaFZqRlFlSEZOZFROSlRFOU5SbEZRUzBGSFNuZExTVmhKTkZkQlExTXhiMkZNT1ROd2NXc3JOM2haUVV3d2VHSm1Temd2UzNwV1NUWTVhelEyUlRCNWJUUndOSEl6ZVhsWEwycHBTVzlqTVc1UFlrNUJPRWx3YlZOcmJWVkpWR2c1VVdaa09Hb3hTVkkyZW1Sc2RWSXllbWQ0YWtGSk1sTnBTamxKWmxZeWFXWmFlbUZ0YWtWbU1DOVZjMFJpTTJGNGJUWXdUM2xMUlRKdVFuUlNXR0p6Y1VkNksxaEJSamRrWkROTVdFWTRlRzVSTHpCMlQwTmpSekZTVGpRMU5YWnZjMmt6VnpSVVMxUnZSMjlqVTJWR1NUTjNkejA5SWl3aWJXRmpJam9pTkRreU9HVmxZVGd4T0RRMk1qWTNPV0pqWXpreU5UWTROVEUzTkRObU56TTFZalU0WVRobE9EVmhPVEU1T0RRek1XUXhPREUwTVRobU9HRTFNR1V5TWlJc0luUmhaeUk2SWlKOSIsImV4cGlyZWRfYXQiOjE2OTEwMzY4MzZ9.QLhi9Nub-2VuBuAH3tv1qhmHPqPgIysUammBA2RGWIk";
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

    public function email(
        $to,
        $subject,
        $message,
        $cc = [],
        $bcc = [],
        $attachments = [],
    ){
        try {
            $accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBc2VhM2I3Q0FqREd3M1pZRWR5Tk5BZzMzRm84bkdWTSIsInZhbHVlIjoiZXlKcGRpSTZJbVJFV25GeWNtZFFjWHA0ZEUweVpVUmpPR3BzTlhjOVBTSXNJblpoYkhWbElqb2lhMUU0ZDNoNFZHNVpSME01YkZaYVNYUkVTelJ2YldRNWVETndWVlU1ZDA5aFVXWk9VWGd2UWpGc1pFcFpaak5ZT1daelpqVm5NMUExT1hseVZUUkJlbUppTm5WWFRuWmlNM2xEZUc1TGJuZGFUSGRSUVVWUVdESXdhMFl2YUVka2NFZFFibFZYUTI5QkwwaHNUakJXYzNaRlkyUlRVR2RVZFdobE5pczRjVTFHVjBWNFJYQnZVa3BoTVV4eU0yazRRbGxxUlZWS2JuRkRRVXRRV0dKdVJIRmhSblJRWlhKV0swWmhVWEJ1VEZsd09XUlZVMWhJY0ZseE0xazRlQ3RDYXpSWFMyMUNVMlJEY0hsRloyMTFOMllyTHpkRFoxZDVNRmdyT0dGMlNtaHVaRGwyYmpGNWVFcDNWak5VYVZWTU9YWkVTV3huVm5KcU5VcG5SaXRNYVdOT1J6aEdXazVNZUhScmJXdE1SR2xPUWxkb2FrY3ZUaTl3Tm1kWFNDc3lZV0kyY0dGRWIxRnFjMFl2UkVOdVZGRnFRUzgzWVcxYU5rTk5Xa3hIUldWclRFVm1hMU52TDNweFpYWlNURTFyUXk5SWIyZG9XR054VWpkYWVEWklZbU5FUzFodmVtZFFOMDgzYTBzM1JUUlJLMnN6WkdWSE5tUnliRkpWZEc0MGMwUTNNU3RSY1VoV1RFOHdXWFJKV1cxc2RrSjBOR0ZFTnpNNVZtSjRibVJpYUhjemRESllRamRMTVhScmQyWkZUelE0ZVVGUE9HRkNTSEJoVHpGbWFtZGtVVkF6VWxWWWNIUlRZVE5yVTJ4bWRsRkxMelJCYzJsVmIyNWhhRFZUTDJoRFowWTBOa0Z0VXk5d1ZYWk1NamR6VWpaT00zY3dOR3BUV2tkbk9HbFlkRVZoU0dKSWRHdDBlak0yYkc4eFdrWTFWV2cyZURsNFJEUlFWV2RTU2tGNWIxTm1WbEJIWkZOWE9XZzBXRGQxYm1vclNGbzNkbkV3WTI1ek4zWlhZWEJrTVRsMFZETkRhblpNYTFaalJrcFBkamh5THk5RWNqUm1SemtyU1RkMVExQlRjRzk1Wkd0ck1EaE9NMm8yTVZBNFZHSkVVVGR1Wm5odU1HUlNRemczVEdackwzWmhkVkYzU2t0TlVVazRObTFzUmpOc2IxcFJhVUV3WjJ0UmNHNU9LMFo0ZVZCaldsUm9ZblZ1U2t0eWJucElXblppYWtodldtUnRXbU12SzBab1NFWXJlRUo1WVdKT2FIZFBjVVpCVjNCVFJqUlNSREpLV2pOamIzbFlaRTQyYUZoM1FWZHRWRk5LUW1OeU9FSmlUVFYxZEZaelVrMDVjRFZOUnpkSlNrZFJZWEYzTWtodFNTdDBRVUl2WlVsQllXNW1kWEI2WWxwUGQzaHVka3cwU1dWUE5FZG5SME5vYkhGbmN6WkxPRUphZEU5Mk56WjZWV3AzU0dwbVUxbFlZaTlOYUVKdlVVSkxWakJtU1ZnclpIRktWelJtWkRRdlZYZzRReTlFVDNsblpHMTRaM2t6Wm5oTGFuWXlNbk00WTA0MllsZDBPVk54YWxWM1EzaElVeTkyYlRNeWVGaHRXVUp1UVRWTFdFNHdRMVJyU1ROeU9UTXhRWFZ3ZUZaS1ZrRjVSamhzUlUxU09EQjJWMjV6WTJacWRIcFBjM0JHVUU5cE9VYzFhRnBKZG1kamVsSjRUR014Y1V4RU5IWXhPVFpwYVRGUWIwSjZTbElyTWtZeVQzVm1XR1p6S3lzMU5IZFBPWGxRSzFaa2ExTlBOVFIzYldKSWMwTmFPRkV4VGs1V1VsWnhhRU1yWldOYVFtNU1jVzlKV2tsb1dtRnJVM1JPWkN0Qk1HdDVaMkZMZEUxdlZuSm5TVVUxZUhFMVdtcDZSelJUVUdsWWJIRlVSbEZwVkdOYVoyMVRNR1pTYUd4QllWTkxUMU13YjJkWE1FVjJiaTlMVHpneFVUbEZjMHN2VDFCaVYyTXZieTh4WWtOaEsxcDNRVGxWYmxrelZ6aGxSM296VFV0SmVFNUVOM0UwV214eWQwZDVVMXBZVjJGeE5FRkdNVnBxTlRReGVrSk1VSEJ6V0Zkbk4yNXFTU3RyT1hseFZ6aFJUbEpGYUhsSmJFOHpiVUZzVVhSTlFVOVVlVzkyTkZGcVEyUTJZM3BsY0VReFZrMTZSVGgwU0RnMmQxTk5hRTU2YlZOTU5rdFhSblpIVjFvNVNrVkJiMUJaVVRSc05reHlTWGhMWm5OcWFEaDRZbWx0Vmt0TFlsQlZTSFJOUkZSQ1RGaGpNWGh4VUZaclptUkNNMmcwY0hvd2MzaERWVTVqWTNVdmIwWmxUMlIxZHpGSksxbHpZVXREVTJKMlNscFFXbmd4WjNWcmMyOXdjbnBhVkZaNldUUmpWakJQVDJkbGIwbGlZbVZMT1dOU2NrVlpOMHR6VWxkcWExQk5haXRLVDFCRldYUndVMVpOTkRaNlVqZEhMemhaTUZWcU5IbGxlVkJMWkV0SlVGaE9OMGRoU2tkeGJqTjJORGxpV1dzNWVXODNRVEI0T0hGaGJFMVVWVFpLYWxsemVUQmpkRmxSUzBRMU5FTkxkbmxhZFdWb2NIQkdRWE5NUldwMllUaDFOMUZVY0d0Uk5GRTBSbHBFVUVaT1NHSmtOWE4yUVhSMk9GVnZTekZOVlUxbk1sQmxTa1UzYVRGVk9VaHVNV3B6V0ZsdE5WWjFOSHBIWkRKQmJYRnFLekpzVTFSeFF6SmpabVZsWTJONVR6azJkV2R2YzNaR1JHSlJXamhvTW5RclVWZFVkRVJaVUZCSk5IUlZVa1pNUnk5WFQzaFBiMlY0WTBWRWFtZ3dObTFuVjBGcVVUaHhOV3gwUVVZd2FuWkpRa0k1TURCM1RtTm5lRlJhUTBRM2NVVnhiMjgzY1dOdlFtOWtkVXQ1YTFwc1RHWXhOMnRSWmpabE4wRlJVSGxHUml0b05tMUxVUzlKUTJSdlpWaFBkWFlyUVhvMlJXa3lWV3RQTHpOQ1NsSnRTbFJhTnpkeVFXRlRTamw2YWpsQmFtTjNUQ3RzTXpGMlUwSjRVR1ZDVlUxYWJFNUhTblZCVERJd2VtWTViV0VyVG1jM09VdzRaMWRpVWpsUlJFdFhlakU1ZVhwTWVGQndUbWs1WmxwcWNDdG1OeXR2U21WRFkyUjZjRUprYW1KWFVEVXljRE5oUzJSMWEzTTBSamRMVkZZclZrd3hlVzl3V1RsV05tRlBSRlJTUnpkQldUSXdSRXhUWVZFMVZVeEtSVzAzZVVkTWJUVnFSMjh4Y0hSQ1ltZDZNRE4wVW10TFFYVmFObGx4UTFwTGNIcFROVGt6VTI1VFpERkRUVmxRUWpCa00yUXZjRkJ4Y3pBeFNTOWFTV1ZzWWpWYWRGaGpUREJ4ZUZOc09EVXZVRXhHV0dSUGRFdHlZVk5hVGs5TlNTOVpUVXR1Um05WGFFeHdlVkkyYVhjMGIwSkdhRTV4WWpSaVMxVm9Zbk5SWldWd1NUSm1VR2hFYlU0clRUWTFWbWxrWlZkelMyTkRUalZOZW5Oc09DOTZLMHhUTW01c2NFeEdNRU5GZWtGVVZtaDZkR1I2ZUdSSWRIQlVUMmgyU1d3eUwzZFpLelpRTnpOU1lXRk1WVWx4YVNzNEt6ZzFhbEpRTDNoTU9YZFJSV1pDTlhGeWNVeFBibWs0YW1Gc1dWUTFXRzVKUVRCbVpWSkVaRTV0Wm5CS2VtNUtUbXB3UTJ0dFFrOXBZVVpEU1hwR05HcHdlRzg0Y200M2VsUnJiMGt2VkdOcU5UbEpWRk42UW5ObFVqRm1kVzB6ZW5FeU5VWlpablpKVlV0SlJHVXdXR2xaWWtSYU5uVmhiblpMU1ZkMU5pdDVWWGRPYnpJME1VVmlSM0ZZVWxvd0wxSktTSE5GTVUxa1oxSk1jMWxPWVZCMWJGVXdUMmxEY1ZFMGJUQjJZMGhKYmtsU2JVNWhVVVJOWm1OelZGaFdUMXAxV0V0VWIwdEpNRE5aWVc5eGQzVmtZVFpFVEdWcmVrZFRTV3MyUjFCYWEwNUZNblpLWVVaMFJrVmxTVGRJV0dOUFptY3pVMkpWU1dWQk4xaGxZa2hUUW5sWFJIVlhkQ3Q1ZDAxRlZXb3JZV3BCZDNGWVVrMVZPVlJuVFdOMmNqSktVVzVsVEhsMGFuVnpiMjlCTmpWQk1FNXNjWGhVSzIwMmJuazVVRXhzVG1SRVoxWkdXbHBLVkVGbWJuZFFNMFJaV0VsQk0zWklRVEl4Y3pWRGJ6WkJjemhpYmxacVlUSnBSRmgxYUZnNGQzTndTV3huTTFkRmFXcFNhRW8xU1N0NU5reG9SSFl5V1daRGVtSk1ibUprTVhKVlozZHJhM2Q2Um5BMmNuUkdibXR4UTBsaFZqRlFlSEZOZFROSlRFOU5SbEZRUzBGSFNuZExTVmhKTkZkQlExTXhiMkZNT1ROd2NXc3JOM2haUVV3d2VHSm1Temd2UzNwV1NUWTVhelEyUlRCNWJUUndOSEl6ZVhsWEwycHBTVzlqTVc1UFlrNUJPRWx3YlZOcmJWVkpWR2c1VVdaa09Hb3hTVkkyZW1Sc2RWSXllbWQ0YWtGSk1sTnBTamxKWmxZeWFXWmFlbUZ0YWtWbU1DOVZjMFJpTTJGNGJUWXdUM2xMUlRKdVFuUlNXR0p6Y1VkNksxaEJSamRrWkROTVdFWTRlRzVSTHpCMlQwTmpSekZTVGpRMU5YWnZjMmt6VnpSVVMxUnZSMjlqVTJWR1NUTjNkejA5SWl3aWJXRmpJam9pTkRreU9HVmxZVGd4T0RRMk1qWTNPV0pqWXpreU5UWTROVEUzTkRObU56TTFZalU0WVRobE9EVmhPVEU1T0RRek1XUXhPREUwTVRobU9HRTFNR1V5TWlJc0luUmhaeUk2SWlKOSIsImV4cGlyZWRfYXQiOjE2OTEwMzY4MzZ9.QLhi9Nub-2VuBuAH3tv1qhmHPqPgIysUammBA2RGWIk";
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


}
