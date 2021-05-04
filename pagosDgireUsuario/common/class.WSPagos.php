<?php

require_once("config.php");
require_once("class.debug.php");
require_once("class.abstracto.php");
require_once("tcpdf_min/tcpdf.php");
require_once("xml-rcp/lib/xmlrpc.inc");
require_once ("../libs/email/PHPMailerAutoload.php");

$ErrUser = 200;
define("WSP_SUCCESS", $ErrUser++);
define("WSP_ERROR", $ErrUser++);
define("WSP_VACIO", $ErrUser++);

/**
 * Class WSPagos
 */
class WSPagos extends Abstracto
{
    /**
     * @var string
     */
    var $user;

    /**
     * @var string
     */
    var $password;

    /**
     * @var string
     */
    var $dependencia;

    /**
     * @var string
     */
    var $ejercicio;

    /**
     * @var string
     */
    var $subDep;

    /**
     * @var string
     */
    var $origen;

    /**
     * @var int
     */
    var $tipo_moneda;

    /**
     * @var string
     */
    var $cve_moneda;

    /**
     * @var string
     */
    var $url;

    /**
     * @var string
     */
    var $method;

    /**
     * @var array
     */
    var $arraymethod;

    /**
     * @var stdClass
     */
    var $response;

    /**
     * @var string
     * tipo de servidor
     * stage= pruebas
     * live = produccion
     */
    const type = 'stage';

    /**
     * @var boolean
     * activar o desactivar el gzip para el xml-rcp
     */
    const gzip = false;

    /**
     * WSPagos constructor.
     */


    public function __construct($xuser = false, $xpassword = false, $xdependencia = false, $xejercicio = false, $xsubDep = false, $xorigen = false, $xtipo_moneda = false, $xcve_moneda = false, $xurlBase = false){

        global $CONFIG;

        $this->user = base64_decode($xuser ? $xuser : $CONFIG->patronato->user);
        $this->password = base64_decode($xpassword ? $xpassword : $CONFIG->patronato->password);
        $this->dependencia = $xdependencia ? $xdependencia : $CONFIG->patronato->dependencia;
        $this->ejercicio = $xejercicio ? $xejercicio : $CONFIG->patronato->ejercicio;
        $this->subDep = $xsubDep ? $xsubDep : $CONFIG->patronato->subDep;
        $this->origen = $xorigen ? $xorigen : $CONFIG->patronato->origen;
        $this->tipo_moneda = $xtipo_moneda ? $xtipo_moneda : $CONFIG->patronato->tipo_moneda;
        $this->cve_moneda = $xcve_moneda ? $xcve_moneda : $CONFIG->patronato->cve_moneda;
        $this->urlBase = $xurlBase ? $xurlBase : $CONFIG->patronato->urlBase;

        $this->response = new \stdClass;
        $this->arraymethod = [

            //Emisión de fichas de depósito
            'fichadeposito.datos' => [
                'stage' => $this->urlBase.'wscfdspruebas/fichadepositoN3.php',
                'live' => '',
            ],
            //Consulta de movimientos bancarios por referencia
            'consultabancoreferencia.srv' => [
                'stage' => $this->urlBase.'wstiendapruebas/srv.php',
                'live' => '',
            ],
            'genera_cfdi.v1' => [
                'stage' => $this->urlBase.'wscfdspruebas/siiewebN.php',
                'live' => '',
            ],
            'inserttc.datos' => [
                'stage' => $this->urlBase.'wscfdspruebas/siiewebN.php',
                'live' => '',
            ],
            'fgeneratck.datos' => [
                'stage' => $this->urlBase.'wscfdspruebas/consultatck.php',
                'live' => '',
            ],
        ];

    }


    public function setUser($xuser){
        $this->user = $xuser;
    }

    public function setPassword($xpassword){
        $this->password = $xpassword;
    }

    public function setDependencia($xdependencia){
        $this->dependencia = $xdependencia;
    }

    public function setEjercicio($xejercicio){
        $this->ejercicio = $xejercicio;
    }

    public function setSubDep($xsubDep){
        $this->subDep = $xsubDep;
    }

    public function setOrigen($xorigen){
        $this->origen = $xorigen;
    }

    public function setTipoMoneda($xtipo_moneda){
        $this->tipo_moneda = $xtipo_moneda;
    }

    public function setCveMoneda($xcve_moneda){
        $this->cve_moneda = $xcve_moneda;
    }

    public function setUrlBase($xurlBase){
        $this->urlBase = $xurlBase;
    }


    /**
     *
     */
    public function I_Init()
    {
        $this->response = new \stdClass;
    }

    /**
     * @param $method
     */
    function setMethod($method)
    {
        $this->I_Init();
        $this->url = $this->arraymethod[$method][WSPagos::type];
        $this->method = $method;
    }

    /**
     * asignar method fichadeposito.datos
     * Inserta los datos y crea la ficha de deposito
     * Parametro de Salida  error
     * WSP_VACIO = 201 vacio
     * WSP_ERROR = 202 se genero un error en el web services
     * Succes
     * WSP_SUCCESS = 200
     * guarda el documento en images/doc/
     * y enla funcion getResponse retorna los parametros de salida, documento = referencia.pdf
     * nom_Dep, nom_subdep, fecha, convenio , referencia)
     *
     * @param array $lstConceptos
     *
     * @return int
     */
    function getFichaDeposito($lstConceptos = [])
    {
        $this->setMethod('fichadeposito.datos');
        if ($this->getReferencia($lstConceptos) == WSP_SUCCESS) {
            $lstConceptos = (object) $lstConceptos;
            $this->createFichaDeposito(
                $this->response->fecha
                ,
                $this->response->convenio
                ,
                $this->response->referencia
                ,
                $lstConceptos->concepto
                ,
                $lstConceptos->importe
            );

            return WSP_SUCCESS;
        }

        return WSP_ERROR;
    }

    /**/
    function getFichaTicket($lstTicket = [])
    {
        $this->setMethod('fgeneratck.datos');
        if ($this->getTicket($lstTicket) == WSP_SUCCESS) {
            $lstTicket = (object) $lstTicket;

            return WSP_SUCCESS;
        }


        return WSP_ERROR;

    }
    /**/

    /**
     * @param date $fecha
     * @param string $convenio
     * @param string $referencia
     * @param string $concepto
     * @param double $importe
     *
     * @return string
     */
    function createFichaDeposito($fecha, $convenio, $referencia, $concepto, $importe)
    {
        global $MyDebug;

        try {
            ob_clean();
            
            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator("DGIRE");
            $pdf->SetAuthor('DGIRE');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                $l = '';
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // add a page
            $pdf->AddPage('L', 'A4');

            $pdf->SetXY(30, 20);
            $pdf->Image(
                __DIR__ . '/../fichas/ficha2.jpg',
                '',
                '',
                210,
                100,
                '',
                '',
                '',
                false,
                300,
                '',
                false,
                false,
                1,
                false,
                false,
                false
            );

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // print a block of text using Write()
            $pdf->SetFont('helvetica', '', 8, '', true);
            $pdf->SetXY(110, 47.8);
            $pdf->Write(0, $fecha, '', 0, '', true, 0, false, false, 0);

            $pdf->SetFont('helvetica', '', 15, '', true);
            $pdf->SetXY(53.2, 80);
            $pdf->Write(0, $convenio, '', 0, '', true, 0, false, false, 0);

            $pdf->SetFont('helvetica', '', 15, '', true);
            $pdf->SetXY(101.5, 80);
            $pdf->Write(0, $referencia, '', 0, '', true, 0, false, false, 0);

            $pdf->SetFont('helvetica', '', 8, '', true);
            $pdf->SetXY(65, 95.9);
            $pdf->Write(0, $concepto, '', 0, '', true, 0, false, false, 0);

            $pdf->SetFont('helvetica', '', 10, '', true);
            $pdf->SetXY(203, 101.5);
            $pdf->Write(0, "$" . $importe, '', 0, '', true, 0, false, false, 0);

            $pdf->SetXY(42, 110);

            // define barcode style
            $style = [
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => [0, 0, 0],
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 6,
                'stretchtext' => 4,
            ];

            $pdf->write1DBarcode($referencia, 'C128A', '', '', '', 10, 0.3, $style, 'N');

            ob_end_clean();
            
            $pdf->Output(__DIR__ . "/../fichas/doc/" . $referencia . '.pdf', 'F');
           
            $this->response->dir_documento = "../fichas/doc/" . $referencia . '.pdf';
            $this->response->documento = $referencia .'.pdf';

            return WSP_SUCCESS;
        
        } catch (Exception $ex) {
            $MyDebug->DebugMessage("WSPagos::createFichaDeposito2:" . $ex->getMessage());
            $this->response->error = $ex->getMessage();

            return WSP_ERROR;
        }
    }
    
    
    /**
   * descargar ficha deposito
   * @param $fecha
   * @param $convenio
   * @param $referencia
   * @param $concepto
   * @param $importe
   * @return int
   */
    
    function DescargarFichaDeposito($fecha, $convenio, $referencia, $concepto, $importe)
    {
    global $MyDebug;

        try {   
                ob_clean();
                // create new PDF document
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
                // set document information
                $pdf->SetCreator("DGIRE");
                $pdf->SetAuthor('DGIRE');
        
                // remove default header/footer
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
        
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        
                // set auto page breaks
                $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        
                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
                // set some language-dependent strings (optional)
                if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                    $l = '';
                    require_once(dirname(__FILE__) . '/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }
        
                // add a page
                $pdf->AddPage('L', 'A4');
        
                $pdf->SetXY(30, 20);
                $pdf->Image(
                     __DIR__ . '/../fichas/ficha2.jpg',
                    '',
                    '',
                    210,
                    100,
                    '',
                    '',
                    '',
                    false,
                    300,
                    '',
                    false,
                    false,
                    1,
                    false,
                    false,
                    false
                );
        
                // set default font subsetting mode
                $pdf->setFontSubsetting(true);
        
                // print a block of text using Write()
                $pdf->SetFont('helvetica', '', 8, '', true);
                $pdf->SetXY(110, 47.8);
                $pdf->Write(0, $fecha, '', 0, '', true, 0, false, false, 0);
        
                $pdf->SetFont('helvetica', '', 15, '', true);
                $pdf->SetXY(53.2, 80);
                $pdf->Write(0, $convenio, '', 0, '', true, 0, false, false, 0);
        
                $pdf->SetFont('helvetica', '', 15, '', true);
                $pdf->SetXY(101.5, 80);
                $pdf->Write(0, $referencia, '', 0, '', true, 0, false, false, 0);
        
                $pdf->SetFont('helvetica', '', 8, '', true);
                $pdf->SetXY(65, 95.9);
                $pdf->Write(0, $concepto, '', 0, '', true, 0, false, false, 0);
        
                $pdf->SetFont('helvetica', '', 10, '', true);
                $pdf->SetXY(203, 101.5);
                $pdf->Write(0, "$" . $importe, '', 0, '', true, 0, false, false, 0);
        
                $pdf->SetXY(42, 110);
        
                // define barcode style
                $style = [
                    'position' => '',
                    'align' => 'C',
                    'stretch' => false,
                    'fitwidth' => true,
                    'cellfitalign' => '',
                    'border' => false,
                    'hpadding' => 'auto',
                    'vpadding' => 'auto',
                    'fgcolor' => [0, 0, 0],
                    'bgcolor' => false, //array(255,255,255),
                    'text' => true,
                    'font' => 'helvetica',
                    'fontsize' => 6,
                    'stretchtext' => 4,
                ];
        
                $pdf->write1DBarcode($referencia, 'C128A', '', '', '', 10, 0.3, $style, 'N');
        
                ob_end_clean();
                $pdf->Output( $referencia . '.pdf', 'D');
                $this->response->documento = $referencia . '.pdf';
        
                return WSP_SUCCESS;
        } catch (Exception $ex) {
            $MyDebug->DebugMessage("WSPagos::createFichaDeposito2:" . $ex->getMessage());
            $this->response->error = $ex->getMessage();
    
            return WSP_ERROR;
            }
    
    }

    /**
     *  method fichadeposito.datos
     *
     * @param array $lstConceptos
     *
     * @return int
     */
    public function getReferencia($lstConceptos = [])
    {
        global $MyDebug;
        $param = [];
        $obsconcepto = (object) $lstConceptos;

        if (count(
                $lstConceptos
            ) == 0 || !isset($obsconcepto->tipoPago) || !isset($obsconcepto->importe) || !isset($obsconcepto->concepto)
        ) {

            $MyDebug->DebugMessage("WSPagos::getReferencia:Elemeto vacio");

            return WSP_VACIO;
        }
        try {
            $data = [
                'Dependencia' => $this->dependencia
                ,
                'Subdep' => $this->subDep
                ,
                'Origen' => $this->origen
                ,
                'Cve_moneda' => $this->cve_moneda
                ,
                'Tipo_pago' => $obsconcepto->tipoPago
                ,
                'Importe' => $obsconcepto->importe
                ,
                'Concepto' => $obsconcepto->concepto,

            ];

            $param[] = php_xmlrpc_encode($data);

            $msg = new xmlrpcmsg($this->method, $param);
            unset($param);

            $client = new xmlrpc_client($this->url);
            $client->setAcceptedCompression(self::gzip);
            $client->setRequestCompression(self::gzip);
            $client->setCredentials($this->user, $this->password);
            $respuesta = $client->send($msg);
            unset($client);
            unset($msg);
            if ($respuesta->faultCode()) {
                $MyDebug->DebugMessage("WSPagos::getReferencia:" . $respuesta->faultString());

                return WSP_ERROR;
            }
            $value = $respuesta->value();

            $validacion = $value[0];
            $error = 0;

            if (!$validacion->scalarval()) {
                $error++;
            }

            foreach ($value[1] as $key => $struct) {
                if ($error > 0) {
                    $MyDebug->DebugMessage("WSPagos::getReferencia:" . $struct);
                    $this->response->error = $struct;
                } else {
                    $_struc = (string) $struct->scalarval();
                    if (!empty($_struc)) {
                        $key = strtolower($key);
                        $this->response->{$key} = $struct->scalarval();
                    }
                }
            }
            if ($error > 0) {
                return WSP_ERROR;
            }

            return WSP_SUCCESS;
        } catch (Exception $ex) {
            $MyDebug->DebugMessage("WSPagos::getReferencia:" . $ex->getMessage());
            $this->response->error = $ex->getMessage();

            return WSP_ERROR;
        }
    }

    /**
     * @param array $dRFC
     * @param array $lstConceptos
     * @param int $total
     * @param string $observaciones
     * @param string $is_invoice 1 = true (generar factura), 0 = false (generar tickets)
     *
     * @return int
     */
    public function GenerateInvoice($dRFC = [], $lstConceptos = [], $total = 0, $observaciones = '', $is_invoice = '0')
    {
        global $MyDebug;
        $strc1 = new stdClass();
        $strc1->Cvepago = "SG";

        if ($is_invoice == '1') {
            $this->setMethod('genera_cfdi.v1');
            $strc1->Tpocfd = "FD";
            if ((count($lstConceptos) == 0 OR !is_array($lstConceptos)) || (count($dRFC) == 0 OR !is_array($dRFC))) {

                $MyDebug->DebugMessage("WSPagos::insertCDFS:Alguno de los elementos vienen vacios");

                return WSP_VACIO;
            }
        } else {
            $this->setMethod('inserttc.datos');
            $strc1->Tpocfd = "TC";
            if (count($lstConceptos) == 0 OR !is_array($lstConceptos)) {

                $MyDebug->DebugMessage("WSPagos::insertCDFS:Alguno de los elementos vienen vacios");

                return WSP_VACIO;
            }
        }

        $aux_total = 0;
        $arr1 = [];
        $arr2 = [];
        foreach ($lstConceptos as $key => $conceptos) {
            $conceptos = (object) $conceptos;
            $datoA1 = new stdClass();
            $datoA1->Cta_ie = $conceptos->cuenta;
            $datoA1->Cantidad = $conceptos->cantidad;
            $datoA1->Unidad = "NO APLICA";
            $datoA1->Descripcion = $conceptos->concepto;
            $datoA1->Precio_unit = $conceptos->importe;
            $datoA1->Iva = $conceptos->iva;
            $datoA1->Iva_impte = $conceptos->importe_iva;
            $datoA1->Importe_total = $conceptos->total;
            $arr1[] = $datoA1;

            $datoB1 = new stdClass();
            $datoB1->Cve_pago = "EF";
            $datoB1->Importe = $conceptos->total;
            $arr2[] = $datoB1;

            $aux_total = $aux_total + $conceptos->total;
        }

        if ($is_invoice == '1') {
            $dRFC = (object) $dRFC;
            $strc2 = new stdClass();
            $strc2->Procedencia = "AL";
            $strc2->Pais = "MX";
            $strc2->Rfc = strtoupper($dRFC->rfc);
            $strc2->Razon_soc = strtoupper($dRFC->razon_soc);
            $strc2->Nombre = strtoupper($dRFC->nombre);
            $strc2->A_paterno = strtoupper($dRFC->a_paterno);
            $strc2->A_materno = strtoupper($dRFC->a_materno);
            $strc2->Estado = strtoupper($dRFC->estado);
            $strc2->DelOMunicipio = strtoupper($dRFC->delmunicipio);
            $strc2->Cp = $dRFC->cp;
            $strc2->Colonia = strtoupper($dRFC->colonia);
            $strc2->Calle = strtoupper($dRFC->calle);
            $strc2->No_ext = strtoupper($dRFC->num_ext);
            $strc2->No_int = strtoupper($dRFC->num_int);
            $strc2->Email = $dRFC->email;
        }

        $strc3 = new stdClass();
        $strc3->Dependencia = trim(strtoupper($this->dependencia));
        $strc3->Subdep = trim(strtoupper($this->subDep));
        $strc3->Origen = $this->origen;
        $strc3->Status_pago = "I";
        $strc3->Importe_factura = $total;
        $strc3->Cve_moneda = $this->cve_moneda;
        $strc3->Forma_liq = "E";
        $strc3->Carta_certif = "0";
        $strc3->Tipo_moneda = $this->tipo_moneda;
        $strc3->Observaciones = strtoupper($observaciones);

        $parametros = [];
        $parametros[] = php_xmlrpc_encode($strc1);
        $parametros[] = php_xmlrpc_encode($arr1);
        $parametros[] = php_xmlrpc_encode($arr2);
        if ($is_invoice == '1') {
            $parametros[] = php_xmlrpc_encode($strc2);
        }
        $parametros[] = php_xmlrpc_encode($strc3);

        if ($is_invoice == '1') {
            $strc4 = new stdClass();
            $parametros[] = php_xmlrpc_encode($strc4);
        }

        $msg = new xmlrpcmsg($this->method, $parametros);
        unset($param);

        $client = new xmlrpc_client($this->url);
        $client->setAcceptedCompression(self::gzip);
        $client->setRequestCompression(self::gzip);
        $client->setCredentials($this->user, $this->password);
        $respuesta = $client->send($msg);
        unset($client);
        unset($msg);
        if ($respuesta->faultCode()) {
            $MyDebug->DebugMessage("WSPagos::insertCDFS:Error xml-rcp " . $respuesta->faultString());

            return WSP_ERROR;
        }
        $value = $respuesta->value();

        $error = 0;
        foreach ($value as $key => $struct) {
            if ($key == 0 && $struct->scalarval() != '1') {
                $this->response->{$key} = $struct->scalarval();
                $MyDebug->DebugMessage("WSPagos::insertCDFS: respuesta ws" . $struct->scalarval());
                $error++;
            } else {
                $_struc = (string) $struct->scalarval();
                if (!empty($_struc)) {
                    $this->response->{$key} = $_struc;
                }
            }
        }
        if ($error > 0) {
            return WSP_ERROR;
        }

        return WSP_SUCCESS;
    }

    /**----------------------------------------------------------------------*/
    /**
     * @param string $Ejercicio
     * @param array $Dependencia
     * @param array $Subdep
     * @param array $Origen
     * @param array $Folio
     *
     * @return int
     */
    public function getTicket($lstTicket = [])
    {
        global $MyDebug;
        $param = [];
        $obsticket = (object) $lstTicket;

        if (count(
                $lstTicket
            ) == 0 || !isset($obsticket->Folio)
        ) {

            $MyDebug->DebugMessage("WSPagos::getTicket:Elemeto vacio");

            return WSP_VACIO;
        }
        try {
            $data = [
                'Ejercicio' => $this->ejercicio
                ,
                'Dependencia' => $this->dependencia
                ,
                'Subdep' => $this->subDep
                ,
                'Origen' => $this->origen
                ,
                'Folio' => $obsticket->Folio,

            ];



            $param[] = php_xmlrpc_encode($data);


            $msg = new xmlrpcmsg($this->method, $param);

            unset($param);

            $client = new xmlrpc_client($this->url);
            $client->setAcceptedCompression(self::gzip);
            $client->setRequestCompression(self::gzip);
            $client->setCredentials($this->user, $this->password);
            $respuesta = $client->send($msg);

            unset($client);
            unset($msg);
            if ($respuesta->faultCode()) {
                $MyDebug->DebugMessage("WSPagos::getTicket:" . $respuesta->faultString());

                return WSP_ERROR;
            }
            $value = $respuesta->value();

            $validacion = $value[0];

            $error = 0;

            if (!$validacion->scalarval()) {
                $error++;
            }

            foreach ($value as $key => $struct) {
            if ($key == 0 && $struct->scalarval() != '1') {
                $this->response->{$key} = $struct->scalarval();
                $MyDebug->DebugMessage("WSPagos::insertCDFS: respuesta ws" . $struct->scalarval());
                $error++;
            } else {
                $_struc = (string) $struct->scalarval();
                if (!empty($_struc)) {
                    $this->response->{$key} = $_struc;
                }
            }
        }
            if ($error > 0) {
                return WSP_ERROR;
            }

            return WSP_SUCCESS;
        } catch (Exception $ex) {
            $MyDebug->DebugMessage("WSPagos::getTicket:" . $ex->getMessage());
            $this->response->error = $ex->getMessage();

            return WSP_ERROR;
        }
        

    }
    /*----------------------------------------------------------------------*/

    /**
     * Parametro de entrada la referencia
     * Parametro de Salida  error
     * WSP_VACIO = 201 vacio
     * WSP_ERROR = 202 se genero un error en el web services
     * Succes
     * WSP_SUCCESS = 200
     *
     * method consultabancoreferencia.srv
     *
     * @param string $referencia
     *
     * @return int
     */
    public function getStatus($referencia = '')
    {
        global $MyDebug;
        if (empty($referencia)) {
            $MyDebug->DebugMessage("WSPagos::getStatus:referencia [$referencia] vacia");

            return WSP_VACIO;
        }
        try {
            $this->setMethod('consultabancoreferencia.srv');
            $client = new xmlrpc_client($this->url);

            $client->setCredentials($this->user, $this->password);
            $client->setSSLVerifyHost(0);
            $client->setSSLVerifyPeer(false);
            $client->setAcceptedCompression(self::gzip);
            $client->setRequestCompression(self::gzip);
            $params = new xmlrpcval(['referencia' => new xmlrpcval($referencia, 'string')], 'struct');

            $msg = new xmlrpcmsg($this->method, [$params]);
            unset($param);
            $respuesta = $client->send($msg);

            unset($client);
            unset($msg);
            if ($respuesta->faultCode()) {
                $MyDebug->DebugMessage("WSPagos::getStatus:" . $respuesta->faultString());

                return WSP_ERROR;
            }
            $value = $respuesta->value();

            $validacion = $value[0];
            $error = 0;

            if ($validacion->scalarval() != '1') {
                $error++;
            }
            $i = 1;
            foreach ($value[1] as $key => $struct) {
                if ($error > 0) {
                    $this->response->error = $struct;
                    $MyDebug->DebugMessage("WSPagos::getStatus:" . $struct);
                    $i++;
                } else {
                    foreach ($struct->scalarval() as $index => $array) {
                        $_struc = (string) $array->scalarval();
                        if (!empty($_struc)) {
                            $this->response->{$i} = $_struc;
                        }
                        $i++;
                    }
                }
            }
            if ($error > 0) {
                return WSP_ERROR;
            }

            return WSP_SUCCESS;
        } catch (Exception $ex) {
            $MyDebug->DebugMessage("WSPagos::getStatus:" . $ex->getMessage());
            $this->response->error = $ex->getMessage();

            return WSP_ERROR;
        }
    }
}

$MyWSPagos = new WSPagos();

?>
