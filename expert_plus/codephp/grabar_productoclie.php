 <?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');    

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();    

 
    $xresultado = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxClieid']) and isset($_POST['xxResult'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxClieid']) <> '' and isset($_POST['xxResult']) <> ''){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xClieid = $_POST['xxClieid'];
            $xResult = $_POST['xxResult'];


            foreach($xResult as $drfila){

                $xProducto = safe($drfila['arryproducto']);
                $xDescrip = safe($drfila['arrydescripcion']);
                $xDesc = strtoupper($xDescrip);
                $xCosto = $drfila['arrycosto'];
                $xGrupid = $drfila['arrygrupid'];
                $xCober = $drfila['arrycober'];
                $xSist =  $drfila['arrysist'];
                $xAsismes = $drfila['arryasismes'];
                $xAsisanu = $drfila['arryasisanu'];
                $xGerencial = $drfila['arrygerencial'];
            
                $xSQL = "INSERT INTO `expert_productos` (clie_id,grup_id,pais_id,empr_id,prod_nombre,prod_descripcion,prod_costo, ";
                $xSQL .= "prod_asistmes,prod_asistanu,prod_cobertura,prod_sistema,prod_gerencial) ";
                $xSQL .= "VALUES ($xClieid,$xGrupid,$xPaisid,$xEmprid,'$xProducto','$xDesc','$xCosto',$xAsismes,$xAsisanu,'$xCober', ";
                $xSQL .= "'$xSist','$xGerencial')";
                mysqli_query($con, $xSQL);

            
            }

            $xresultado="OK";
        }    
    }

    echo $xresultado;

?>