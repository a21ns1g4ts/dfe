<?php

namespace App;

use App\Models\DFEDoc;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Tools;

class DFEService
{
    protected Tools $tools;

    public static bool $testing = false;

    public static int $environment = 2; // 1 - production, 2 - homologation

    public function __construct(
        protected array $config,
        protected string $pfxContent,
        protected string $password
    ) {
        $config['tpAmb'] = $config['tpAmb'] ?? self::$environment;

        $this->tools = new Tools(json_encode($config), Certificate::readPfx($this->pfxContent, 170481));
        $this->tools->setEnvironment(self::$environment);
        $this->tools->model('55');
    }

    /**
     * Service for the distribution of summary information and
     * electronic tax documents of interest to an actor.
     *
     * @param  int  $ultNSU  last NSU number received
     * @param  int  $numNSU  NSU number you wish to consult
     * @param  string|null  $chave  Optional chave parameter
     * @param  string  $fonte  data source ('AN' or 'RS')
     */
    public function sefazDistDFe(int $ultNSU = 0, int $numNSU = 0, ?string $chave = null, string $fonte = 'AN'): ?array
    {
        try {
            $response = self::$testing
                ? file_get_contents(base_path('/tests/fixtures/dfe_response.xml'))
                : $this->tools->sefazDistDFe($ultNSU, $numNSU, $chave, $fonte);
        } catch (\Exception $e) {
            logger()->error('Error fetching DFe: '.$e->getMessage());

            return null;
        }

        return $this->parseDFeResponse($response);
    }

    /**
     * Parse the DFe response XML and extract data.
     *
     * @param  string  $response  XML response
     */
    private function parseDFeResponse(string $response): ?array
    {
        $dom = new \DOMDocument;
        $dom->loadXML($response);
        $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);

        if (! $node) {
            return null;
        }

        $data = [
            'tp_amb' => $this->getNodeValue($node, 'tpAmb'),
            'ver_aplic' => $this->getNodeValue($node, 'verAplic'),
            'c_stat' => $this->getNodeValue($node, 'cStat'),
            'x_motivo' => $this->getNodeValue($node, 'xMotivo'),
            'dh_resp' => $this->getNodeValue($node, 'dhResp'),
            'ult_nsu' => $this->getNodeValue($node, 'ultNSU'),
            'max_nsu' => $this->getNodeValue($node, 'maxNSU'),
            'lote' => $this->parseLote($node),
        ];

        return $data;
    }

    /**
     * Sync DFEs from SEFAZ and insert new records.
     *
     * @param  ?string  $ultNSU  last NSU number received
     */
    public function syncDFEs(?string $ultNSU = null): void
    {
        $ultNSU = $ultNSU ?? (DFEDoc::max('nsu') ?? 0);
        $maxNSU = $ultNSU;
        $limit = 10;
        $iCount = 0;
        $nsusToInsert = [];

        while ($ultNSU <= $maxNSU && $iCount < $limit) {
            $iCount++;

            $data = $this->sefazDistDFe($ultNSU);

            if (! empty($data['lote'])) {
                $nsus = collect($data['lote'])->pluck('nsu');
                $exists = DFEDoc::whereIn('nsu', $nsus)->pluck('nsu');

                $newNSUs = $nsus->diff($exists);
                if ($newNSUs->isNotEmpty()) {
                    $nsusToInsert = collect($data['lote'])->whereIn('nsu', $newNSUs->all())->toArray();
                }
            }

            $ultNSU = $data['ult_nsu'] ?? $ultNSU;
            if ($ultNSU == $data['max_nsu']) {
                break;
            }
        }

        if (! empty($nsusToInsert)) {
            DFEDoc::insert($nsusToInsert);
        }
    }

    /**
     * Safely extract node value from XML.
     */
    private function getNodeValue(\DOMNode $node, string $tag): ?string
    {
        $element = $node->getElementsByTagName($tag)->item(0);

        return $element ? $element->nodeValue : null;
    }

    /**
     * Parse the 'loteDistDFeInt' node for documents.
     */
    private function parseLote(\DOMNode $node): array
    {
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
        }

        return $loteData;
    }
}
