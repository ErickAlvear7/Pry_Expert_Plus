<?php

    function safe($valor)
    {
        $valor = str_ireplace("function","",$valor);
        $valor = str_ireplace("xml","",$valor);
        $valor = str_ireplace("script","",$valor);
        $valor = str_ireplace("java","",$valor);
        $valor = str_ireplace(" document ","",$valor);
        $valor = str_ireplace(" document","",$valor);    
        $valor = str_ireplace("SELECT ","",$valor);
        $valor = str_ireplace("COPY ","",$valor);
        $valor = str_ireplace("DELETE","",$valor);
        $valor = str_ireplace("UPDATE "," ",$valor);
        $valor = str_ireplace("INSERT","",$valor);
        $valor = str_ireplace("EXECUTE"," ",$valor);
        $valor = str_ireplace(" DROP","",$valor);
        $valor = str_ireplace("DROP "," ",$valor);
        $valor = str_ireplace(" DUMP","",$valor);
        $valor = str_ireplace(" OR ","",$valor);
        $valor = str_ireplace(" XOR ","",$valor);
        $valor = str_ireplace(" AND ","",$valor);
        $valor = str_ireplace("%","",$valor);
        $valor = str_ireplace("LIKE","",$valor);
        $valor = str_ireplace(" FROM ","",$valor);
        $valor = str_ireplace(" INTO ","",$valor);
        $valor = str_ireplace(" VALUES","",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("--"," ",$valor);
        $valor = str_ireplace("^"," ",$valor);
        $valor = str_ireplace("["," ",$valor);
        $valor = str_ireplace("]"," ",$valor);
        $valor = str_ireplace("\\"," ",$valor);
        $valor = str_ireplace("!"," ",$valor);
        $valor = str_ireplace("¡"," ",$valor);
        $valor = str_ireplace("?"," ",$valor);
        $valor = str_ireplace("="," ",$valor);
        $valor = str_ireplace(">"," ",$valor);
        $valor = str_ireplace("<"," ",$valor);
        return $valor;
    }
?>