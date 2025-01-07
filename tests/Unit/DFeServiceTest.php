<?php

it('runs the dfe process successfully', function () {
    $result = $this->dfeService->sefazDistDFe('000000000006811', '000000000006991');

    expect($result)->toBeArray();
    expect($result['c_stat'])->toBe('138');
    expect($result['ult_nsu'])->toBe('000000000006811');
    expect($result['max_nsu'])->toBe('000000000006991');
    expect($result['lote'])->toBeArray();
});
