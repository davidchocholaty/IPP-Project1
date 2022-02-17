<?php
/************************************************************************/
/*                                                                      */
/* Soubor: token_util.php                                               */
/* Vytvoren: 2022-02-17                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript se tridou obsahujici metody pro praci s tokeny         */ 
/*                                                                      */
/************************************************************************/

class TokenUtil {
    /*
     * Metoda slouzi pro urceni, zda dany token
     * je operacni kod (nazev instrukce)
     * Pro operacni kod plati, ze nezalezi
     * na velikosti pismen
     * 
     * @param $instructionSet Sada instrukci jazyka IPPcode22
     * @param $token           Vstupni token
     * @return                 V pripade kladne odpovedi true, jinak false
     */ 
    public static function isOpCode($instructionSet, $token) {
        foreach(array_keys($instructionSet) as $op_code) {
            if(strcasecmp($op_code, $token) == 0) {
                return true;
            }
        }

        return false;
    }

    /*
     * Metoda slouzi pro urceni, zda dany token
     * je datovy typ
     * 
     * @param $dataType Datove typy jazyka IPPcode22
     * @param $token     Vstupni token
     * @return           V pripade kladne odpovedi true, jinak false
     */ 
    public static function isDataType($dataType, $token) {
        foreach(array_keys($dataType) as $type) {
            if(strcmp($type, $token) == 0) {
                return true;
            }
        }

        return false;
    }

    /*
     * Metoda slouzi pro urceni, zda dany token
     * je identifikator jazyka: '.IPPcode22', kdy
     * nezalezi na velikosti pismen
     * 
     * @param token Vstupni token
     * @return      V pripade kladne odpovedi true, jinak false
     */ 
    public static function isLanguageId($token) {
        if(strcasecmp(LANGUAGE_ID, $token) == 0) {
            return true;
        }

        return false;
    }

    /*
     * Metoda slouzi pro urceni, zda dany token
     * je promenna
     * 
     * @param $frameType Typ ramce
     * @param $token      Vstupni token
     * @return            V pripade kladne odpovedi true, jinak false
     */ 
    public static function isVar($frameType, $token) {
        $var_frame = explode('@', $token)[0];

        foreach($frameType as $frame) {
            if(strcmp($frame, $var_frame) == 0) {
                return true;
            }
        }

        return false;
    }

    /*
     * Metoda slouzi pro overeni validniho zapisu nazvu promenne
     * 
     * @param $var Nazev promenne
     * @return     V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    public static function validVar($var) {
        $pattern = "~^(GF|LF|TF)@[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~";

        if(preg_match($pattern, $var)) {        
            return VALID;
        }

        return INVALID;
    }

    /*
     * Metoda slouzi pro overeni validniho zapisu nazvu navesti
     * 
     * @param $label Nazev navesti
     * @return     V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    public static function validLabel($label) {
        $pattern = "~^[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~";

        if(preg_match($pattern, $label)) {        
            return VALID;
        }

        return INVALID;
    }

    /*
     * Metoda slouzi pro overeni validniho zapisu konstanty
     *
     * @param $dataType Datove typy jazyka IPPcode22
     * @param $const     Konstanta
     * @return           V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    public static function validConst($dataType, $const) {
        $const_parts = explode('@', $const, 2);
        
        $const_type = $const_parts[0];
        $const_val = $const_parts[1];

        if(self::isDataType($dataType, $const_type)) {
            switch($const_type) {
                case 'int':
                    $pattern = "~^[+-]?[0-9]+$~";
                    break;
                case 'bool':
                    $pattern = "~^(true|false)$~";
                    break;
                case 'string':
                    /* ?! -> negace vyrazu   */
                    //TODO backslash in string
                    //$pattern = "~(?!(\\\\[0-9]{0,2}($|\p{L}|\p{M}|\p{S}|\p{P}\p{Z}|\p{C}| )|\\\\[0-9]{4,}))~u";
                    $pattern = "~(?:[^\s#\\\\]|(?:\\\\\d{3}))*~u";
                    break;
                case 'nil';
                    $pattern = "~^(nil)$~";
                    break;
            }
                        
            if(preg_match($pattern, $const_val)) {        
                return VALID;
            }        
        }

        return INVALID;
    }
}

?>