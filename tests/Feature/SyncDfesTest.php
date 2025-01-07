<?php

use App\DfeService;

it('logs and processes DFe data correctly', function () {
    $dfeService = $this->mock(DfeService::class);
    $dfeService->shouldReceive('sefazDistDFe')->andReturn([
        'ult_nsu' => 10,
        'max_nsu' => 10,
        'lote' => [
            ['nsu' => 1, 'schema' => 'schema1', 'content' => 'content1', 'tipo' => 'tipo1'],
            ['nsu' => 2, 'schema' => 'schema2', 'content' => 'content2', 'tipo' => 'tipo2'],
            ['nsu' => 3, 'schema' => 'schema3', 'content' => 'content3', 'tipo' => 'tipo3'],
        ],
    ]);

    $syncDFES = new \App\Jobs\SyncDFES($dfeService);
    $syncDFES->handle();

    $this->assertDatabaseCount('dfe_docs', 3);
    $this->assertDatabaseHas('dfe_docs', ['nsu' => 1, 'schema' => 'schema1', 'content' => 'content1', 'tipo' => 'tipo1']);
    $this->assertDatabaseHas('dfe_docs', ['nsu' => 2, 'schema' => 'schema2', 'content' => 'content2', 'tipo' => 'tipo2']);
    $this->assertDatabaseHas('dfe_docs', ['nsu' => 3, 'schema' => 'schema3', 'content' => 'content3', 'tipo' => 'tipo3']);
});
