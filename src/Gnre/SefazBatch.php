<?php

namespace PhxCargo\Gnre;

use DOMDocument;
use Illuminate\Support\Collection;
use PhxCargo\Gnre\Factories\BatchFactory;
use PhxCargo\Gnre\Factories\Webservice\ConnectionFactory;
use PhxCargo\Gnre\Models\Receipt;
use Sped\Gnre\Render\Html;
use Sped\Gnre\Render\Pdf;

/**
 * Class EnviarLoteSefaz
 * @package PhxCargo\Gnre
 */
class SefazBatch
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
     * @var BatchFactory
     */
    private $batchFactory;

    /**
     * EnviarLoteSefaz constructor.
     * @param DefaultSetUp $defaultSetUp
     * @param BatchFactory $batchFactory
     * @param ConnectionFactory $connectionFactory
     */
    public function __construct(
        DefaultSetUp $defaultSetUp,
        BatchFactory $batchFactory,
        ConnectionFactory $connectionFactory
    ) {
        $this->defaultSetUp = $defaultSetUp;
        $this->batchFactory = $batchFactory;
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @param Collection $airwaybills
     * @return Receipt
     */
    public function send(Collection $airwaybills): Receipt
    {
        $lote = $this->batchFactory->create($airwaybills);

        $connection = $this->connectionFactory->create(
            $this->defaultSetUp,
            $lote->getHeaderSoap(),
            $lote->toXml()
        );
        $response = $connection->doRequest($lote->soapAction());

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($response);
        $receipt = $doc->getElementsByTagName("recibo");

        $number = $receipt->item(0)->getElementsByTagName('numero')->item(0)->nodeValue;
        $timestamp = $receipt->item(0)->getElementsByTagName('dataHoraRecibo')->item(0)->nodeValue;

        return new Receipt($number, $timestamp);
    }

    /**
     * @param $lote
     */
    public function generatePdf($lote): void
    {
        $html = new Html();
        $html->create($lote);
        $pdf = new Pdf();
        $pdf->create($html)->stream('phxgnre.pdf', ['Attachment' => 0]);
    }
}