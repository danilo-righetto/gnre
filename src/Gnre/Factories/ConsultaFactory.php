<?php

namespace PhxCargo\Gnre\Factories;
use Sped\Gnre\Sefaz\Consulta;

/**
 * Class ConsultaFactory
 * @package PhxCargo\Gnre\Factories
 */
class ConsultaFactory
{
    /**
     * @param int $receive
     * @return Consulta
     */
    public function create(int $receive): Consulta
    {
        $consulta = new Consulta;
        $consulta->setRecibo($receive);
        return $consulta;
    }
}