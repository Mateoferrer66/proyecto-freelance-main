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

    /**
      * formatCode() - Antepone la cantidad de ceros definida a un numero.
      *
      * @param $number --> numero al cual se le concatenaran los ceros.
      * @param $zeros --> cantidad de numeros de los que se compone el codigo y el le pone los ceros que necesite rellenar 
      *                   ejemplo tenemos como numero el 568 y los $zeros = 5 entonces el numero que retorna es 00568.
      * @return Numero con el nuevo formato.
      */
    public static function formatCode($number,$zeros)
    {    
        $size = strlen($number);
        $concat = '';
        if($size < $zeros)
        {
            for($i=0;$i<($zeros-$size);$i++)
            {
                $concat .= '0';
            }
            $number = $concat.$number; 
        }
                        
        return $number; 
    }

    /**
     * Cambia la fecha del formato Español(2009-11-24) al formato de BD(24/11/2009)
     * @param $date Fecha en formato "2009-11-24"
     * @return String
     */
    public static function db2date( $date )
    {       
        if ($date === null || $date === '') {
            return '';
        }

        if (strpos($date, '-')) {
            $fec = explode('-', $date);
            return $fec[2] . '/' . $fec[1] . '/' . $fec[0];
        }

        return '';
    }

    /**
     * Cambia la fecha+hora del formato de BD (2009-11-24 10:30:00) 
     * al formato español (24/11/2009 10:30)
     * 
     * @param string|null $date Fecha en formato "2009-11-24 10:30:00"
     * @param bool $addHour Si true incluye la hora en el resultado
     * @return string
     */
    public static function db2dateHour($date, $addHour = true)
    {
        if ($date === null || $date === '') {
            return '';
        }

        if (strpos($date, '-') && strpos($date, ' ')) {
            $fecHour = explode(' ', $date);
            $fec     = explode('-', $fecHour[0]);

            if ($addHour) {
                $hour = explode(':', $fecHour[1]);
                return $fec[2] . '/' . $fec[1] . '/' . $fec[0] . ' ' . $hour[0] . ':' . $hour[1];
            }

            return $fec[2] . '/' . $fec[1] . '/' . $fec[0];
        }

        return '';
    }
}