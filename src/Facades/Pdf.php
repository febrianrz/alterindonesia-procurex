<?php
namespace Alterindonesia\Procurex\Facades;

use GuzzleHttp\Client;

class Pdf {

    private $files = [];
    public function __construct(
        $files
    ){
        $this->files = $files;
    }


    public function mergePdf($files=[]) {
        $fileNames = [];
        foreach($files as $file) {
            $fileNames[] = $this->downloadFile($file);
        }
        dd($fileNames);
    }

    private function downloadFile($url) {
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

    public static function mergeFromURL($files=[]){
        $pdf = new self($files);
        return $pdf->mergePdf($files);
    }
}
