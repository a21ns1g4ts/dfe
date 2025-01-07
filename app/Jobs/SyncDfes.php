<?php

namespace App\Jobs;

use App\DfeService;
use App\Models\DFEDocs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use function Illuminate\Log\log;

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
        log('SyncDfes:handle', ['ultNSU' => $this->ultNSU]);

        $maxNSU = $this->ultNSU;
        $limit = 10;
        $iCount = 0;

        while ($this->ultNSU <= $maxNSU) {
            log('SyncDfes:handle:while', ['ultNSU' => $this->ultNSU, 'maxNSU' => $maxNSU, 'iCount' => $iCount]);
            $iCount++;

            if ($iCount >= $limit) {
                break;
            }

            $data = $dfeService->sefazDistDFe($this->ultNSU);

            if (count($data['lote']) > 0) {
                log('SyncDfes:handle:insert', ['lote' => $data['lote']]);
                DFEDocs::insert($data['lote']);
            }

            $this->ultNSU = $data['ult_nsu'];

            if ($this->ultNSU == $data['max_nsu']) {
                log('SyncDfes:handle:break', ['ultNSU' => $this->ultNSU, 'maxNSU' => $maxNSU]);
                break;
            }

            sleep(2);
        }
    }
}
