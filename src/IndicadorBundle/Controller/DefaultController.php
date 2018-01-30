<?php

namespace IndicadorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ComboArea = $em->getRepository('IndicadorBundle:consulta')->Comboareas();
        
        //SACAR EL AÑO ACTUAL
        //SACAR EL MES ACTIVO POR RANGO DE FECHA
        $perfil=1;//PERFIL 1:SECRETARIA,2:OFICINA
        $areactual=1;
        return $this->render('IndicadorBundle:Default:tablero_home.html.twig',['cboarea'=>$ComboArea,'areaactual'=>$areactual]);
    }
    
    /**
     * @Route("/lsttablero")
     * @Method("POST")
     */
    public function listatableroAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $codarea = $request->get('codarea');
        $Indicadores = $em->getRepository('IndicadorBundle:consulta')->IndicadoresbyArea($codarea);
        $perfil=1;//
        $codarea_session=1;
        $hoy = getdate();
        $year=$hoy['year'];
        $MesActual = $em->getRepository('IndicadorBundle:consulta')->MesActualbyRango($year);
        $mes_actual=$MesActual['mes'];
        echo $this->renderView('IndicadorBundle:Default:tabla.html.twig',['lista'=>$Indicadores,'mesactual'=>$mes_actual,'codarea'=>$codarea_session]);
        exit;
    }
    
    /**
     * @Route("/dataGrafico")
     * @Method("POST")
     */
    public function dataGraficoAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $codtablero = $request->get('codtablero');
        $dataGrafico = $em->getRepository('IndicadorBundle:consulta')->datagrafico($codtablero);
        $data=[];
        array_push($data, $dataGrafico['tab_ene']);
        array_push($data, $dataGrafico['tab_feb']);
        array_push($data, $dataGrafico['tab_mar']);
        array_push($data, $dataGrafico['tab_abr']);
        array_push($data, $dataGrafico['tab_may']);
        array_push($data, $dataGrafico['tab_jun']);
        array_push($data, $dataGrafico['tab_jul']);
        array_push($data, $dataGrafico['tab_ago']);
        array_push($data, $dataGrafico['tab_sep']);
        array_push($data, $dataGrafico['tab_oct']);
        array_push($data, $dataGrafico['tab_nov']);
        array_push($data, $dataGrafico['tab_dic']);
        array_push($data, $dataGrafico['cod_tablero']);
        array_push($data, $dataGrafico['tab_punt_min']);
        array_push($data, $dataGrafico['tab_punt_med']);
        array_push($data, $dataGrafico['tab_punt_max']);
        echo json_encode($data);
        exit;
    }
    
    /**
     * @Route("/ficha")
     */
    public function generarFichaAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $arrayFormGet = array();
        $id = $request->get('id');

        $dataGrafico = $em->getRepository('IndicadorBundle:consulta')->datagrafico($id);
        $organo = $request->get('organo');
        $mesReporte = $request->get('mes');
        $proceso = $request->get('proceso');
        $objetivo = $request->get('objetivo');
        $calculo = $request->get('calculo');
        $fuente = $request->get('fuente');
        $accion = $request->get('accion');
        $fecha = $request->get('fecha');
        $responsable = $request->get('responsable');
        $titulo = $request->get('titulo');
        
        array_push($arrayFormGet,$id);
        array_push($arrayFormGet,$organo);
        array_push($arrayFormGet,$mesReporte);
        array_push($arrayFormGet,$proceso);
        array_push($arrayFormGet,$objetivo);
        array_push($arrayFormGet,$calculo);
        array_push($arrayFormGet,$fuente);
        array_push($arrayFormGet,$accion);
        array_push($arrayFormGet,$fecha);
        array_push($arrayFormGet,$responsable);
        array_push($arrayFormGet, $dataGrafico['tab_punt_min']);
        array_push($arrayFormGet, $dataGrafico['tab_punt_med']);
        array_push($arrayFormGet, $dataGrafico['tab_punt_max']);

        array_push($arrayFormGet, $dataGrafico['tab_ene']);
        array_push($arrayFormGet, $dataGrafico['tab_feb']);
        array_push($arrayFormGet, $dataGrafico['tab_mar']);
        array_push($arrayFormGet, $dataGrafico['tab_abr']);
        array_push($arrayFormGet, $dataGrafico['tab_may']);
        array_push($arrayFormGet, $dataGrafico['tab_jun']);
        array_push($arrayFormGet, $dataGrafico['tab_jul']);
        array_push($arrayFormGet, $dataGrafico['tab_ago']);
        array_push($arrayFormGet, $dataGrafico['tab_sep']);
        array_push($arrayFormGet, $dataGrafico['tab_oct']);
        array_push($arrayFormGet, $dataGrafico['tab_nov']);
        array_push($arrayFormGet, $dataGrafico['tab_dic']);

        array_push($arrayFormGet, $titulo);

        $snappy = $this->get('knp_snappy.pdf');
        $html = $this->renderView('IndicadorBundle:Default:ficha.html.twig', array("arrayFormGet" => $arrayFormGet));
         $filename = "file";

        return new Response(
            $snappy->getOutputFromHtml($html,
                    array(
                        'encoding' => 'utf-8',
                        'title'=> 'Personal con Certificado',
                        'images' => true,
                        'enable-javascript' => true,
                        'javascript-delay' => 4000,
                        'no-stop-slow-scripts' =>false
                    )
            ),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename = "'.$filename.'.pdf" '
            )
        );


       // return $this->render('IndicadorBundle:Default:ficha.html.twig');
    }
    
    
    /**
     * @Route("/datamesbycodigo")
     * @Method("POST")
     */
    public function DataMesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $codigo = $request->get('codigo');
        $campo = $request->get('campo');
        $datavalor = $em->getRepository('IndicadorBundle:consulta')->DataMesbyCodigo($codigo,$campo);
        echo $datavalor['valor'];exit;
    }
    
    /**
     * @Route("/guardarValor")
     * @Method("POST")
     */
    public function GuardarValorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $codigo = $request->get('codigo');
        $campo = $request->get('campo');
        $valor = $request->get('valor');
//        var_dump($valor);
        $data= $em->getRepository('IndicadorBundle:consulta')->GuardarValor($codigo,$campo,$valor);
        echo $data['msj'];exit;
    }
    
}
