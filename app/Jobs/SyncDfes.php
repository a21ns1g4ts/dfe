<?php

namespace App\Jobs;

use App\DfeService;
use App\Models\Dfe;
use App\Models\DfeDoc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDfes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ultNSU;

    public function __construct($ultNSU = 0)
    {
        $this->ultNSU = $ultNSU;
    }

    public function handle(DfeService $dfeService)
    {
        $maxNSU = $this->ultNSU;
        $loopLimit = 12;
        $iCount = 0;

        while ($this->ultNSU <= $maxNSU) {
            $iCount++;
            if ($iCount >= $loopLimit) {
                break;
            }

            try {
                $resp = $dfeService->getDfe($this->ultNSU);
            } catch (\Exception $e) {
                logger($e->getMessage());
                break;
            }

            $dom = new \DOMDocument;
            $dom->loadXML($resp);
            $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);

            $data = [
                'tp_amb' => $node->getElementsByTagName('tpAmb')->item(0)->nodeValue,
                'ver_aplic' => $node->getElementsByTagName('verAplic')->item(0)->nodeValue,
                'c_stat' => $node->getElementsByTagName('cStat')->item(0)->nodeValue,
                'x_motivo' => $node->getElementsByTagName('xMotivo')->item(0)->nodeValue,
                'dh_resp' => $node->getElementsByTagName('dhResp')->item(0)->nodeValue,
                'ult_nsu' => $node->getElementsByTagName('ultNSU')->item(0)->nodeValue,
                'max_nsu' => $node->getElementsByTagName('maxNSU')->item(0)->nodeValue,
            ];

            $dfe = Dfe::create($data);
            $this->ultNSU = $data['ult_nsu'];
            $maxNSU = $data['max_nsu'];

            $lote = $node->getElementsByTagName('loteDistDFeInt')->item(0);

            if ($lote) {
                foreach ($lote->getElementsByTagName('docZip') as $doc) {
                    DfeDoc::create([
                        'dfe_id' => $dfe->id,
                        'nsu' => $doc->getAttribute('NSU'),
                        'schema' => $doc->getAttribute('schema'),
                        'content' => gzdecode(base64_decode($doc->nodeValue)),
                        'tipo' => substr($doc->getAttribute('schema'), 0, 6),
                    ]);
                }
            }

            if ($this->ultNSU == $maxNSU) {
                break;
            }

            sleep(2);
        }
    }
}
