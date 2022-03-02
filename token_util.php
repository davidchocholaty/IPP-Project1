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
     * @param $instructionCodes Sada kodu instrukci jazyka IPPcode22
     * @param $token            Vstupni token
     * @return                  V pripade kladne odpovedi true, jinak false
     */ 
    public static function isOpCode($instructionCodes, $token) {
        foreach($instructionCodes as $opCode) {
            if(strcasecmp($opCode, $token) == 0) {
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
     * Metoda slouzi pro ziskani datoveho typu konstanty     
     *      
     * @param $token Vstupni token konstanty
     * @return       Datovy typ vstupni konstanty
     */ 
    public static function getConstDataType($token) {
        return explode('@', $token)[0];
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
        $varFrame = explode('@', $token)[0];

        foreach($frameType as $frame) {
            if(strcmp($frame, $varFrame) == 0) {
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
     * @param $const    Konstanta
     * @return          V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    public static function validConst($dataType, $const) {
        $constParts = explode('@', $const, 2);
        
        $constType = $constParts[0];
        $constVal = $constParts[1];

        if(self::isDataType($dataType, $constType)) {
        	$str = false;
        
            switch($constType) {
                case 'int':
                    $pattern = "~^[+-]?[0-9]+$~";
                    break;
                case 'bool':
                    $pattern = "~^(true|false)$~";
                    break;
                case 'string':
                	$str = true;
                	break;                    
                case 'nil';
                    $pattern = "~^(nil)$~";
                    break;
            }
            
            if($str && !preg_match('~(\\\\($|\p{S}|\p{P}\p{Z}|\p{M}|\p{L}|\p{C})|(\\\\[0-9]{0,2}($|\p{S}|\p{P}\p{Z}|\p{M}|\p{L}|\p{C}))| |#)~', $constVal)) {
            	return VALID;
            } elseif(preg_match($pattern, $constVal)) {        
				return VALID;        			
			}
        }

        return INVALID;
    }
}

?>
