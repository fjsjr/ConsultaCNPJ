<?php
session_start();
require_once("ConsultaReceita.class.php");
$tipo_consulta = $_GET['tipo_consulta'];
$a = new ConsultaReceita();
$a->exibirCaptcha($tipo_consulta);
?>
