<?php
/**
 * @copyright Copyright (c) 2019 Dynamika Web
 * @link https://github.com/dynamikaweb/yii2-1doc-api
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dynamikaweb\api;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\base\Exception;

use Yii;

/**
 *
 * 
 *
 * @author Rodrigo Dornelles <rodrigo@dornelles.me> <rodrigo@dynamika.com.br>
 * @version 0.1  (17/09/2019)
 * 
 */
class OneDocApi extends Model
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
     * __construct
     *
     * @param  mixed $config
     *
     * @return void
     */
    public function  __construct ( $config = [] )
    {
        self::connectTo1Doc( $config );
        $this->url_request = self::configUrl( $config );
        
        parent::__construct( $config );
    }


    /**
     * init
     * 
     * @return void
     */
    public function init ( )
    {

    }



    /**
     * getRequisicao
     *
     * @param  boolean $real Paremtros de autenticação são reais ( por padrão falso para esconder a informação )
     *
     * @return string-json a requisão que sera feita para api 1doc
     */
    public function getRequisicao( $real = false )
    {
        $json = $this->instructs;

        if( $real ){
            $json = ArrayHelper::merge( $json, self::getConnection1Doc( ) ); 
        }

        return Json::encode( $json, false );
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
        return Yii::$app->session->get('1doc', self::getConnectionFake());
    }

    /**
     * static
     *
     * @param  mixed $config
     *
     * @return void
     */
    private static function connectTo1Doc( $config )
    {
        if ( Yii::$app->session->has('1doc') ){
            return ;
        }

        $attributes = ['client_auth', 'token', 'secret'];

        foreach ( $attributes as $attribute ){
            // verificar se a autenticação tem todos os dados.
            if ( !isset( $config[$attribute]) ){
                throw new Exception("1Doc - Api Error - Não foi possivel fazer a autenticação! [{$attribute}] não foi encontrado.", 1);
            }
            // gravar em sessão
            Yii::$app->session->set('1doc', [
                $attribute => $config[$attribute]
            ]);
        }
        return ;
    }

    public static function configUrl( $config )
    {
        $urls = self::URLS;

        if ( !isset($config['url']) ){
            return current( $urls );
        }

        if ( isset($urls[$config['url']]) ){
            return $urls[$config['url']];
        }

        return $config['url'];
    }
}
