<?php

namespace app\components;

use Yii;

class UtilitiesHelper
{
    /**
     * Le da formato a un texto que va a ser utilizado en url semantica.
     * @param $str es la cadena que queremos utilizar en la url
     * @return String
     */
    public static function formatUrl($str,$charReplace="-")
    { 
        $str = htmlentities($str);
        $str = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/','$1',$str);
        $str = html_entity_decode($str);
        $str = strtolower($str);
        $str = str_replace("ç","c",$str);
        $str = preg_replace('@[ =()/\'\"\:\+\!\“\”\‘\’\¡\¿\?\º\,\;\$\&\#\%\´\·\.\@\«\»]+@',$charReplace,trim($str));
        $str = preg_replace('@[\W]+@',$charReplace,$str);
        $str = preg_replace('@[-]*[^A-Za-z0-9._,]@',$charReplace,$str);
        $str = preg_replace('@^[-]@','',$str);
        $str = preg_replace('@([-])$@','',$str);
        
        $str = strtolower($str);
        return $str;
    }
}