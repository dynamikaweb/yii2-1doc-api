<?php
/**
 * 
 * @copyright Copyright (c) 2019 Dynamika Web
 * @link https://github.com/dynamikaweb/yii2-1doc-api
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * 
 */
namespace dynamikaweb\api;

use Yii;

/**
 *
 * @api 1Doc
 * @author Rodrigo Dornelles <rodrigo@dornelles.me> <rodrigo@dynamika.com.br>
 * @version 0.1a  (18/09/2019)
 *
 * *
 *
 * @see todos os comentarios estão em português por que o uso desta @api está focado no mercado nacional.
 * 
 * *
 * 
 */
class OneDocApi extends \yii\base\Model
{   
    /**
     * @property XXX
     * @return string
     * 
     * destinado a ocultação de informações sensiveis 
     * 
     */
    const XXX = 'XXXXXXXXXX';
    
    /**
     * @property URLS
     * @return array
     * 
     * urls padrões pré estabelecidas
     * 
     */
    const URLS = [
        'prod' => 'https://api.1doc.com.br',
        'testes' => 'https://app7.1doc.com.br/api/v2/'
    ];

    /**
     * @property instructs 
     * @return array
     * 
     * requisição que sera feita pela @api
     *  
     */
    public $instructs = [
        'method' => '',
	    'client_auth' => self::XXX,
	    'token' => self::XXX,
        'secret' => self::XXX,
    ];

    /**
     * @property rules
     * @return array-array
     * 
     * regras de monstagem da @property instruct 
     * 
     */
    public $rules = [
        'emissao' => [
            'hash' => 'hash',
            'grupo' => 'grupo',
            'documento' => 'id_documento',
            'disponivel' => 'disponivel_workplace',
            'origem' => 'origem_id_pessoa',
            'destino' => 'destino_id_pessoa',
            'cidade' => 'cidade'
        ],
        'pagina' => 'num_pagina',
        'limit' => 'limit'
    ];

    /**
     * @property url_request
     * @return string
     * 
     * url para requisição restfull @api
     * 
     */
    private $url_request;

    /**
     * @property data
     * @return array
     * 
     * dados retornados pela @api da 1doc
     * 
     */
    public $data = [];


    /**
     * __construct 
     * 
     * quando component for montado, já deve ser criado component auxiliar para autenticação segura
     * 
     * @return void
     */
    public function __construct( $config = [] )
    {
        /** 
         * @see componet dynamikaweb\OneDocAuth
         */
        Yii::$app->set( 'OneDocAuth', new OneDocAuth );
        parent::__construct( $config );
    }

    /**
     * init
     * 
     * quando componet for chamado pela primeira vez
     * 
     * @return void
     */
    public function init ( )
    {        
        // Define uma URL padrão para quando não foi escolhida
        if ( !$this->url_request ) {
            $url = self::URLS;
            $this->url_request = current($url);
        }      
    }


    /**
     * find 
     * 
     * montagem da estrutura de request de acordo com @property rules
     *
     * @param  array $options
     *
     * @return array $this->instructs
     */
    public function find( $options )
    {
        foreach ($options as $option => $value){
            // se o parametro for o metodo, colocar prefixo emission
            if($option == 'method'){
                $this->instructs['method'] = 'emission'.ucfirst($value);
                continue; 
            }
            // parametros que estão em emissão
            if( isset($this->rules['emissao'][$option]) ){
                $this->instructs['emissao'][$this->rules['emissao'][$option]] = $value;
                continue;
            }
            // parametros aceitos fora de emissão
            if( isset($this->rules[$option]) ){
                $this->instructs[$this->rules[$option]] = $value;
                continue;
            }
        }
        return $this->instructs;
    }


    /**
     * getRun
     * 
     * executa a requisição para @api 1DOC
     *
     * @return array-object
     */
    public function getRun()
    {
        $ch= curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_request);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=". base64_encode($this->getRequest(true)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->data = json_decode(curl_exec ($ch));
        curl_close($ch);
        return $this;
    }

    /**
     * getRequest
     *
     * @param  boolean $real Paremtros de autenticação são reais ( por padrão falso para esconder a informação )
     *
     * @return string-json a requisão que sera feita para api 1doc
     */
    public function getRequest( $real = false )
    {
        $json = $this->instructs;

        // se for uma requisição real, substiuir autenticação ficticia pela verdadeira
        if( $real ){
            $auth = Yii::$app->OneDocAuth; // objeto de autenticação
            $json = \yii\helpers\ArrayHelper::merge( $json, $auth() ); 
        }
        
        return \yii\helpers\Json::encode( $json, false );
    }

    /**
     * __set
     *
     * @param string $name
     * @param mixed $value
     * 
     */
    public function __set( $name, $value )
    {
        switch ($name)
        {
            case 'client_auth': case 'token': case 'secret':
                Yii::$app->OneDocAuth->$name = $value;
            return ;
            case 'url': 
                $urls = self::URLS;
                $this->url_request = !isset($urls[$value]) ? $value:$urls[$value];
            return ;
        }
        
        parent::__set( $name, $value );
    }
}
