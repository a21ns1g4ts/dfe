<?php

namespace App\Jobs;

use App\DfeService;
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
        $limit = 10;
        $iCount = 0;

        while ($this->ultNSU <= $maxNSU) {
            $iCount++;

            if ($iCount >= $limit) {
                break;
            }

            $data = $dfeService->sefazDistDFe($this->ultNSU);

            if (count($data['lote']) > 0) {
                DfeDoc::insert($data['lote']);
            }

            $this->ultNSU = $data['ult_nsu'];

            if ($this->ultNSU == $data['max_nsu']) {
                break;
            }

            sleep(2);
        }
    }
}
