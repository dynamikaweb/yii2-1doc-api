dynamikaweb/yii2-1doc-api 
====================================
[![Latest Stable Version](https://img.shields.io/github/v/release/dynamikaweb/yii2-1doc-api)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api) [![Total Downloads](https://poser.pugx.org/dynamikaweb/yii2-1doc-api/downloads)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api) [![License](https://poser.pugx.org/dynamikaweb/yii2-1doc-api/license)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/70f5ed844fa74261b0b989f869a78317)](https://www.codacy.com/manual/RodrigoDornelles_2/yii2-1doc-api?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dynamikaweb/yii2-1doc-api&amp;utm_campaign=Badge_Grade) [![Build Test](https://scrutinizer-ci.com/g/dynamikaweb/yii2-1doc-api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dynamikaweb/yii2-1doc-api/) [![Latest Unstable Version](https://poser.pugx.org/dynamikaweb/yii2-1doc-api/v/unstable)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api)


Instalação
------------
ultilize [composer](http://getcomposer.org/download/) para instalar esta extensão.

execute

```bash
$ composer require dynamikaweb/yii2-1doc-api 
```
ou adicione

```json
"dynamikaweb/yii2-1doc-api" : "*"
```

to the require section of your application's `composer.json` file.

Como configurar
-----

adicione ao arquivo `config/main.php` o seguinte _component_:
```PHP 
'components' => [
        'OneDocApi' => [
            'class' => 'dynamikaweb\api\OneDocApi',
            'client_auth' => '',
            'token' => '',
            'secret' => '',
        ],
],
```

Como usar
-----
```PHP

class DemoController extends MyBaseController
{
    public function actionIndex()
    {   
        $api = Yii::$app->OneDocApi;
   
        $api->find([
            'method' => 'list',
            'documento' => 24,
            'grupo' => 19
        ]);

        return $this->render('index', ['api' => $api->run]);
    }
}
```
