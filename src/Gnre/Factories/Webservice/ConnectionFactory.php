<?php

namespace PhxCargo\Gnre\Factories\Webservice;

use PhxCargo\Gnre\DefaultSetUp;
use Sped\Gnre\Webservice\Connection;

/**
 * Class ConnectionFactory
 * @package PhxCargo\Gnre\Factories\Webservice
 */
class ConnectionFactory extends \Sped\Gnre\Webservice\ConnectionFactory
{
    /**
     * @param DefaultSetUp $setup
     * @param array $headers
     * @param string $data
     * @return Connection
     */
    public function create(DefaultSetUp $setup, $headers = [], $data = ''): Connection
    {
        $connection = new Connection($setup, $headers, $data);
        $connection->addCurlOption([
            CURLOPT_SSLVERSION => 32,
            CURLOPT_CAINFO => $setup->getCertificationChain(),
        ]);

        return $connection;
    }
}