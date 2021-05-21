<?php
namespace Allgood\IndicesBCB;

use SoapClient;

class IndiceBCB extends SOAPClient
{

    /*
     * constantes para os índices mais usados
     */
    const BCB_IGPM = 189;
    const BCB_INPC = 188;
    const BCB_IPCA = 433;

    private $bcb_url = "https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl";
    private $bcbIndex;

    private $ultimoValorVO = null;
    
    /**
     * Classe de conexão com o BCB, o índice utilizado por
     * default é o IGPM.
     *
     * Documentação da API no BCB está disponível em:
     * https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/sgsAjudaIng.jsp#SA
     *
     * Além das funções definidas na classe, o objeto resultante é
     * um cliente SOAP e pode acessar qualquer método publicado no
     * WSDL do webservice, porém daí é preciso sempre informar
     * os parâmetros completos esperados pelo webservice.
     *
     *  Constantes aceitas como índices:
     *     BCB_IGPM = 189    // IGP-M da FGV
     *     BCB_INPC = 188    // INPC do IBGE
     *     BCB_IPCA = 433    // IPCA do IBGE
     *
     * @param int $indice Índice a ser utilizado (default IGPM)
     * @param string $url URI para acesso ao webservice SOAP
     */
    public function __construct(int $indice = 189, string $url = null)
    {
        $this->bcbIndex = $indice;
        return parent::__construct($this->bcb_url);
    }

    /**
     * Obtém o último valor
     *
     * @param bool $ignoreCache
     * @return \stdClass
     */
    public function getUltimoValor(bool $ignoreCache = false) : \stdClass
    {
        if ($ignoreCache || is_null($this->ultimoValorVO)) {
            $this->ultimoValorVO = $this->getUltimoValorVO($this->bcbIndex);
        }
        
        return $this->ultimoValorVO->ultimoValor;
    }

    /**
     * Obtém valores do período especificado.
     *
     * @param string $inicio início do período no formato dd/mm/yyyy
     * @param string $fim final do período no formato dd/mm/yyyy
     * @return array Array de stdClass com os valores do período
     */
    public function getValoresPeriodo(string $inicio, string $fim) : array
    {
        $answer = $this->getValoresSeriesVO([ $this->bcbIndex ], $inicio, $fim);
        return $answer[0]->valores;
    }
    
    /**
     * Obtém os últimos 12 valores publicados da série.
     *
     * @return array
     */
    public function getUltimosDozeValores(): array
    {
        
        $ultimo = $this->getUltimoValor();
        $ultimoMes = $ultimo->mes;
        $ultimoAno = $ultimo->ano;
        
        $inicioMes = ($ultimoMes+1)%12;
        $inicioAno = ($ultimoMes>1) ? $ultimoAno-1 : $ultimoAno;
        
        $dataInicio = sprintf("01/%02d/%04d", $inicioMes, $inicioAno);
        $dataFim = sprintf("01/%02d/%04d", $ultimoMes, $ultimoAno);

        return $this->getValoresPeriodo($dataInicio, $dataFim);
    }

    /**
     * Calcula o índice acumulado do período.
     *
     * ATENÇÂO: Essa função só é operacional para séries que
     * representam percentuais. Testado apenas para os três
     * índices informados em constantes: IGP-M, IPCA e INPC
     *
     * @param array $valores Array de valores a calcular o acúmulo.
     * @return float
     */
    static function valorAcumuladoDoPeriodo(array $valores) : float
    {
        $acumulado = 1.0;
        foreach ($valores as $valor) {
            $acumulado = $acumulado * ( ((float)$valor->valor/100) + 1);
        }
        
        return ( ($acumulado - 1) * 100);
    }
}
