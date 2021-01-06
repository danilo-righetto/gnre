<?php

namespace PhxCargo\Gnre;

use DOMDocument;
use Illuminate\Support\Arr;
use PhxCargo\Gnre\Factories\ConsultaFactory;
use PhxCargo\Gnre\Factories\Webservice\ConnectionFactory;
use PhxCargo\Gnre\Models\Gnre;

/**
 * Class CheckSefazResponse
 * @package PhxCargo\Gnre
 */
class CheckSefazResponse
{
    /**
     * @var DefaultSetUp
     */
    private $defaultSetUp;
    /**
     * @var ConnectionFactory
     */
    private $connectionFactory;
    /**
     * @var ConsultaFactory
     */
    private $consultaFactory;

    /**
     * CheckSefazResponse constructor.
     * @param DefaultSetUp $defaultSetUp
     * @param ConnectionFactory $connectionFactory
     * @param ConsultaFactory $consultaFactory
     */
    public function __construct(
        DefaultSetUp $defaultSetUp,
        ConnectionFactory $connectionFactory,
        ConsultaFactory $consultaFactory
    ) {
        $this->defaultSetUp = $defaultSetUp;
        $this->connectionFactory = $connectionFactory;
        $this->consultaFactory = $consultaFactory;
    }

    /**
     * @param int $receiptNumber
     * @return \Illuminate\Support\Collection
     */
    public function request($receiptNumber)
    {
        $consulta = $this->consultaFactory->create($receiptNumber);

        $connection = $this->connectionFactory->create(
            $this->defaultSetUp,
            $consulta->getHeaderSoap(),
            $consulta->toXml()
        );

        $response = $connection->doRequest($consulta->soapAction());

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($response);

        $result = $doc->getElementsByTagName("TResultLote_GNRE");
        $number = $result->item(0)->getElementsByTagName('resultado')->item(0)->nodeValue;

        $lines = explode("\n", $number);
        $header = Arr::pull($lines, 0);
        $footer = Arr::pull($lines, count($lines));

        $batch = collect();
        foreach ($lines as $line) {
            $gnre = new Gnre;
            $gnre->status = substr($line, 5, 1);
            $gnre->declarationNumber = substr($line, 544, 18);
            $gnre->uf = substr($line, 6, 2);
            $gnre->dueDate = substr($line, 892, 8);
            $gnre->referenceDate = substr($line, 909, 6);
            $gnre->totalAmount = substr($line, 918, 15);
            $gnre->restatement = substr($line, 933, 15);
            $gnre->barcode = substr($line, 1026, 44);
            $gnre->importerDocType = substr($line, 222, 1);
            $gnre->importerDocument = substr($line, 223, 16);

            $batch->push($gnre);
        }

        return $batch;
    }
}