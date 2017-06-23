<?php

namespace UserBundle\GACalidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Clases\funciones;

class ingresoForUnitController extends Controller
{
    /**
     * @Route("/GACalidad/formulaunit", name="formulaunit")
     */
    public function indexAction()
    {
        return $this->render('UserBundleGACalidadBundle:Default:ingresoForUnit.html.twig', array('disable' => "disabled"));
    }

    /**
     * @Route("/update/data/from/ajax/callproducto", name="_ajaxForUnit", options={"expose"=true})
     */
    public function ajaxAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $funciones = new funciones();
            if(isset($_POST['version'])){
                $suggest = "";
                $search = $_POST['version'];
                $sth_ver = $funciones->consultaBD('select * from jarvis.sch_garcal_tbdata_tamanolote tl
                                                    where tl.prodID=' . $_POST['producto'].' and tl.version = "'.$search.'"', 'jarvis');

                if ($sth_ver->num_rows > 0) {

                    while ($result_ver = $sth_ver->fetch_object()) {
                        $suggest .= '<div id="'.$result_ver->version.'" class="suggest-element">
                                        <a data="'.$result_ver->version.'" id="'.$result_ver->version.'">'.$result_ver->version.'</a></div>';
                    }
                }
                return new Response($suggest);

            }elseif(isset($_POST['copia'])){
                $tr = '';$rowCount = $_POST['rowcount'];$und = '';
                $sth = $funciones->consultaBD('select fu.ing,fu.cant from jarvis.sch_garcal_tbdata_tamanolote tl
                                                    join jarvis.sch_garcal_tbdata_forunit fu on fu.tamanoloteid = tl.id

                            where tl.prodID=' . $_POST['prodid'].' and tl.version ="'.$_POST['versiones'].'"', 'jarvis');
                if ($sth->num_rows > 0) {

                    while ($result = $sth->fetch_object()) {
                        $productosdescrip = "<select class='form-control input-sm'
                                            id='productodesc_".$rowCount."'
                                            onchange='change_desc(this,".$rowCount.")'
                                            name='productodesc_".$rowCount."' >
                                                <option id='0' >Seleccione...</option>";
                        $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION,p.prodUMEDIDA
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodDESCRIPCION ASC', 'jarvis');

                        if ($sth_prod->num_rows > 0) {

                            while ($result_prod = $sth_prod->fetch_object()) {
                                if($result->ing == $result_prod->prodID) {
                                    $und = $result_prod->prodUMEDIDA;
                                    $productosdescrip .= '<option selected id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                                }else{
                                    $productosdescrip .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                                }
                            }
                        }
                        $productosdescrip .= '</select>';

                        $productoscod = "<select class='form-control input-sm'
                                         id='productocod_".$rowCount."'
                                         onchange='change_cod(this,".$rowCount.")'
                                         name='productocod_".$rowCount."' >
                                            <option id='0' >Seleccione...</option>";
                        $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION,p.prodUMEDIDA
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodCODIGO ASC', 'jarvis');

                        if ($sth_prod->num_rows > 0) {

                            while ($result_prod = $sth_prod->fetch_object()) {
                                if($result->ing == $result_prod->prodID) {
                                    $und = $result_prod->prodUMEDIDA;
                                    $productoscod .= '<option selected id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                                }else{
                                    $productoscod .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                                }
                            }
                        }
                        $productoscod .= '</select>';


                        $tr .= '<tr id="tr_'.$rowCount.'" >
                                    <td>'.$productoscod.'</td>
                                    <td>'.$productosdescrip.'</td>
                                    <td>
                                        <label for="prodUMEDIDA" id="lbUnd_' . $rowCount . '"
                                        name="lbUnd_' . $rowCount . '">
                                            '.$und.'
                                        </label>
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="number" id="cantidad_'.$rowCount.'"
                                        required name="cantidad_'.$rowCount.'" value="'.$result->cant.'"/>
                                    </td>
                                    <td>
                                        <input type="hidden" id="ing_' .$rowCount. '" name="ing_' .$rowCount. '" value="'.$result->ing.'"  />
                                        <a href="#" onclick="eliminarReg(tr_'.$rowCount.','.$rowCount.')" >
                                             <span class="glyphicon glyphicon-remove-sign" style="color:red"></span>
                                        </a>
                                    </td>
                                </tr>';
                        $rowCount++;
                    }
                }
                $rowCount--;
                return new Response($tr."&&".$rowCount);
            }elseif(!empty($_POST['producto'])){
                $suggest = "";
                $search = $_POST['producto'];
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery("select p from AppBundle:SchAdminTbdataProductos p
                    INNER JOIN AppBundle:SchMrpTbdataEm em with em.prodid = p.prodid
                    where em.tipo = 'PA' and p.proddescripcion like :foo ORDER BY p.proddescripcion DESC");
                $query->setParameter('foo', '%'.$search.'%');
                $productos = $query->getResult();
                foreach ($productos as $producto){
                    $suggest .= '<div id="'.utf8_encode($producto->getProddescripcion()).' - '.$producto->getProdid().'" class="suggest-element"><a data="'.utf8_encode($producto->getProddescripcion()).'" id="'.$producto->getProdid().'">'.utf8_encode($producto->getProddescripcion()).'</a></div>';
                }
                return new Response($suggest);

            }elseif(!empty($_POST['codigo'])) {
                $suggest = "";
                $search = $_POST['codigo'];
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery("SELECT p
                                FROM AppBundle:SchAdminTbdataProductos p
                                INNER JOIN AppBundle:SchMrpTbdataEm em with em.prodid = p.prodid
                                WHERE em.tipo = 'PA' and p.prodcodigo like :prodid order by p.prodcodigo DESC");
                $query->setParameter('prodid', '%'.$search.'%');
                $productos = $query->getResult();
                foreach ($productos as $producto){
                    $suggest .= '<div id="'.utf8_encode($producto->getProddescripcion()).' - '.$producto->getProdid().'" class="suggest-element"><a data="'.utf8_encode($producto->getProddescripcion()).'" id="'.$producto->getProdid().'">'.utf8_encode($producto->getProddescripcion()).'</a></div>';
                }
                return new Response($suggest);
            }elseif(isset($_POST['proddescrip'])){
                $productosdescrip = "<select class='form-control input-sm' id='productodesc_".$_POST['rowcount']."' onchange='change_desc(this,".$_POST['rowcount'].")' name='productodesc_".$_POST['rowcount']."' ><option id='0' >Seleccione...</option>";
                $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodDESCRIPCION ASC', 'jarvis');

                if ($sth_prod->num_rows > 0) {

                    while ($result_prod = $sth_prod->fetch_object()) {
                        $productosdescrip .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                    }
                }
                $productosdescrip .= '</select>';
                $productoscod = "<select class='form-control input-sm' id='productocod_".$_POST['rowcount']."' onchange='change_cod(this,".$_POST['rowcount'].")' name='productocod_".$_POST['rowcount']."' ><option id='0' >Seleccione...</option>";
                $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodCODIGO ASC', 'jarvis');

                if ($sth_prod->num_rows > 0) {

                    while ($result_prod = $sth_prod->fetch_object()) {
                        $productoscod .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                    }
                }
                $productoscod .= '</select>';
                return new Response($productosdescrip."&&".$productoscod);
            }elseif(isset($_POST['proddescripselect'])){
                $productoscod = '';$und = '';
                $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION,p.prodUMEDIDA
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodCODIGO ASC', 'jarvis');
                if ($sth_prod->num_rows > 0) {

                    while ($result_prod = $sth_prod->fetch_object()) {
                        if($_POST['ing'] == $result_prod->prodID) {
                            $und = $result_prod->prodUMEDIDA;
                            $productoscod .= '<option selected id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                        }else{
                            $productoscod .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                        }
                    }
                }
                return new Response($productoscod."&&".$und);
            }elseif(isset($_POST['prodcodselect'])){
                $productosdescrip = '';$und = '';
                $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION,p.prodUMEDIDA
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodCODIGO ASC', 'jarvis');
                if ($sth_prod->num_rows > 0) {

                    while ($result_prod = $sth_prod->fetch_object()) {
                        if($_POST['ing'] == $result_prod->prodID) {
                            $und = $result_prod->prodUMEDIDA;
                            $productosdescrip .= '<option selected id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                        }else{
                            $productosdescrip .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                        }
                    }
                }
                return new Response($productosdescrip."&&".$und);
            }elseif (isset($_POST['eliminar'])) {
                $valida = 0;$result = '';

                $sth = $funciones->consultaBD('select *
                                                    from jarvis.sch_garcal_tbdata_forunit
                                                    where id=' . $_POST['eliminar'], 'jarvis');

                if ($sth->num_rows > 0) {
                    $result = $sth->fetch_object();
                }
                if ($funciones->consultaBD('UPDATE  jarvis.sch_garcal_tbdata_forunit  set estatusing="0" where id=' . $_POST['eliminar'], 'jarvis')) {
                    $us = $this->get('security.token_storage')->getToken()->getUser()->getID();
                    $sthlote = $funciones->consultaBD('select count(*) as total
                                                    from jarvis.sch_garcal_tbdata_forunit
                                                    where tamanoloteid=' . $result->tamanoloteid.' and estatusing = 1', 'jarvis');
                    $funciones->consultaBD("INSERT INTO `jarvis`.`sch_garcal_tbdata_historialforunit`
                        (`fechaACTUALIZACION`,`userACTUALIZACION`,`descripcion`,`tamanoloteID`)
                        VALUES ('".date("Y-m-d h:i:s")."',$us,'Se elimino un ingrediente',".$result->tamanoloteid.")", 'jarvis');
                    if ($sthlote->num_rows > 0) {
                        $resultlote = $sthlote->fetch_object();
                        if($resultlote->total > 0){
                            $valida = 1;
                        }else{
                            $funciones->consultaBD('UPDATE jarvis.sch_garcal_tbdata_tamanolote  set estatusreg="0" where id=' . $result->tamanoloteid, 'jarvis');
                            $valida = "1&2";
                        }
                    }

                }
                return new Response($valida);
            }else
            {
                return new Response("Error");
            }
        }else {

            return new Response('This is not ajax!', 400);
        }
    }

    /**
     * @Route("/GACalidad/formulaunit/post", name="postActionGuardarForUnit", options={"expose"=true})
     */
    public function postAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            print_r($_POST);
            $funciones = new funciones();$sth = '';$em = $this->getDoctrine()->getEntityManager();$tabla = '';$cont = 1;
            $query = '';$tamanolote = '';$unidadlote = '';$resulttamanolote = '';$version = '';$comentario='';$lbversion = '';

            if (isset($_POST['modificar']) && ($_POST['total_tds'] > 0)) {
                $us = $this->get('security.token_storage')->getToken()->getUser()->getID();
                $version = $_POST['version']+1;
                if(isset($_POST['estatus'])){
                    if($_POST['estatus'] == 0){
                        $funciones->consultaBD('UPDATE jarvis.sch_garcal_tbdata_tamanolote set tamano = '.$_POST['tamano'].',
                        unidad="'.$_POST['undlote'].'",prodID='.$_POST['prodid'].' where id='.$_POST['idlote'], 'jarvis');
                        $funciones->consultaBD("INSERT INTO `jarvis`.`sch_garcal_tbdata_historialforunit`
                        (`fechaACTUALIZACION`,`userACTUALIZACION`,`descripcion`,`tamanoloteID`)
                        VALUES ('".date("Y-m-d h:i:s")."',$us,'".$_POST['comentario']."',".$_POST['idlote'].")", 'jarvis');

                        for ($i = 1; $i <= $_POST['total_tds']; $i++) {
                            if (isset($_POST['id_' . $i])) {
                                $funciones->consultaBD('UPDATE  jarvis.sch_garcal_tbdata_forunit  set ing="' . $_POST['ing_' . $i] . '",cant="' . $_POST['cantidad_' . $i] . '" where id=' . $_POST['id_' . $i], 'jarvis');
                            } else {
                                $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_forunit (ing,cant,tamanoloteid,estatusing) values   ("' . $_POST['ing_' . $i] . '","' . $_POST['cantidad_' . $i] . '",' . $_POST['idlote'] . ',"1") ', 'jarvis');
                            }
                        }
                    }elseif($_POST['estatus'] == 1){
                        $funciones->consultaBD('UPDATE jarvis.sch_garcal_tbdata_tamanolote set
                        version="0" where id='.$_POST['idlote'], 'jarvis');
                        
                        $us = $this->get('security.token_storage')->getToken()->getUser()->getID();
                        $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_tamanolote (tamano,unidad,prodID,version,
                        estatus,fecha_registro,userID) values (' . $_POST['tamano'] . ',"' . $_POST['undlote'] . '",
                        ' . $_POST['prodid'] . ',"'.$version.'",0,"' . date("Y-m-d h:i:s") . '",'.$us.') ', 'jarvis');

                        $sthtamanolote = $funciones->consultaBD("select * from jarvis.sch_garcal_tbdata_tamanolote order by id DESC LIMIT 1", 'jarvis');
                        $resulttamanolote = $sthtamanolote->fetch_object();

                        if($_POST['total_tds'] > 1) {
                            for ($i = 1; $i <= $_POST['total_tds']; $i++) {
                                $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_forunit (ing,
                                cant,tamanoloteid,estatusing) values   (
                                "' . utf8_encode($_POST['ing_' . $i]) . '",
                                "' . utf8_encode($_POST['cantidad_' . $i]) . '",
                                ' . $resulttamanolote->id . ',"1") ', 'jarvis');
                            }
                        }else{
                            $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_forunit (ing,
                                cant,tamanoloteid,estatusing) values (
                                ' . $_POST['ing_1'] . ',
                                ' . $_POST['cantidad_1'] . ',
                              ' . $resulttamanolote->id . ',"1") ', 'jarvis');
                        }
                        $resultado = 1;
                    }
                }
                $resultado = 2;
            }elseif (isset($_POST['agregar']) && ($_POST['total_tds'] > 0)) {
                $us = $this->get('security.token_storage')->getToken()->getUser()->getID();
                $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_tamanolote (tamano,unidad,prodID,version,
                        estatus,fecha_registro,userID) values (' . $_POST['tamano'] . ',"' . $_POST['undlote'] . '",
                        ' . $_POST['prodid'] . ',"1",0,"' . date("Y-m-d h:i:s") . '",'.$us.') ', 'jarvis');

                $sthtamanolote = $funciones->consultaBD("select * from jarvis.sch_garcal_tbdata_tamanolote order by id DESC LIMIT 1", 'jarvis');
                $resulttamanolote = $sthtamanolote->fetch_object();

                if($_POST['total_tds'] > 1) {
                    for ($i = 1; $i <= $_POST['total_tds']; $i++) {
                        $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_forunit (ing,
                                cant,tamanoloteid,estatusing) values   (
                                "' . utf8_encode($_POST['ing_' . $i]) . '",
                                "' . utf8_encode($_POST['cantidad_' . $i]) . '",
                                ' . $resulttamanolote->id . ',"1") ', 'jarvis');
                    }
                }else{
                    $funciones->consultaBD('insert into jarvis.sch_garcal_tbdata_forunit (ing,
                                cant,tamanoloteid,estatusing) values (
                                ' . $_POST['ing_1'] . ',
                                ' . $_POST['cantidad_1'] . ',
                              ' . $resulttamanolote->id . ',"1") ', 'jarvis');
                }
                $resultado = 1;
            }
            if(!empty($_POST['descripcion'])) {
                $sthprod = $funciones->consultaBD('select * from jarvis.sch_admin_tbdata_productos
                where prodDESCRIPCION = "'.trim($_POST['descripcion']).'"', 'jarvis');
                if($sthprod->num_rows > 0) {
                    $resultprod = $sthprod->fetch_object();
                    $sth = $funciones->consultaBD('select * from jarvis.sch_garcal_tbdata_tamanolote tl
                                                    where tl.prodID="' . $resultprod->prodID . '" and tl.estatusreg = 1 order by id DESC LIMIT 1', 'jarvis');
                }
                $query = $em->createQuery("
                                SELECT p.prodid, p.prodcodigo, p.proddescripcion, em.dr, em.le, em.presentacion, em.ccstat,
                                em.opt,em.groupprodid,em.accionterapeuticaid,em.formafarmaid,em.fechacaducidad,em.estado,
                                em.jiraTicket,pro.p1, pro.q1, pro.p2, pro.q2, pro.p3, pro.q3, pro.p4, pro.q4, pro.p5,
                                pro.q5, pro.p6,pro.q6, pro.p7,pro.q7, pro.p8, pro.q8, pro.p9, pro.q9, pro.p10, pro.q10
                                FROM AppBundle:SchAdminTbdataProductos p
                                INNER JOIN AppBundle:SchMrpTbdataEm em with em.prodid = p.prodid
                                LEFT JOIN AppBundle:SchSalesTbdataPricepromotions pro with pro.prodid = p.prodcodigo
                                WHERE em.tipo = 'PA' and p.proddescripcion like :prodid order by p.proddescripcion");
                $query->setParameter('prodid', '%'.rtrim(ltrim($_POST['descripcion'])).'%');

            }elseif(!empty($_POST['codigo'])) {
                $sthprod = $funciones->consultaBD('select * from jarvis.sch_admin_tbdata_productos
                where prodCODIGO="' . trim($_POST['codigo']) . '"', 'jarvis');
                if($sthprod->num_rows > 0) {
                    $resultprod = $sthprod->fetch_object();
                    $sth = $funciones->consultaBD('select * from jarvis.sch_garcal_tbdata_tamanolote tl
                                                    where tl.prodID="' . $resultprod->prodID . '" and tl.estatusreg = 1 order by id DESC LIMIT 1', 'jarvis');
                }

                $query = $em->createQuery("
                                SELECT p.prodid, p.prodcodigo, p.proddescripcion, em.dr, em.le, em.presentacion, em.ccstat,
                                em.opt,em.groupprodid,em.accionterapeuticaid,em.formafarmaid,em.fechacaducidad,em.estado,
                                em.jiraTicket,pro.p1, pro.q1, pro.p2, pro.q2, pro.p3, pro.q3, pro.p4, pro.q4, pro.p5,
                                pro.q5, pro.p6,pro.q6, pro.p7,pro.q7, pro.p8, pro.q8, pro.p9, pro.q9, pro.p10, pro.q10
                                FROM AppBundle:SchAdminTbdataProductos p
                                INNER JOIN AppBundle:SchMrpTbdataEm em with em.prodid = p.prodid
                                LEFT JOIN AppBundle:SchSalesTbdataPricepromotions pro with pro.prodid = p.prodcodigo
                                WHERE em.tipo = 'PA' and p.prodcodigo like :prodid order by p.prodcodigo");
                $query->setParameter('prodid', '%'.rtrim(ltrim($_POST['producto'])).'%');

            }


            $producto = $query->getResult();
            if ($sth->num_rows > 0) {
                $resulttl = $sth->fetch_object();
                $sth = $funciones->consultaBD('select * from jarvis.sch_garcal_tbdata_forunit fu
                                                    join jarvis.sch_mrp_tbdata_em em on fu.ing = em.prodID
                                                    join jarvis.sch_admin_tbdata_productos p on fu.ing = p.prodID
                                                    where fu.tamanoloteid="' . $resulttl->id . '" and fu.estatusing = 1
                                                    order by em.ingtipo,p.prodDESCRIPCION asc', 'jarvis');
                if ($sth->num_rows > 0) {
                    while ($result = $sth->fetch_object()) {


                        $productosdescrip = "<select class='form-control input-sm' onchange='change_desc(this,$cont)' id='productodesc_" . $cont . "' name='productodesc_" . $cont . "'>
                                        <option id='0' >Seleccione...</option>";
                        $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodDESCRIPCION DESC', 'jarvis');

                        if ($sth_prod->num_rows > 0) {

                            while ($result_prod = $sth_prod->fetch_object()) {
                                if ($result->ing == $result_prod->prodID) {
                                    $productosdescrip .= '<option selected id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                                } else {
                                    $productosdescrip .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                                }
                            }
                        }
                        $productosdescrip .= '</select>';

                        $productoscod = "<select class='form-control input-sm' onchange='change_cod(this,$cont)' id='productocod_" . $cont . "' name='productocod_" . $cont . "'>
                                        <option id='0' >Seleccione...</option>";
                        $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodCODIGO DESC', 'jarvis');

                        if ($sth_prod->num_rows > 0) {

                            while ($result_prod = $sth_prod->fetch_object()) {
                                if ($result->ing == $result_prod->prodID) {
                                    $productoscod .= '<option selected id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                                } else {
                                    $productoscod .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                                }
                            }
                        }
                        $productoscod .= '</select>';


                        $tabla .= '<tr id="tr_' . $cont . '">
                                <td>' . $productoscod . '</td>
                                <td>' . $productosdescrip . '</td>
                                <td>
                                    <label for="prodUMEDIDA" id="lbUnd_' . $cont . '" name="lbUnd_' . $cont . '">' . $result->prodUMEDIDA . '</label>
                                </td>
                                <td>
                                    <input class="form-control input-sm" type="number" id="cantidad_' . $cont . '"
                                            required name="cantidad_' . $cont . '" value="' . $result->cant . '" />
                                </td>
                                <td>
                                    <input type="hidden" id="ing_' . $cont . '" name="ing_' . $cont . '" value="' . $result->ing . '"  />
                                    <input type="hidden" id="id_' . $cont . '" name="id_' . $cont . '"
                                    value="' . $result->id . '" />
                                    <a href="#" onclick="eliminarReg(' . $result->id . ',' . $cont . ')" >
                                        <span class="glyphicon glyphicon-remove-sign" style="color:red"></span>
                                    </a>
                                </td>

                           </tr>';
                        $cont++;
                        $sthtamanolote = $funciones->consultaBD("select * from jarvis.sch_garcal_tbdata_tamanolote where id = $result->tamanoloteid", 'jarvis');
                        $resulttamanolote = $sthtamanolote->fetch_object();
                        $version = $resulttl->version;
                        $estatus = $resulttl->estatus;

                    }
                    $sthhistory = $funciones->consultaBD('SELECT * FROM jarvis.sch_garcal_tbdata_historialforunit
                where id = ' . $resulttamanolote->id, 'jarvis');
                    if ($sthhistory->num_rows > 0) {
                        $resulthistory = $sthhistory->fetch_object();
                        $comentario = '<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
										<label for="comentario">Comentario</label>
										<textarea class="form-control input-sm" name="comentario" required id="comentario"  rows="4" cols="50">' . $resulthistory->descripcion . '</textarea>
									</div>';
                    } else {
                        $valor = ' ';
                        $comentario = '<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
										<label for="comentario">Comentario</label>
										<textarea form="form-registro" class="form-control input-sm" name="comentario" required id="comentario"  rows="4" cols="50">' . $valor . '</textarea>
									</div>';
                    }

                    $tamanolote = $resulttamanolote->tamano;
                    $unidadlote = $resulttamanolote->unidad;
                    $boton = '<div class="col-md-12 align_right">
                            <input type="hidden" id="total_tds" name="total_tds" value="' . $cont . '"  />
                            <input type="hidden" id="prodid" name="prodid" value="' . $producto[0]['prodid'] . '" />
                            <input type="hidden" id="idlote" name="idlote" value="' . $resulttamanolote->id . '" />
                            <input type="hidden" id="version" name="version" value="' . $version . '" />
                            <input type="hidden" id="estatus" name="estatus" value="' . $estatus . '" />
                            <button class="btn btn-primary btn-sm" type="submit" name="modificar" id="modificar" value="modificar">Modificar</button>
                        </div>';
                }
                $lbversion = '<label id="lbversion" name="lbversion">Versi&oacuten: '.$version.'</label>';
            }else {
                $version = 0;
                $lbversion = '<label if="lbversion" name="lbversion">Versi&oacuten: '.$version.'</label>';
                $productosdescrip = "<select class='form-control input-sm' id='productodesc_1' onchange='change_desc(this,1)' name='productodesc_1' ><option id='0' >Seleccione...</option>";
                $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodDESCRIPCION ASC', 'jarvis');

                if ($sth_prod->num_rows > 0) {

                    while ($result_prod = $sth_prod->fetch_object()) {
                        $productosdescrip .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodDESCRIPCION) . '</option>';
                    }
                }
                $productosdescrip .= '</select>';

                $productoscod = "<select class='form-control input-sm' onchange='change_cod(this,1)' id='productocod_1' name='productocod_1'>
                                        <option id='0' >Seleccione...</option>";
                $sth_prod = $funciones->consultaBD('select p.prodID,p.prodCODIGO,p.prodDESCRIPCION
                                                    from jarvis.sch_admin_tbdata_productos p
                                                    join jarvis.sch_mrp_tbdata_em e on p.prodID=e.prodID
                                                    where  e.ccstat = "ACTIVO" and e.tipo="MP" or e.tipo="ME"
                                                    order by p.prodCODIGO DESC', 'jarvis');

                if ($sth_prod->num_rows > 0) {

                    while ($result_prod = $sth_prod->fetch_object()) {
                        $productoscod .= '<option id="' . $result_prod->prodID . '" name="' . $result_prod->prodID . '">' . utf8_encode($result_prod->prodCODIGO) . '</option>';
                    }
                }
                $productoscod .= '</select>';

                $tabla = '<tr id="tr_1">
                                <td>' . $productoscod . '</td>
                                <td>' . $productosdescrip . '</td>
                                <td>
                                    <label for="prodUMEDIDA" id="lbUnd_1" name="lbUnd_1"></label>
                                </td>
                                <td>
                                    <input class="form-control input-sm" type="number" id="cantidad_1"
                                            required name="cantidad_1" value="" />
                                </td>
                                <td>
                                    <input type="hidden" id="ing_1" name="ing_1" value=""  />
                                    <a href="#" onclick="eliminarReg(tr_1,1)" >
                                        <span class="glyphicon glyphicon-remove-sign" style="color:red"></span>
                                    </a>
                                </td>
                           </tr>';
                $boton = '<div class="col-md-12 align_right">
                            <input type="hidden" id="total_tds" name="total_tds" value="1"  />

                            <input type="hidden" id="prodid" name="prodid" value="' . $producto[0]['prodid'] . '" />
                            <button class="btn btn-primary btn-sm" type="submit" name="agregar" id="agregar" value="agregar">Agregar</button>
                          </div>';
            }
            return $this->render('UserBundleGACalidadBundle:Default:ingresoForUnit.html.twig',
                array (
                    'producto'=>$producto,
                    'boton' => $boton,
                    'forunit' => $tabla,
                    'tamanolote' => $tamanolote,
                    'unidadlote' => $unidadlote,
                    'comentario' => $comentario,
                    'version' => $lbversion,
                    'disable' => ""
                )
            );
        }
    }
}
