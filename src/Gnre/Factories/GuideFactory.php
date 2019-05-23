<?php

namespace PhxCargo\Gnre\Factories;

use PhxCargo\Gnre\Models\Parser\AirwaybillParser;
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
     * @var AirwaybillParser
     */
    private $airwaybillParser;

    /**
     * GuideFactory constructor.
     * @param AirwaybillParser $airwaybillParser
     */
    public function __construct(AirwaybillParser $airwaybillParser)
    {
        $this->airwaybillParser = $airwaybillParser;
    }

    /**
     * @param $airwaybill
     * @param Lote $lote
     */
    public function create($airwaybill, Lote $lote)
    {
        foreach ($airwaybill['items'] as $item) {
            $guide = $this->airwaybillParser->transform($item, $airwaybill);
            $lote->addGuia($this->fillGuide($guide));
            break;
        }
    }

    /**
     * @param array $guide
     * @return Guia
     */
    public function fillGuide(array $guide): Guia
    {
        $guideObject = new Guia();

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