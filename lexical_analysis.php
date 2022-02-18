<?php
/************************************************************************/
/*                                                                      */
/* Soubor: lexical_analysis.php                                         */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici lexikalni analyzu jazyka IPPcode22          */
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici lexikalni analyzator
 * 
 * Pouziti navrhovy vzor: Singleton
 */
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

    /*
     * Metoda pro vytvoreni/ziskani instance
     */ 
    public static function getInstance() {
        if(self::$instance == NULL) {
            self::$instance = new Scanner();
        }

        return self::$instance;
    }

    /*
     * Metoda provadejici lexikalni analyzu vstupni instrukce
     * 
     * @param $instruction Vstupni instrukce
     * @return             V pripade validniho zapisu instanci tridy Instruction 
     *                     se statusem VALID a polem obsahujicim tokeny instrukce
     *     
     *                     V pripade nevalidniho zapisu instanci tridy Instuction
     *                     se statusem INVALID     
     */    
    private static function lexicalAnalysis($instruction) {                
        $instTokens = array();        

        /* EOF */
        if($instruction[0] == TokenType::T_EOF->value) {            
            $eofToken = EndOfFile::getInstance(TokenType::T_EOF->value);
            return new Instruction(VALID, array($eofToken));
        }

        $opCode = new OpCodeFactory();
        $operand = new OperandFactory();

        foreach($instruction as $token) {
            if(!str_contains($token, '@')) {
                /* 
                 * Operacni kod
                 * Typ
                 * Identifikator jazyka
                 * Navesti
                 */
                if(TokenUtil::isOpCode(InstructionSet::$instructionCodes, $token)) {                    
                    /* Operacni kod */
                    $opCodeToken = $opCode->createToken(TokenType::T_OP_CODE->value, $token);
                    array_push($instTokens, $opCodeToken);
                } elseif (TokenUtil::isDataType(DataType::$dataType, $token)) {
                    /* Typ */
                    $operandToken = $operand->createToken(TokenType::T_TYPE->value, $token);
                    array_push($instTokens, $operandToken);
                } elseif (TokenUtil::isLanguageId($token)) {
                    /* Identifikator jazyka */
                    $languageIdToken = LanguageIdentifier::getInstance(TokenType::T_LANGUAGE_ID->value);
                    array_push($instTokens, $languageIdToken);
                } else {
                    /* Navesti */
                    if(!TokenUtil::validLabel($token)) {
                        return new Instruction(INVALID);
                    }

                    $operandToken = $operand->createToken(TokenType::T_LABEL->value, $token);
                    array_push($instTokens, $operandToken);
                }
            } else {
                /* 
                 * Promenna
                 * Konstanta
                 */
                if(TokenUtil::isVar(FrameType::$frameType, $token)) {
                    /* Promenna */               
                    if(!TokenUtil::validVar($token)) {                        
                        return new Instruction(INVALID);
                    }
                    
                    $operandToken = $operand->createToken(TokenType::T_VAR->value, $token);
                    array_push($instTokens, $operandToken);
                } else {
                    /* Konstanta */
                    if(!TokenUtil::validConst(DataType::$dataType, $token)) {                        
                        return new Instruction(INVALID);
                    }                
                    
                    $operandToken = $operand->createToken(TokenType::T_CONST->value, $token);
                    array_push($instTokens, $operandToken); 
                }
            }
        }
        
        return new Instruction(VALID, $instTokens);
    }

    /*
     * Metoda slouzi pro ziskani instrukce
     * pro syntakticky analyzator
     * 
     * 1. Nacteni vstupni intrukce
     * 2. Provedeni lexikalni analyzy vstupni instukce
     * 
     * @return V pripade validniho zapisu instanci tridy Instruction 
     *         se statusem VALID a polem obsahujicim tokeny instrukce
     *     
     *         V pripade nevalidniho zapisu instanci tridy Instuction
     *         se statusem INVALID
     */
    public function getInstruction() {        
        $instruction = StringUtil::readInstruction();

        return self::lexicalAnalysis($instruction);
    }    
}

?>