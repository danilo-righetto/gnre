<?php

namespace PhxCargo\Gnre\Factories;

use Illuminate\Support\Collection;
use PhxCargo\Gnre\Models\Shipping;
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
     * @param Lote $lote
     */
    public function __construct(GuideFactory $guideFactory, Lote $lote)
    {
        $this->guideFactory = $guideFactory;

        $lote->utilizarAmbienteDeTeste(
            config('phxgnre.sefaz_debug', false)
        );
        $this->lote = $lote;
    }

    /**
     * @param Collection|Shipping[] $shipments
     * @return Lote
     */
    public function create(Collection $shipments): Lote
    {
        $shipments->each(function (Shipping $shipping) {
            $this->guideFactory->create($shipping, $this->lote);

        });

        return $this->lote;
    }
}