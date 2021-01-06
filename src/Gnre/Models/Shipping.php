<?php

namespace PhxCargo\Gnre\Models;

/**
 * Class Shipping
 * @package PhxCargo\Gnre\Models
 */
class Shipping
{
    /**
     * Use for external control
     * @var string
     */
    public $identifiableCode;

    /** @var float */
    public $declarationNumber;

    /** @var float */
    public $totalAmount;

    /** @var float */
    public $exchangeRate;

    /**
     * Importer
     */

    /** @var string */
    public $uf;

    /** @var int */
    public $cityCode;

    /** @var string */
    public $legalName;

    /** @var string */
    public $documentType;

    /** @var string */
    public $documentNumber;
}