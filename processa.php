<?php
// Criado por Marcos Peli
// ultima atualização 05/06/2015 - correçâo ref alteraçâo parametros consulta CPF da receita de 03/06/2015
// o objetivo dos scripts deste repositório é integrar consultas de CNPJ e CPF diretamente da receita federal
// para dentro de aplicações web que necessitem da resposta destas consultas para proseguirem, como e-comerce e afins.

require('funcoes.php');
require('ConsultaReceita.class.php');

$a = new ConsultaReceita();
/*

// dados da postagem de formulário de CNPJ
$cnpj = $_POST['cnpj'];						// Entradas POST devem ser tratadas para evitar injections
$captcha_cnpj = $_POST['captcha_cnpj'];		// Entradas POST devem ser tratadas para evitar injections

//dados da postagem do formulario de CPF
*/
$cpf = $_POST['cpf'];						// Entradas POST devem ser tratadas para evitar injections
$datanascim = $_POST['txtDataNascimento'];	// Entradas POST devem ser tratadas para evitar injections
$captcha_cpf = $_POST['captcha_cpf'];		// Entradas POST devem ser tratadas para evitar injections
var_dump($_POST);
/*

if($cnpj AND $captcha_cnpj)
{
	$getHtmlCNPJ = $a->doRequestCNPJ($cnpj, $captcha_cnpj);
	$campos = $a->parseHtmlCNPJ($getHtmlCNPJ);
}
*/
if($cpf AND $datanascim AND $captcha_cpf)
{
	$getHtmlCPF = $a->doRequestCPF($cpf, $datanascim, $captcha_cpf);
	$campos = $a->parseHtmlCPF($getHtmlCPF);
}


var_dump($campos);

?>
