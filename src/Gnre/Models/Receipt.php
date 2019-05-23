<?php

namespace PhxCargo\Gnre\Models;
/**
 * Class Receipt
 * @package PhxCargo\Gnre\Models
 */
class Receipt
{
    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * Receipt constructor.
     * @param string $number
     * @param string $timestamp
     */
    public function __construct(string $number, string $timestamp)
    {
        $this->number = $number;
        $this->timestamp = $timestamp;
    }
}