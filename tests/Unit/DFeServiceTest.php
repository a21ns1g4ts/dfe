<?php

use App\DFEService;

it('runs the dfe process successfully', function () {
    DFEService::$testing = true;

    $config = [
        'atualizacao' => '2017-02-20 09:11:21',
        'tpAmb' => 2,
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

    $certificate = file_get_contents(__DIR__.'./../fixtures/cert.pfx');
    $password = '170481';

    $service = new DFEService($config, $certificate, $password);

    $result = $service->sefazDistDFe('000000000006811', '000000000006991');

    expect($result)->toBeArray();
    expect($result['c_stat'])->toBe('138');
    expect($result['ult_nsu'])->toBe('000000000006811');
    expect($result['max_nsu'])->toBe('000000000006991');
    expect($result['lote'])->toBeArray();
});
