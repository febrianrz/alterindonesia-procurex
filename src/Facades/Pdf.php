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


    public function mergePdf($files=[]): string
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
        $path = storage_path('app/public/MERGED_'.\Str::random(20).time().'.pdf');
        $oMerger->save($path);
        return $path;
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

    public static function mergeFromURL($files=[]): string
    {
        $pdf = new self($files);
        return $pdf->mergePdf($files);
    }
}
