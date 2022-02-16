<?php
/************************************************************************/
/*                                                                      */
/* Soubor: scanner.php                                                  */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-15                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici syntaktickou analyzu jazyka IPPcode22       */
/*                                                                      */
/************************************************************************/

/************* DEFINICE ************/
define("LANGUAGE_ID", ".IPPcode22");
define("VALID", true);
define("INVALID", false);

/* Navrhovy vzor Singleton */
final class Scanner {
    private static $instance = NULL;

    private function __construct() {

    }

    public static function getInstance() {
        if(self::$instance == NULL) {
            self::$instance = new Scanner();
        }

        return self::$instance;
    }

    /*
     * Funkce slouzi pro odstraneni jednoradkoveho
     * komentare nasledujiciho za samotnou instrukci
     * 
     * @params $input_line Vstupni radek
     * @return             Vstupni radek bez komentare
     */
    private static function removeComment($input_line) {
        return explode('#', $input_line)[0];
    }

    /*
     * Funkce slouzi pro odstraneni znaku noveho
     * radku nebo bilych znaku na konci vstupniho radku
     * 
     * @param $input line Vstupni radek obsahujici znak noveho radku
     * @return            Vstupni radek bez znaku noveho radku
     */
    private static function trimLineEnd($input_line) {
        $no_new_line = rtrim($input_line, "\n");
        $no_white_spaces = rtrim($input_line);
        
        return $no_white_spaces;
    }

    /*
     * Funkce prevede vstupni retezec na pole obsahujici
     * jednotliva slova retezce (tokeny)
     * Pritom jsou ignorovany vsechny bile znaky
     * 
     * @param $str Vstupni retecez
     * @return     Vysledne pole obsahujici tokeny
     */ 
    private static function str2Arr($str) {
        return preg_split("/\s+/", $str);
    }

    /*
     * Funkce slouzi pro nacteni jedne instrukce ze vstupu
     * Pritom jsou ignorovany komentare, prazdne radky a bile znaky
     * 
     * @return Nactena instrukce
     */
    private static function readInstruction() {
        while(($input_line = fgets(STDIN)) !== false){
            if (str_starts_with($input_line, '#') ||
                str_starts_with($input_line, '\n')) {

                continue;
            } else {
                $input_line = self::removeComment($input_line);
                $input_line = self::trimLineEnd($input_line);

                return self::str2Arr($input_line);            
            }
        }
        
        //TODO oop
        /* EOF */
        return array(token::T_EOF->value);
    }

    /*
     * Funkce slouzi pro urceni, zda dany token
     * je operacni kod (nazev instrukce)
     * Pro operacni kod plati, ze nezalezi
     * na velikosti pismen
     * 
     * @param $instruction_set Sada instrukci jazyka IPPcode22
     * @param $token           Vstupni token
     * @return                 V pripade kladne odpovedi true, jinak false
     */ 
    private static function isOpCode($instruction_set, $token) {
        foreach(array_keys($instruction_set) as $op_code) {
            if(strcasecmp($op_code, $token) == 0) {
                return true;
            }
        }

        return false;
    }

    /*
     * Funkce slouzi pro urceni, zda dany token
     * je datovy typ
     * 
     * @param $data_type Datove typy jazyka IPPcode22
     * @param $token     Vstupni token
     * @return           V pripade kladne odpovedi true, jinak false
     */ 
    private static function isDataType($data_type, $token) {
        foreach(array_keys($data_type) as $type) {
            if(strcmp($type, $token) == 0) {
                return true;
            }
        }

        return false;
    }

    /*
     * Funkce slouzi pro urceni, zda dany token
     * je identifikator jazyka: '.IPPcode22', kdy
     * nezalezi na velikosti pismen
     * 
     * @param token Vstupni token
     * @return      V pripade kladne odpovedi true, jinak false
     */ 
    private static function isLanguageId($token) {
        if(strcasecmp(LANGUAGE_ID, $token) == 0) {
            return true;
        }

        return false;
    }

    /*
     * Funkce slouzi pro urceni, zda dany token
     * je promenna
     * 
     * @param $frame_type Typ ramce
     * @param $token      Vstupni token
     * @return            V pripade kladne odpovedi true, jinak false
     */ 
    private static function isVar($frame_type, $token) {
        $var_frame = explode('@', $token)[0];

        foreach($frame_type as $frame) {
            if(strcmp($frame, $var_frame) == 0) {
                return true;
            }
        }

        return false;
    }

    /*
     * Funkce slouzi pro overeni validniho zapisu nazvu promenne
     * 
     * @param $var Nazev promenne
     * @return     V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    private static function validVar($var) {
        $pattern = "~^(GF|LF|TF)@[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~";

        if(preg_match($pattern, $var)) {        
            return VALID;
        }

        return INVALID;
    }

    /*
     * Funkce slouzi pro overeni validniho zapisu nazvu navesti
     * 
     * @param $label Nazev navesti
     * @return     V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    private static function validLabel($label) {
        $pattern = "~^[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~";

        if(preg_match($pattern, $label)) {        
            return VALID;
        }

        return INVALID;
    }

    /*
     * Funkce slouzi pro overeni validniho zapisu konstanty
     *
     * @param $data_type Datove typy jazyka IPPcode22
     * @param $const     Konstanta
     * @return           V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    private static function validConst($data_type, $const) {
        $const_parts = explode('@', $const, 2);
        
        $const_type = $const_parts[0];
        $const_val = $const_parts[1];

        if(self::isDataType($data_type, $const_type)) {
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

    /*
     * Funkce provadejici lexikalni analyzu vstupni instrukce
     * 
     * @param $instruction Vstupni instrukce
     * @return             V pripade validniho zapisu instrukce array(VALID, tokeny instrukce),
     *                     jinak array(INVALID)
     */ 
    //TODO oop
    private static function lexicalAnalysis($instruction) {
        global $instruction_set;
        global $data_type;
        global $frame_type;
        
        $inst_tokens = array();

        /* EOF */
        if($instruction[0] == token::T_EOF->value) {
            return array(VALID, $instruction);
        }

        foreach($instruction as $token) {
            if(!str_contains($token, '@')) {
                /* Operacni kod, typ, navesti, identifikator jazyka */
                if(self::isOpCode($instruction_set, $token)) {
                    array_push($inst_tokens, $token);                           
                } elseif (self::isDataType($data_type, $token)) {
                    array_push($inst_tokens, token::T_TYPE->value);                           
                } elseif (self::isLanguageId($token)) {
                    array_push($inst_tokens, token::T_LANGUAGE_ID->value);
                } else {
                    /* Navesti */
                    if(!self::validLabel($token)) {
                        return array(INVALID);
                    }
                    
                    array_push($inst_tokens, token::T_LABEL->value);                           
                }
            } else {
                /* Promenna, konstanta */
                if(self::isVar($frame_type, $token)) {                
                    if(!self::validVar($token)) {
                        return array(INVALID);
                    }

                    array_push($inst_tokens, token::T_VAR->value);
                } else {
                    /* Konstanta */
                    if(!self::validConst($data_type, $token)) {
                        return array(INVALID);
                    }                

                    array_push($inst_tokens, token::T_CONST->value);                           
                }
            }
        }
        
        return array(VALID, $inst_tokens);
    }

    /*
     * Funkce slouzi pro ziskani instrukce
     * pro syntakticky analyzator
     * 
     * 1. Nacteni vstupni intrukce
     * 2. Provedeni lexikalni analyzy vstupni instukce
     * 
     * @return V pripade validniho zapisu instrukce array(VALID, tokeny instrukce),
     *         jinak array(INVALID)
     */
    public function getInstruction() {
        $instruction = self::readInstruction();

        return self::lexicalAnalysis($instruction);    
    }    
}

?>