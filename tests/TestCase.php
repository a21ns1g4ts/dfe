<?php

namespace Tests;

use App\DFEService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    // use RefreshDatabase;

    protected DFEService $dfeService;

    protected function setUp(): void
    {
        parent::setUp();

        DFEService::$testing = true;

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

        $certificate = file_get_contents(__DIR__.'/fixtures/cert.pfx');
        $password = '170481';

        $this->dfeService = new DFEService($config, $certificate, $password);
    }
}
