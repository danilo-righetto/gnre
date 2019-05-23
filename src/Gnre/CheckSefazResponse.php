<?php

namespace PhxCargo\Gnre;

use PhxCargo\Gnre\Factories\ConsultaFactory;
use PhxCargo\Gnre\Factories\Webservice\ConnectionFactory;

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

    public function request()
    {
        $consulta = $this->consultaFactory->create(1902089062);

        $connection = $this->connectionFactory->create(
            $this->defaultSetUp,
            $consulta->getHeaderSoap(),
            $consulta->toXml()
        );
        $request = $connection->doRequest($consulta->soapAction());
        dd($request);
    }
}