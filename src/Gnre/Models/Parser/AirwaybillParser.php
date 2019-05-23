<?php /** @noinspection ALL */

namespace PhxCargo\Gnre\Models\Parser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class GuideTransformer
 * @package App\Transformers
 */
class AirwaybillParser
{
    /**
     * @param array $item
     * @param array $airwaybill
     * @return array
     */
    public function transform(array $item, array $airwaybill): array
    {
        $brlCurrency = $this->getCurrentCurrency();

        $fobCif = floatval($item['commercial_value']) * $brlCurrency;
        $ii = 0.6 * $fobCif;
        $baseCalcIcms = $fobCif + $ii;
        $retornoInformacoesComplementares =
            "FOB CIF: R$" . money_format('%.2n', $fobCif)
            . " I.I.: R$" . money_format('%.2n', $ii) . " 60% "
            . " BASE CALC.ICMS(FOB + I.I.): R$" . money_format('%.2n', $baseCalcIcms) . "\n"
            . " FÓRMULA: (BASE ICMS / (1 - ALIQUOTA / 100)) * (ALIQUOTA / 100)\n"
            . " VALOR ICMS: R$";

        return [
            "c01_UfFavorecida" => $airwaybill['importer']['addresses'][0]['state'], #todo
            "c02_receita" => 100056, # currently hardcoded at harpia system
            "c25_detalhamentoReceita" => '000033',
            "c27_tipoIdentificacaoEmitente" => 1,
            "c03_idContribuinteEmitente" => config('gnre.sefaz_certificate_cnpj'),
            "c28_tipoDocOrigem" => config('gnre.sefaz_tipo_doc_origem'),
            "c04_docOrigem" => '190000992436', // #todo => check Valor dire

            "c06_valorPrincipal" => floatval(str_replace(',', '.', $item['commercial_value'])),
            // "c10_valorTotal" => floatval(str_replace(',', '.', $item['commercial_value'])),
            "retornoInformacoesComplementares" => $retornoInformacoesComplementares,
            "c14_dataVencimento" => date("Y-m-d"),

            //Emitente
            "c16_razaoSocialEmitente"       => config('gnre.sefaz_razao_social'),
            "c17_inscricaoEstadualEmitente" => config('gnre.sefaz_inscricao_estadual'),
            "c18_enderecoEmitente"          => config('gnre.sefaz_endereco'),
            "c19_municipioEmitente"         => config('gnre.sefaz_municipio'),
            "c20_ufEnderecoEmitente"        => config('gnre.sefaz_uf'),
            "c21_cepEmitente"               => config('gnre.sefaz_cep'),
            "c22_telefoneEmitente"          => config('gnre.sefaz_telefone'),

            //Destinatário
            "c34_tipoIdentificacaoDestinatario" => $airwaybill['importer']['documents'][0]['type'] == 'CNPJ' ? 1 : 2,
            "c35_idContribuinteDestinatario" => 44396372809,
            // "c36_inscricaoEstadualDestinatario" => 796050190118, # todo use phx doccuments to get this information
            "c37_razaoSocialDestinatario" => $airwaybill['importer']['name'],
            "c38_municipioDestinatario" => 27408, # todo check ibge city code

            "c33_dataPagamento" => date("Y-m-d"),
            "ano" => date("Y"),
            "mes" => date("m"),
            "periodo" => '0',
            "parcela" => '1',
        ];
    }

    /**
     * @param Client $client
     * @return mixed
     */
    private function getCurrentCurrency()
    {
        $client = new Client;
        try {
            $result = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=USD');
            $brlCurrency = json_decode($result->getBody())->rates->BRL;
        } catch (GuzzleException $e) {
            # do nothing
            return 4;
        }
        return $brlCurrency;
    }
}