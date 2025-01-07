<?php

namespace App;

use NFePHP\Common\Certificate;
use NFePHP\NFe\Tools;

class DfeService
{
    public function getDfe($ultNSU)
    {
        $pfxContent = file_get_contents(app_path('cert.pfx'));
        $password = '170481';

        $arr = [
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

        $configJson = json_encode($arr);

        $tools = new Tools($configJson, Certificate::readPfx($pfxContent, $password));
        $tools->model('55');
        $tools->setEnvironment(2);

        return $tools->sefazDistDFe($ultNSU);
    }
}
