<?php

namespace PhxCargo\Gnre\Factories;

use Sped\Gnre\Sefaz\Lote;

/**
 * Class BatchFactory
 * @package PhxCargo\Gnre\Factories
 */
class BatchFactory
{
    /**
     * @var Lote
     */
    private $lote;
    /**
     * @var GuideFactory
     */
    private $guideFactory;

    /**
     * BatchFactory constructor.
     * @param GuideFactory $guideFactory
     */
    public function __construct(GuideFactory $guideFactory)
    {
        $this->guideFactory = $guideFactory;
    }

    /**
     * @param array $parameters
     * @return Lote
     */
    public function create(array $parameters): Lote
    {
        $lote = new Lote();
        $lote->utilizarAmbienteDeTeste(env("APP_DEBUG", true));

        # todo Guides must be generated for awb, not for items
        foreach ($parameters as $airwaybill) {
            $this->guideFactory->create($airwaybill, $lote);
            break;
        }
        return $lote;
    }
}