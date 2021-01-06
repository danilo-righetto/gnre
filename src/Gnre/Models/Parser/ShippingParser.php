<?php /** @noinspection SpellCheckingInspection */

namespace PhxCargo\Gnre\Models\Parser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use PhxCargo\Gnre\Models\Shipping;

/**
 * Class GuideTransformer
 * @package App\Transformers
 */
class ShippingParser
{
    const TIPO_DOCUMENTO_CNPJ = "1";
    const TIPO_DOCUMENTO_CPF = "2";
    /**
     * @var Carbon
     */
    private $carbon;

    /**
     * ShippingParser constructor.
     * @param Carbon $carbon
     */
    public function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
        $this->carbon->addDay();
    }


    /**
     * @param Shipping $shipping
     * @return array
     */
    public function transform(Shipping $shipping): array
    {
        $fobCif = (float)$shipping->totalAmount * $shipping->exchangeRate;
        $ii = 0.6 * $fobCif;
        $baseCalcIcms = $fobCif + $ii;

        $additionalInformation =
            "FOB CIF: R$" . money_format('%.2n', $fobCif)
            . " I.I.: R$" . money_format('%.2n', $ii) . " 60% "
            . " BASE CALC.ICMS(FOB + I.I.): R$" . money_format('%.2n', $baseCalcIcms) . "\n"
            . " FÓRMULA: (BASE ICMS / (1 - ALIQUOTA / 100)) * (ALIQUOTA / 100)\n"
            . " VALOR ICMS: R$";

        $documentType = self::TIPO_DOCUMENTO_CNPJ;

        if ('CNPJ' != $shipping->documentType) {
            $documentType = self::TIPO_DOCUMENTO_CPF;
        }

        $parameters = [
            "c01_UfFavorecida" => $shipping->uf,
            "c02_receita" => 100056,
            "c27_tipoIdentificacaoEmitente" => self::TIPO_DOCUMENTO_CNPJ,
            "c03_idContribuinteEmitente" => config('phxgnre.sefaz_certificate_cnpj'),
            "c28_tipoDocOrigem" => config('phxgnre.sefaz_tipo_doc_origem'),
            "c04_docOrigem" => $shipping->declarationNumber,

            "c10_valorTotal" => number_format(
                $shipping->totalAmount,
                2,
                '.',
                ''
            ),

            "c06_valorPrincipal" => number_format(
                $shipping->totalAmount,
                2,
                '.',
                ''
            ),

            "retornoInformacoesComplementares" => $additionalInformation,
            "c14_dataVencimento" => $this->carbon->format("Y-m-d"),

            //Emitente
            "c16_razaoSocialEmitente" => config('phxgnre.sefaz_razao_social'),
            "c18_enderecoEmitente" => config('phxgnre.sefaz_endereco'),
            "c19_municipioEmitente" => config('phxgnre.sefaz_municipio'),
            "c20_ufEnderecoEmitente" => config('phxgnre.sefaz_uf'),
            "c21_cepEmitente" => config('phxgnre.sefaz_cep'),
            "c22_telefoneEmitente" => config('phxgnre.sefaz_telefone'),

            //Destinatário
            "c34_tipoIdentificacaoDestinatario" => $documentType,
            "c35_idContribuinteDestinatario" => $shipping->documentNumber,
            "c37_razaoSocialDestinatario" => $shipping->legalName,
            "c38_municipioDestinatario" => $shipping->cityCode,

            "c33_dataPagamento" => $this->carbon->format("Y-m-d"),
            "ano" => $this->carbon->format("Y"),
            "mes" => $this->carbon->format("m"),
            "periodo" => '1',
            "parcela" => '1',
        ];

        if ($shipping->uf == 'MT') {
            $parameters = array_merge(
                ['c25_detalhamentoReceita' => '000033'],
                $parameters
            );
        }

        return $parameters;
    }
}