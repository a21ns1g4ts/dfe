<?php

namespace App;

use NFePHP\Common\Certificate;
use NFePHP\NFe\Tools;

class DFEService
{
    protected Tools $tools;

    public static bool $testing = false;

    public static int $environment = 2; // 1 - production, 2 - homologation

    public function __construct(protected array $config, protected string $pfxContent, protected string $password)
    {
        $config['tpAmb'] = $config['tpAmb'] ?? self::$environment;

        $this->tools = new Tools(json_encode($config), Certificate::readPfx($this->pfxContent, $this->password));
        $this->tools->setEnvironment(self::$environment);
        $this->tools->model('55');
    }

    /**
     * Service for the distribution of summary information and
     * electronic tax documents of interest to an actor.
     *
     * @param  int  $ultNSU  last NSU number recived
     * @param  int  $numNSU  NSU number you wish to consult
     * @param  string  $fonte  data source 'AN' and for some cases it may be 'RS'
     */
    public function sefazDistDFe(int $ultNSU = 0, int $numNSU = 0, ?string $chave = null, string $fonte = 'AN'): ?array
    {
        try {
            $response = self::$testing
                ? file_get_contents(base_path('/tests/fixtures/dfe_response.xml'))
                : $this->tools->sefazDistDFe($ultNSU, $numNSU, $chave, $fonte);
        } catch (\Exception $e) {
            logger($e->getMessage());

            return null;
        }

        $dom = new \DOMDocument;
        $dom->loadXML($response);
        $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);

        $data = [
            'tp_amb' => $node->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'ver_aplic' => $node->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'c_stat' => $node->getElementsByTagName('cStat')->item(0)->nodeValue,
            'x_motivo' => $node->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dh_resp' => $node->getElementsByTagName('dhResp')->item(0)->nodeValue,
            'ult_nsu' => $node->getElementsByTagName('ultNSU')->item(0)->nodeValue,
            'max_nsu' => $node->getElementsByTagName('maxNSU')->item(0)->nodeValue,
            'lote' => [],
        ];

        $lote = $node->getElementsByTagName('loteDistDFeInt')->item(0);

        $loteData = [];

        if ($lote) {
            foreach ($lote->getElementsByTagName('docZip') as $doc) {
                $loteData[] = [
                    'nsu' => $doc->getAttribute('NSU'),
                    'schema' => $doc->getAttribute('schema'),
                    'content' => gzdecode(base64_decode($doc->nodeValue)),
                    'tipo' => substr($doc->getAttribute('schema'), 0, 6),
                ];
            }

            $data['lote'] = $loteData;
        }

        return $data;
    }
}
