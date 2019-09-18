<?php 

namespace dynamikaweb\api;

class OneDocAuth extends \yii\base\Model
{ 
    public $client_auth;
    public $token; 
    public $secret;

    public function __invoke()
    {
        $auth = [
            'client_auth' => $this->client_auth,
            'token' => $this->token,
            'secret' => $this->secret
        ];

        if( ($index = array_search( null, $auth )) !== false ){
            throw new OneDocException("Autenticação Incompleta!\nNão foi encontrado: ".$index);
        }
    
        return $auth;
    }

}