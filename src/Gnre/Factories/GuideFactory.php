<?php

namespace PhxCargo\Gnre\Factories;

use PhxCargo\Gnre\Models\Parser\ShippingParser;
use PhxCargo\Gnre\Models\Shipping;
use Sped\Gnre\Exception\UndefinedProperty;
use Sped\Gnre\Sefaz\Guia;
use Sped\Gnre\Sefaz\Lote;

/**
 * Class GuideFactory
 * @package PhxCargo\Gnre\Factories
 */
class GuideFactory
{
    /**
     * @var ShippingParser
     */
    private $shippingParser;

    /**
     * GuideFactory constructor.
     * @param ShippingParser $shippingParser
     */
    public function __construct(ShippingParser $shippingParser)
    {
        $this->shippingParser = $shippingParser;
    }

    /**
     * @param Shipping $shipping
     * @param Lote $lote
     */
    public function create(Shipping $shipping, Lote $lote)
    {
        $guide = $this->shippingParser->transform($shipping);
        $lote->addGuia($this->fillGuide($guide));
    }

    /**
     * @param array $guide
     * @return Guia
     */
    private function fillGuide(array $guide): Guia
    {
        $guideObject = new Guia;
        foreach ($guide as $field => $value) {
            try {
                $guideObject->__set($field, $value);
            } catch (UndefinedProperty $e) {
                # do nothing
            }
        }

        return $guideObject;
    }
}