OneDocApi Yii2
====================================
[![Latest Stable Version](https://poser.pugx.org/dynamikaweb/yii2-1doc-api/v/stable)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api) [![Total Downloads](https://poser.pugx.org/dynamikaweb/yii2-1doc-api/downloads)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api) [![License](https://poser.pugx.org/dynamikaweb/yii2-1doc-api/license)](https://packagist.org/packages/dynamikaweb/yii2-1doc-api) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/70f5ed844fa74261b0b989f869a78317)](https://www.codacy.com/manual/RodrigoDornelles_2/yii2-1doc-api?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dynamikaweb/yii2-1doc-api&amp;utm_campaign=Badge_Grade)

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

run

```bash
$ composer require dynamikaweb/yii2-1doc-api
```
or add

```json
"dynamikaweb/yii2-1doc-api" : "*"
```

to the require section of your application's `composer.json` file.

Usage
-----

add to `config/main.php`
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
