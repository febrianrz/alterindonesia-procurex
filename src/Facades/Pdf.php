<?php
namespace Alterindonesia\Procurex\Facades;

use GuzzleHttp\Client;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class Pdf {

    private array $files;
    public function __construct(
        $files
    ){
        $this->files = $files;
    }


    public function mergePdf($files=[], $nameOfMergedFile=""): array
    {
        $fileNames = [];
        foreach($files as $file) {
            $fileNames[] = $this->downloadFile($file);
        }
        $oMerger = PDFMerger::init();
        foreach ($fileNames as $fileName){
            $oMerger->addPDF(storage_path('app/temp/'.$fileName), 'all');
        }

        $oMerger->merge();
        $filename = $nameOfMergedFile;
        $path = storage_path('app/public/'.$filename);
        $oMerger->save($path);
        return [
            'filename' => $filename,
            'path' => $path
        ];
    }

    private function downloadFile($url): bool|string
    {
        $client = new Client();
        try {
            $response = $client->get($url);
            $filename = \Str::random(20).time().'.pdf';
            \Storage::disk('local')->put('temp/'.$filename, $response->getBody());
            return $filename;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function mergeFromURL($files=[],$fileName="",$mediaTypeId=16): string
    {
        $pdf = new self($files);
        $arr = $pdf->mergePdf($files,$fileName);
        $result = $pdf->sentToMedia($fileName,config('procurex.access_token'),$mediaTypeId);
        return $result['data']['url'];
    }

    private function sentToMedia($filename,$accessToken=null,$mediaTypeId=16) {
        $http = \Http::withHeaders([
            'Authorization' => 'Bearer '.$accessToken,
            'Accept' => 'application/json'
        ])->attach('file', file_get_contents(storage_path('app/public/'.$filename)), $filename)
            ->post(config('procurex.service_base_url').'/api/media/media',
                [
                    'media_type_id' => $mediaTypeId,
                    'disk'  => 'gcs'
                ]);
        $response = $http->json();
        return $response;
    }
}
