<?php
    
    $con=mysqli_connect("localhost","root","","expert_plus"); /*Database Connection*/
    mysqli_query($con,'SET NAMES utf8');  //Para poder recibir correctamente caracteres con tildes
    mysqli_set_charset($con,'utf8');
        
?>