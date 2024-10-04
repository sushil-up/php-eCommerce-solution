<?php

class config
{
    function __construct(){
        // $this->returnVal($key);
    }

    public static function getValue($key){
        
        $configArr = [
            'db_host' => 'localhost',
            'db_name' => 'aceshop',
            'db_user' => 'aceshop',
            'db_pass' => '4k9EuwxWApSC15w3DIPs',
        ];

        return $configArr[$key];
    }

}