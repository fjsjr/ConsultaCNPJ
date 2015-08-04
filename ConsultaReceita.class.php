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
  * Arquivo de envio da consulta CPF
  *
  *@var String
  *@since v1.0
  */
  var $consultaCPF = '/Aplicacoes/ATCTA/CPF/ConsultaPublicaExibir.asp';

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

  /**
  *
  * Dados a serem selecionados no site da receita
  *
  * @var Array
  * @since v1.0
  */

  var 	$campos = array(
    'CamposCNPJ' => array(
      'NÚMERO DE INSCRIÇÃO',
      'DATA DE ABERTURA',
      'NOME EMPRESARIAL',
      'TÍTULO DO ESTABELECIMENTO (NOME DE FANTASIA)',
      'CÓDIGO E DESCRIÇÃO DA ATIVIDADE ECONÔMICA PRINCIPAL',
      'CÓDIGO E DESCRIÇÃO DAS ATIVIDADES ECONÔMICAS SECUNDÁRIAS',
      'CÓDIGO E DESCRIÇÃO DA NATUREZA JURÍDICA',
      'LOGRADOURO',
      'NÚMERO',
      'COMPLEMENTO',
      'CEP',
      'BAIRRO/DISTRITO',
      'MUNICÍPIO',
      'UF',
      'ENDEREÇO ELETRÔNICO',
      'TELEFONE',
      'ENTE FEDERATIVO RESPONSÁVEL (EFR)',
      'SITUAÇÃO CADASTRAL',
      'DATA DA SITUAÇÃO CADASTRAL',
      'MOTIVO DE SITUAÇÃO CADASTRAL',
      'SITUAÇÃO ESPECIAL',
      'DATA DA SITUAÇÃO ESPECIAL'
    ),
    'indicesCNPJ' => array(
      'INCRICAO',
      'DT_ABERTURA',
      'RZ_SOCIAL',
      'FANTASIA',
      'COD_ATV_PRINCIPAL',
      'COD_ATV_SECUNDARIAS',
      'NATU_JURIDICA',
      'LOGRADOURO',
      'NUMERO',
      'COMPLEMENTO',
      'CEP',
      'BAIRRO',
      'MUNICIPIO',
      'UF',
      'EMAIL',
      'TEL',
      'EFR',
      'SIT_CADASTRAL',
      'DT_SIT_CADASTRAL',
      'MT_SIT_CADASTRAL',
      'ST_ESPECIAL',
      'DT_ST_ESPECIAL',
      'status'
    ),
    'CamposCPF' => array(
      'No do CPF:',
      'Nome da Pessoa Física:',
      'Data de Nascimento:',
      'Situação Cadastral:',
      'Data da Inscrição:',
      'Comprovante emitido às:',
      'status',
    ),
    'indicesCPF' => array(
      'CPF',
      'NOME',
      'DT_NASC',
      'ST_CADASTRAL',
      'DT_INSCRICAO',
      'COMPROVANTE',
      'i',
      'status',
    ),
  );

  /**
  * Construtor da Classe (instancia variaveis, e abre a sessão ambas se não existirem)
  *
  * @access public
  * @since v1.2
  * @uses new ConsultaReceita
  *
  * @param String $Request (CNPJ ou CPF);
  * @return boolean
  */
  public function __construct(){

    if (!isset($_SESSION)) session_start();
    if (!defined('COOKIELOCAL')) define('COOKIELOCAL', str_replace('\\', '/', realpath('./')).'/'.$this ->cookiePath);
    if (!defined('HTTPCOOKIELOCAL')) define('HTTPCOOKIELOCAL',$this ->cookiePath);

  }


  /**
  * Verifica qual o tipo consulta CNPJ ou CPF e define url de acesso
  *
  * @access private
  * @since v1.0
  * @uses self::verificarConsulta($Request)
  *
  * @param String $Request (CNPJ ou CPF);
  * @return boolean
  */

  private function verificarConsulta($Request){

    $this->tipoConsulta = strtolower($Request);

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
  * Exibe Captcha de acesso para consulta na receita
  *
  * @access public
  * @since v1.0
  * @uses ConsultaReceita->exibirCaptcha($tipoConsulta)
  *
  * @param String $tipoConsulta
  * @return Imagem do Captcha || boolean
  */
  public function exibirCaptcha($tipoConsulta){

    @session_start();
    if(self::verificarConsulta($tipoConsulta)){

      if(!file_exists($this->cookieFile)){

        $file = fopen($this->cookieFile, 'w');

        fclose($file);
      }



      $ch = curl_init($this->url);
      $options = array(
        CURLOPT_COOKIEJAR  => $this->cookieFile,
        CURLOPT_COOKIEFILE => $this->cookieFile,
        CURLOPT_HTTPHEADER => array(
          "Pragma: no-cache",
          "Origin: $this->domain",
          "Host: www.receita.fazenda.gov.br",
          "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
          "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
          "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3",
          "Accept-Encoding: gzip, deflate",
          "Cookie: flag=1; $this->cookieFile",
          "Connection: keep-alive"
        ),
      );
      curl_setopt_array($ch, $options);
      $img = curl_exec($ch);
      curl_close($ch);

      if(@imagecreatefromstring($img)==false)
      throw new Exception('Não foi possível capturar o captcha');
      return array(
        'cookie' => $cookie,
        'captchaBase64' => 'data:image/png;base64,' . base64_encode($img)
      );


    }else{

      $this->erro = "Parâmetro de pesquisa inválido";
      return false;

    }


  }

  /**
  * Função para selecionar dados do arquivo html
  *
  * @access private
  * @since v1.0
  * @uses self::selecionaDados($inicio,$fim,$total)
  *
  * @param String $inicio
  * @param String $fim
  * @param String $total
  * @return Array
  */
  private function selecionaDados($inicio,$fim,$total){

    $interesse = str_replace($inicio,'',str_replace(strstr(strstr($total,$inicio),$fim),'',strstr($total,$inicio)));
    return($interesse);

  }

  private function doRequest($cookieFile, $cookieFile_fopen){
    if(!file_exists($cookieFile)){
      return false;
    }else{
      // pega os dados de sessão gerados na visualização do captcha dentro do cookie
      $file = fopen($cookieFile_fopen, 'r');
      while (!feof($file)){
        $conteudo = '';
        $conteudo .= fread($file, 1024);
      }
      fclose ($file);

      $explodir = explode(chr(9),$conteudo);

      $sessionName = trim($explodir[count($explodir)-2]);
      $sessionId = trim($explodir[count($explodir)-1]);

      // constroe o parâmetro de sessão que será passado no próximo curl
      $cookie = $sessionName.'='.$sessionId;

      return $Request = array('pagina' => $conteudo, 'cookie' => $cookie,);

      /*CPF SEM flag		// constroe o parâmetro de sessão que será passado no próximo curl
      $cookie = $sessionName.'='.$sessionId.';
      */

    }

  }



  /**
  * Exibe Captcha de acesso para consulta na receita
  *
  * @access public
  * @since v1.0
  * @uses ConsultaReceita->exibirCaptcha($tipoConsulta)
  *
  * @param String $tipoConsulta
  * @return Imagem do Captcha || boolean
  */

  public function doRequestCNPJ($cnpj, $captcha, $request = 'cnpj') {

    self::verificarConsulta($request);

    $pagina = self::doRequest($this->cookieFile, $this->cookieFile_fopen);

    if(!strstr($pagina['pagina'], 'flag 1')){

      $linha = chr(10).chr(10).$this->domain.'  FALSE	/pessoajuridica/cnpj/cnpjreva/	FALSE	0	flag	1'.chr(10);

      // novo cookie com o flag=1 dentro dele , antes da linha de sessionname e sessionid
      $cookieNovo = str_replace(chr(10).chr(10),$linha,$pagina['pagina']);

      // cria o novo cookie , com a linha flag=1 inserida
      $file = fopen($this->cookieFile, 'w');
      fwrite($file, $cookieNovo);
      fclose($file);

    }


    // dados que serão submetidos a consulta por post
    $post = array
    (
    'submit1'						=> 'Consultar',
    'origem'						=> 'comprovante',
    'cnpj' 							=> $cnpj,
    'txtTexto_captcha_serpro_gov_br'=> $captcha,
    'search_type'					=> 'cnpj'

  );

  $post = http_build_query($post, NULL, '&');

  $ch = curl_init($this->domain.'/pessoajuridica/cnpj/cnpjreva/valida.asp');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);		// aqui estão os campos de formulário
  curl_setopt($ch, CURLOPT_COOKIEFILE, $this -> cookieFile);	// dados do arquivo de cookie
  curl_setopt($ch, CURLOPT_COOKIEJAR, $this -> cookieFile);	// dados do arquivo de cookie
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0');
  curl_setopt($ch, CURLOPT_COOKIE, $pagina['cookie']);	    // dados de sessão e flag=1
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
  curl_setopt($ch, CURLOPT_REFERER, $this->domain.$this->path);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $html = curl_exec($ch);
  curl_close($ch);

  return $html;
}


/**
* Função para realizar a requizição dos dados ao site da receita (pesoa física)
*
* @access private
* @since v1.0
* @uses self::doRequestCPF($inicio,$fim,$total)
*
* @param String $cpf
* @param String $dtnasc
* @param String $captca
* @return Array
*/

public function doRequestCPF($cpf, $dtnasc, $captcha, $request = 'cpf'){

  $url = $this->domain.$this->consultaCPF;

  self::verificarConsulta($request);

  $pagina = self::doRequest($this->cookieFile, $this->cookieFile_fopen);

  // dados que serão submetidos a consulta por post
  $post = array
  (

  'txtTexto_captcha_serpro_gov_br'		=> $captcha,
  'tempTxtCPF'							=> $cpf,
  'tempTxtNascimento'						=> $dtnasc,
  'temptxtToken_captcha_serpro_gov_br'	=> '',
  'temptxtTexto_captcha_serpro_gov_br'	=> $captcha
);

$post = http_build_query($post, NULL, '&');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);		// aqui estão os campos de formulário
curl_setopt($ch, CURLOPT_COOKIEFILE, $this -> cookieFile);	// dados do arquivo de cookie
curl_setopt($ch, CURLOPT_COOKIEJAR, $this -> cookieFile);	// dados do arquivo de cookie
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0');
curl_setopt($ch, CURLOPT_COOKIE, $pagina['cookie']);			// continua a sessão anterior com os dados do captcha
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.receita.fazenda.gov.br/aplicacoes/atcta/cpf/consultapublica.asp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

return $html;

}

public function parseHtmlCNPJ($html){

  // caracteres que devem ser eliminados da resposta
  $caract_especiais = array(
    chr(9),
    chr(10),
    chr(13),
    '&nbsp;',
    '</b>',
    '  ',
    '<b>MATRIZ<br>',
    '<b>FILIAL<br>'
  );

  $html = str_replace('<br><b>','<b>',str_replace($caract_especiais,'',strip_tags($html,'<b><br>')));

  $html3 = $html;

  // faz a extração
  for($i=0;$i<count($this->campos['CamposCNPJ']);$i++)
  {
    $html2 = strstr($html,utf8_decode($this->campos['CamposCNPJ'][$i]));
    $resultado[] = trim(self::selecionaDados(utf8_decode($this->campos['CamposCNPJ'][$i]).'<b>','<br>',$html2));
    $html=$html2;
  }

  // extrai os CNAEs secundarios , quando forem mais de um
  if(strstr($resultado[5],'<b>'))
  {
    $cnae_secundarios = explode('<b>',$resultado[5]);
    $resultado[5] = $cnae_secundarios;
    unset($cnae_secundarios);
  }

  // devolve STATUS da consulta correto
  if(!$resultado[0])
  {
    if(strstr($html3,utf8_decode('O número do CNPJ não é válido')))
    {$resultado['status'] = 'CNPJ incorreto ou não existe';}
    else
    {$resultado['status'] = 'Imagem digitada incorretamente';}
  }
  else
  {$resultado['status'] = 'OK';}

  //return self::listarDados($resultado);
  //return $resultado;

  $resArray = array_combine($this->campos['indicesCNPJ'],$resultado);
  return $resArray;

}


  public function parseHtmlCPF($html){

    $caract_especiais = array(
  	chr(9),
  	chr(10),
  	chr(13),
  	'&nbsp;',
  	'  ',
  	 );

  	// prepara a resposta para extrair os dados
  	$html = str_replace('<br /><br />','<br />',str_replace($caract_especiais,'',strip_tags($html,'<b><br>')));

  	// para utilizar na hora de devolver o status da consulta
  	$html3 = $html;

  	// faz a extração
  	for($i=0;$i<count($this->campos['CamposCPF']);$i++)
  	{
  		$html2 = strstr($html,utf8_decode($this->campos['CamposCPF'][$i]));
  		$resultado[] = trim(self::selecionaDados(utf8_decode($this->campos['CamposCPF'][$i]),'<br',$html2));
  		$html=$html2;
  	}

  	// devolve STATUS da consulta correto
  	if(!$resultado[0])
  	{
  		if(strstr($html3,utf8_decode('CPF incorreto')))
  		{$resultado['status'] = 'CPF incorreto';}
  		else if(strstr($html3,utf8_decode('não existe em nossa base de dados')))
  		{$resultado['status'] = 'CPF não existe';}
  		else if(strstr($html3,utf8_decode('Os caracteres da imagem não foram preenchidos corretamente')))
  		{$resultado['status'] = 'Imagem digitada incorretamente';}
  		else
  		{$resultado['status'] = 'Receita não responde';}
  	}
  	else
  	{$resultado['status'] = 'OK';}

    $resArray = array_combine($this->campos['indicesCPF'],$resultado);
    return $resArray;


  }


}


?>
