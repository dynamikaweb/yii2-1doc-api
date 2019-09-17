<?php
/**
 * @copyright Copyright (c) 2019 Dynamika Web
 * @link https://github.com/dynamikaweb/yii2-1doc-api
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dynamikaweb\api;

use Yii;

/**
 *
 * 
 *
 * @author Rodrigo Dornelles <rodrigo@dornelles.me> <rodrigo@dynamika.com.br>
 * @version 0.1  (17/09/2019)
 * 
 */
class OneDocApi extends \yii\base\Model
{   
    const XXX = 'XXXXXXXXXX';
    const URLS = [
        'prod' => 'api.1doc.com.br',
        'testes' => 'app7.1doc.com.br/api/v2/'
    ];

    public $instructs = [
        'method' => self::XXX,
	    'client_auth' => self::XXX,
	    'token' => self::XXX,
        'secret' => self::XXX,
    ];

    private $url_request;
    public $data = [];

    /**
     * init
     * 
     * @return void
     */
    public function init ( )
    {
        // Verifica se existe parametros de autenticação
        $auth = Yii::$app->session->get('1doc-auth', []);
        foreach ( ['client_auth', 'token', 'secret'] as $attribute){
            if(!array_key_exists($attribute, $auth)){
                throw new OneDocException("Autenticação Incompleta!\nNão foi encontrado: [{$attribute}]");
            }
        }
        // Define uma URL padrão para quando não foi escolhida
        if ( !$this->url_request ) {
            $url = self::URLS;
            $this->url_request = current($url);
        }
    
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

        if( $real ){
            $json = \yii\helpers\ArrayHelper::merge( $json, self::getConnection1Doc( ) ); 
        }

        return \yii\helpers\Json::encode( $json, false );
    }


    /**
     * 
     */
    public function getSubmitRequest ( )
    {

    }

    /**
     * getConnection1Doc
     * 
     * retorna os dados de autenticação armazenados em sessão.
     *
     * @return array [client_auth, token, secret]
     */
    public static function getConnection1Doc ( ) 
    {
        return Yii::$app->session->get('1doc-auth', []);
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
            case 'client_auth': 
            case 'token': 
            case 'secret':
                Yii::$app->session->set('1doc-auth', \yii\helpers\ArrayHelper::merge(
                    Yii::$app->session->get('1doc-auth', []),
                    [$name => $value]
                ));
            return ;
            case 'url': 
                $urls = self::URLS;
                $this->url_request = !isset($urls[$value]) ? $value:$urls[$value];
            return ;
        }
        
        parent::__set( $name, $value );
    }
}
