<?php

namespace App\Console\Commands;

use App\DFEService;
use App\Jobs\SyncDFEs as JobsSyncDFEs;
use Illuminate\Console\Command;

class SyncDFEs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dfe:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync DFEs from SEFAZ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $config = [
            'atualizacao' => '2017-02-20 09:11:21',
            // 'tpAmb' => 2,
            'razaosocial' => '57.302.627 ATILA DENIS CARDOSO DA SILVA',
            'cnpj' => '57302627000114',
            'siglaUF' => 'CE',
            'schemes' => 'PL_009_V4',
            'versao' => '4.00',
            'tokenIBPT' => 'AAAAAAA',
            'CSC' => 'GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G',
            'CSCid' => '000001',
            'proxyConf' => [],
        ];

        $certificate = file_get_contents(base_path('tests/fixtures/cert.pfx'));
        $password = '170481';

        $dfeService = new DFEService($config, $certificate, $password);

        JobsSyncDFEs::dispatch($dfeService);
    }
}
