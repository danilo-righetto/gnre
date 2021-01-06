<?php

namespace PhxCargo\Gnre\Models;

use Illuminate\Support\Carbon;

/**
 * Class Gnre
 * @package PhxCargo\Gnre\Models
 */
class Gnre
{
    /** @var int */
    public $status;

    /**
     * Use for external control
     * @var string
     */
    public $identifiableCode;

    /**
     * @var string
     */
    public $declarationNumber;

    /**
     * @var bool|string
     */
    public $uf;

    /**
     * @var bool|string
     */
    public $dueDate;

    /**
     * @var bool|string
     */
    public $referenceDate;

    /**
     * @var bool|string
     */
    public $totalAmount;

    /**
     * @var bool|string
     */
    public $restatement;

    /**
     * @var bool|string
     */
    public $barcode;

    /** @var string */
    public $importerName;

    /**
     * @var bool|string
     */
    public $importerDocType;

    /**
     * @var bool|string
     */
    public $importerDocument;

    /**
     * @return Carbon
     */
    public function getDueDate(): ?Carbon
    {
        if (empty($this->dueDate)) {
            return null;
        }

        preg_match("/^([0-9]{2})([0-9]{2})([0-9]{4})$/", $this->dueDate, $date);

        unset($date[0]);

        try {
            return new Carbon(implode('-', array_reverse($date)));
        } catch (\Exception $e) {
            return null;
        }
    }
}