<?php
/* @const Default CAMINHO RAIZ. */
/**
* Classe para controle de login e permissões de usuário
*
* (PHP 4, PHP 5)
*
* @author Fernando Junior <fernando.jr@fjrdesigner.com.br>
* @link http://fjrdesigner.com.br/
*
* @version v1.0
*
*
*/
class ConsultaReceita {

  /**
  * Inicia a sessão se necessário?
  *
  * @var boolean
  * @since v1.0
  */
  var $iniciaSessao = true;

  /**
  * Pasta do aonde será armazenado o cookie
  *
  * @var string
  * @since v1.0
  */
  var $cookiePath = "cookies/";


  /**
  * Dominio da receita federal
  *
  * @var string
  * @since v1.0
  */
  var $domain = 'http://www.receita.fazenda.gov.br';

  /**
  * Dominio da receita federal
  *
  * @var string
  * @since v1.0
  */
  var $path = '/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao2.asp';

  /**
  * Pasta do Captcha para exibir CNPJ
  *
  * @var string
  * @since v1.0
  */
  var $captchaCNPJ = '/pessoajuridica/cnpj/cnpjreva/captcha/gerarCaptcha.asp';


  /**
  * Pasta do Captcha para exibir CPF
  *
  * @var string
  * @since v1.0
  */
  var $captchaCPF = '/Aplicacoes/ATCTA/CPF/captcha/gerarCaptcha.asp';


  /**
  * Url selecionada para consulta
  *
  * @var String
  * @since v1.0
  */
  var $url = '';

  /**
  * Arquivo de Cookie para consulta Curl
  *
  * @var String
  * @since v1.0
  */
  var $cookieFile = '';


  /**
  * Arquivo de Cookie para consulta Curl
  *
  * @var String
  * @since v1.0
  */
  var $cookieFile_fopen = '';

  /**
  * Qual sera o tipo da consulta CNPJ ou CPF
  *
  * @var string
  * @since v1.0
  */
  var $tipoConsulta = '';

  /**
  * Armazena as mensagens de erro
  *
  * @var string
  * @since v1.0
  */
  var $erro = '';


  public function __construct(){
    if ($this -> iniciaSessao AND !isset($_SESSION))
    @session_start();

    define('COOKIELOCAL', str_replace('\\', '/', realpath('./')).'/'.$this -> cookiePath);
    define('HTTPCOOKIELOCAL',$this -> cookiePath);
  }

  private function verificarCaptcha($tipoConsulta){

    $this->tipoConsulta = strtolower($tipoConsulta);

    if($this->tipoConsulta == 'cpf'){

      $this->cookieFile = COOKIELOCAL.'cpf_'.session_id();
      $this->cookieFile_fopen = $this->cookiePath.'cpf_'.session_id();
      $this->url = $this->domain.$this->captchaCPF;
      return true;

    }elseif($this->tipoConsulta == 'cnpj'){

      $this->cookieFile = COOKIELOCAL.'cnpj_'.session_id();
      $this->cookieFile_fopen = $this->cookiePath.'cnpj_'.session_id();
      $this->url = $this->domain.$this->captchaCNPJ;
      return true;

    }else{
      return false;
    }

  }
  /**
  * Verifica se qual o tipo consulta CNPJ ou CPF e define url de acesso
  *
  * @access public
  * @since v1.0
  * @uses ConsultaReceita->verificaConsulta($tipoConsulta)
  *
  * @param String $tipoConsulta
  * @return Void||boolean
  */
  public function exibirCaptcha($Request){
    @session_start();

    if(self::verificarCaptcha($Request)){

        if(!file_exists($this->cookieFile)){

          $file = fopen($this->cookieFile, 'w');

          fclose($file);
        }

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        //  aqui será gravada as chaves de sessão
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        //  aqui será gravada as chaves de sessão
        // IMPORTANTE: sem o parametro RETURNTRANSFER para esta chamada de curl.
        $imgsource = curl_exec($ch);
        curl_close($ch);
/*
        // faz a chamada Curl que gera a imagem de captcha para consulta de CPF ou CNPJ conforme o parâmetro passado
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
*/

        $imgsource = curl_exec($ch);
        curl_close($ch);

        // se tiver imagem , mostra*/
        echo $this->url;


    }else{

      $this->erro = "Parâmetro de pesquisa inválido";
      return false;

    }


  }


}


?>
