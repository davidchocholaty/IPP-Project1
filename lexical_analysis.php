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

    /*
     * Zabraneni vytvareni vice instanci
     * s pomoci soukromeho konstruktoru
     */
    private function __construct() {
    }

    /*
     * Zabraneni klonovani instance
     */
    private function __clone() {
    }

    /*
     * Zabraneni zruseni serializace
     */
    public function __wakeup() {        
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

        /* EOF */             
        return array(TokenType::T_EOF);
    }

    /*
     * Funkce slouzi pro urceni, zda dany token
     * je operacni kod (nazev instrukce)
     * Pro operacni kod plati, ze nezalezi
     * na velikosti pismen
     * 
     * @param $instructionSet Sada instrukci jazyka IPPcode22
     * @param $token           Vstupni token
     * @return                 V pripade kladne odpovedi true, jinak false
     */ 
    private static function isOpCode($instructionSet, $token) {
        foreach(array_keys($instructionSet) as $op_code) {
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
     * @param $dataType Datove typy jazyka IPPcode22
     * @param $token     Vstupni token
     * @return           V pripade kladne odpovedi true, jinak false
     */ 
    private static function isDataType($dataType, $token) {
        foreach(array_keys($dataType) as $type) {
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
     * @param $frameType Typ ramce
     * @param $token      Vstupni token
     * @return            V pripade kladne odpovedi true, jinak false
     */ 
    private static function isVar($frameType, $token) {
        $var_frame = explode('@', $token)[0];

        foreach($frameType as $frame) {
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
     * @param $dataType Datove typy jazyka IPPcode22
     * @param $const     Konstanta
     * @return           V pripade validniho zapisu VALID (true), jinak INVALID (false)
     */
    private static function validConst($dataType, $const) {
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

    /*
     * Funkce provadejici lexikalni analyzu vstupni instrukce
     * 
     * @param $instruction Vstupni instrukce
     * @return             V pripade validniho zapisu instrukce array(VALID, tokeny instrukce),
     *                     jinak array(INVALID)
     */ 
    //TODO oop
    private static function lexicalAnalysis($instruction) {                
        $inst_tokens = array();

        $inst_set_keys = array_keys(InstructionSet::$instructionSet);        

        /* EOF */
        if($instruction[0] == TokenType::T_EOF) {
            $eof = new EndOfFileFactory();
            $eofToken = $eof->createToken(TokenType::T_EOF);
            return array(VALID, array($eofToken));
        }

        $opCode = new OpCodeFactory();
        $operand = new OperandFactory();

        foreach($instruction as $token) {
            if(!str_contains($token, '@')) {
                /* Operacni kod, typ, navesti, identifikator jazyka */
                if(self::isOpCode(InstructionSet::$instructionSet, $token)) {
                    $opCodeIdx = array_search($token, $inst_set_keys);
                    $opCodeToken = $opCode->createToken($opCodeIdx);
                    array_push($inst_tokens, $opCodeToken);          
                } elseif (self::isDataType(DataType::$dataType, $token)) {                    
                    $operandToken = $operand->createToken(TokenType::T_TYPE);
                    array_push($inst_tokens, $operandToken);                           
                } elseif (self::isLanguageId($token)) {                    
                    $operandToken = $operand->createToken(TokenType::T_LANGUAGE_ID);
                    array_push($inst_tokens, $operandToken);
                } else {
                    /* Navesti */
                    if(!self::validLabel($token)) {
                        return array(INVALID);
                    }
                                        
                    $operandToken = $operand->createToken(TokenType::T_LABEL);
                    array_push($inst_tokens, $operandToken);
                }
            } else {
                /* Promenna, konstanta */
                if(self::isVar(FrameType::$frameType, $token)) {                
                    if(!self::validVar($token)) {
                        return array(INVALID);
                    }
                    
                    $operandToken = $operand->createToken(TokenType::T_VAR);
                    array_push($inst_tokens, $operandToken);
                } else {
                    /* Konstanta */
                    if(!self::validConst(DataType::$dataType, $token)) {
                        return array(INVALID);
                    }                
                    
                    $operandToken = $operand->createToken(TokenType::T_CONST);
                    array_push($inst_tokens, $operandToken); 
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