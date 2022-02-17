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
        if($instruction[0] == TokenType::T_EOF->value) {
            $eof = new EndOfFileFactory();
            $eofToken = $eof->createToken(TokenType::T_EOF->value);
            return array(VALID, array($eofToken));
        }

        $opCode = new OpCodeFactory();
        $operand = new OperandFactory();

        foreach($instruction as $token) {
            if(!str_contains($token, '@')) {
                /* Operacni kod, typ, navesti, identifikator jazyka */
                if(TokenUtil::isOpCode(InstructionSet::$instructionSet, $token)) {
                    $opCodeIdx = array_search($token, $inst_set_keys);
                    $opCodeToken = $opCode->createToken($opCodeIdx);
                    array_push($inst_tokens, $opCodeToken);          
                } elseif (TokenUtil::isDataType(DataType::$dataType, $token)) {                    
                    $operandToken = $operand->createToken(TokenType::T_TYPE->value);
                    array_push($inst_tokens, $operandToken);                           
                } elseif (TokenUtil::isLanguageId($token)) {                    
                    $operandToken = $operand->createToken(TokenType::T_LANGUAGE_ID->value);
                    array_push($inst_tokens, $operandToken);
                } else {
                    /* Navesti */
                    if(!TokenUtil::validLabel($token)) {
                        return array(INVALID);
                    }
                                        
                    $operandToken = $operand->createToken(TokenType::T_LABEL->value);
                    array_push($inst_tokens, $operandToken);
                }
            } else {
                /* Promenna, konstanta */
                if(TokenUtil::isVar(FrameType::$frameType, $token)) {                
                    if(!TokenUtil::validVar($token)) {
                        return array(INVALID);
                    }
                    
                    $operandToken = $operand->createToken(TokenType::T_VAR->value);
                    array_push($inst_tokens, $operandToken);
                } else {
                    /* Konstanta */
                    if(!TokenUtil::validConst(DataType::$dataType, $token)) {
                        return array(INVALID);
                    }                
                    
                    $operandToken = $operand->createToken(TokenType::T_CONST->value);
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
        $instruction = StringUtil::readInstruction();

        return self::lexicalAnalysis($instruction);    
    }    
}

?>