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
 */
final class Scanner {
    /*
     * Metoda provadejici lexikalni analyzu vstupni instrukce
     * 
     * @param $instruction Vstupni instrukce
     * @return             V pripade validniho zapisu instanci tridy Instruction 
     *                     se statusem EXIT_SUCCESS a polem obsahujicim tokeny instrukce
     *     
     *                     V pripade nevalidniho zapisu instanci tridy Instuction
     *                     se statusem odpovidajicim dane chybe
     */    
    private static function lexicalAnalysis($instruction) {
        $instTokens = array();      

        /* EOF */
        if($instruction[0] == TokenType::T_EOF->value) {          
            $eofToken = EndOfFile::getInstance(TokenType::T_EOF->value);
            return new Instruction(ExitCode::EXIT_SUCCESS->value, array($eofToken));
        }

        $opCode = new OpCodeFactory();
        $operand = new OperandFactory();

        /*
         * Identifikator jazyka
         * Operacni kod
         */
        if (TokenUtil::isLanguageId($instruction[0])) {
            /* Identifikator jazyka */
            if(count($instruction) > 1) {
                return new Instruction(ExitCode::BAD_HEADER->value);
            }

            $languageIdToken = LanguageIdentifier::getInstance(TokenType::T_LANGUAGE_ID->value);

            return new Instruction(ExitCode::EXIT_SUCCESS->value, array($languageIdToken));

        } elseif(TokenUtil::isOpCode(InstructionSet::$instructionCodes, $instruction[0])) {
            /* Operacni kod */
            $opCodeToken = $opCode->createToken(TokenType::T_OP_CODE->value, $instruction[0]);
            array_push($instTokens, $opCodeToken);
        } else {
            return new Instruction(ExitCode::BAD_OP_CODE->value);
        }

        /* Posunuti pole na argumenty instrukce */
        array_shift($instruction);

        /* Argumenty instrukce */
        foreach($instruction as $token) {
            if(!str_contains($token, '@')) {
                /*
                 * Typ                 
                 * Navesti
                 */                
                if (TokenUtil::isDataType(DataType::$dataType, $token)) {
                    /* Typ */
                    $operandToken = $operand->createToken(TokenType::T_TYPE->value, $token);
                    array_push($instTokens, $operandToken);
                } else {
                    /* Navesti */
                    if(!TokenUtil::validLabel($token)) {
                        return new Instruction(ExitCode::LEX_STX_ERR->value);
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
                        return new Instruction(ExitCode::LEX_STX_ERR->value);
                    }
                    
                    $operandToken = $operand->createToken(TokenType::T_VAR->value, $token);
                    array_push($instTokens, $operandToken);
                } else {
                    /* Konstanta */
                    if(!TokenUtil::validConst(DataType::$dataType, $token)) {
                        return new Instruction(ExitCode::LEX_STX_ERR->value);
                    }                
                    
                    $operandToken = $operand->createToken(TokenType::T_CONST->value, $token);
                    array_push($instTokens, $operandToken); 
                }
            }
        }
        
        return new Instruction(ExitCode::EXIT_SUCCESS->value, $instTokens);
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
    public static function getInstruction() {        
        $instruction = StringUtil::readInstruction();

        return self::lexicalAnalysis($instruction);
    }    
}

?>
