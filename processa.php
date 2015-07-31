<?php
// Criado por Marcos Peli
// ultima atualização 05/06/2015 - correçâo ref alteraçâo parametros consulta CPF da receita de 03/06/2015
// o objetivo dos scripts deste repositório é integrar consultas de CNPJ e CPF diretamente da receita federal
// para dentro de aplicações web que necessitem da resposta destas consultas para proseguirem, como e-comerce e afins.

require('funcoes.php');

// dados da postagem de formulário de CNPJ
$cnpj = $_POST['cnpj'];						// Entradas POST devem ser tratadas para evitar injections
$captcha_cnpj = $_POST['captcha_cnpj'];		// Entradas POST devem ser tratadas para evitar injections

// dados da postagem do formulario de CPF
$cpf = $_POST['cpf'];						// Entradas POST devem ser tratadas para evitar injections
$datanascim = $_POST['txtDataNascimento'];	// Entradas POST devem ser tratadas para evitar injections
$captcha_cpf = $_POST['captcha_cpf'];		// Entradas POST devem ser tratadas para evitar injections

if($cnpj AND $captcha_cnpj)
{
	$getHtmlCNPJ = getHtmlCNPJ($cnpj, $captcha_cnpj);
	$campos = parseHtmlCNPJ($getHtmlCNPJ);
}
if($cpf AND $datanascim AND $captcha_cpf)
{
	$getHtmlCPF = getHtmlCPF($cpf, $datanascim, $captcha_cpf);
	$campos = parseHtmlCPF($getHtmlCPF);
}
/*
var_dump($campos);

$dados = array(
'$INSCRICAO',
'$DATA_DE_ABERTURA',
'$NOME_EMPRESARIAL',
'$NOME_FANTASIA',
'$COD_E_DESCRICAO_DA_ATIVIDADE_ECONOMICA_PRINCIPAL',
'$COD_E_DESCRICAO_DA_ATIVIDADE_ECONOMICAS_SECUNDARIAS',
'$COD_E_DESCRICAO_DA_NATUREZA_JURIDICA',
'$LOGRADOURO',
'$NÚMERO',
'$COMPLEMENTO',
'$CEP',
'$BAIRRO',
'$MUNICIPIO',
'$UF',
'$EMAIL',
'$TEL',
'$EFR',
'$SITUACAO_CADASTRAL',
'$DATA_DA_SITUACAO_CADASTRAL',
'$MOTIVO_DE_SITUACAO_CADASTRAL',
'$SITUACAO_ESPECIAL',
'$DATA_DA_SITUACAO_ESPECIAL');*/

list($INSCRICAO,$DATA_DE_ABERTURA,$NOME_EMPRESARIAL,$NOME_FANTASIA,$COD_E_DESCRICAO_DA_ATIVIDADE_ECONOMICA_PRINCIPAL,$COD_E_DESCRICAO_DA_ATIVIDADE_ECONOMICAS_SECUNDARIAS,$COD_E_DESCRICAO_DA_NATUREZA_JURIDICA,$LOGRADOURO,$NUMERO,$COMPLEMENTO,$CEP,$BAIRRO,$MUNICIPIO,$UF,$EMAIL,$TEL,$EFR,$SITUACAO_CADASTRAL,$DATA_DA_SITUACAO_CADASTRAL,$MOTIVO_DE_SITUACAO_CADASTRAL,$SITUACAO_ESPECIAL,$DATA_DA_SITUACAO_ESPECIAL) = $campos;

$d = array(
	'cnpj' => $INSCRICAO,$DATA_DE_ABERTURA,$NOME_EMPRESARIAL,$NOME_FANTASIA,$COD_E_DESCRICAO_DA_ATIVIDADE_ECONOMICA_PRINCIPAL,$COD_E_DESCRICAO_DA_ATIVIDADE_ECONOMICAS_SECUNDARIAS,$COD_E_DESCRICAO_DA_NATUREZA_JURIDICA,$LOGRADOURO,$NUMERO,$COMPLEMENTO,$CEP,$BAIRRO,$MUNICIPIO,$UF,$EMAIL,$TEL,$EFR,$SITUACAO_CADASTRAL,$DATA_DA_SITUACAO_CADASTRAL,$MOTIVO_DE_SITUACAO_CADASTRAL,$SITUACAO_ESPECIAL,$DATA_DA_SITUACAO_ESPECIAL,$campos['status']
);


var_dump($d);
?>
