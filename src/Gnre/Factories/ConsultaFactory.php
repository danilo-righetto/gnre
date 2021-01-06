<?php

namespace PhxCargo\Gnre\Factories;
use Sped\Gnre\Sefaz\Consulta;

/**
 * Class ConsultaFactory
 * @package PhxCargo\Gnre\Factories
 */
class ConsultaFactory
{
    const TEST_ENVIRONMENT = 2;
    const PRODUCTION_ENVIRONMENT = 1;
    /**
     * @var Consulta
     */
    private $consulta;

    /**
     * ConsultaFactory constructor.
     * @param Consulta $consulta
     */
    public function __construct(Consulta $consulta)
    {
        $debug = config('phxgnre.sefaz_debug', false);
        $environment = !$debug ?
            self::PRODUCTION_ENVIRONMENT :
            self::TEST_ENVIRONMENT;

        $consulta->setEnvironment($environment);
        $consulta->utilizarAmbienteDeTeste($debug);

        $this->consulta = $consulta;
    }

    /**
     * @param int $receive
     * @return Consulta
     */
    public function create(int $receive): Consulta
    {
        $this->consulta->setRecibo($receive);
        return $this->consulta;
    }
}