<?php
/************************************************************************/
/*                                                                      */
/* Soubor: syntax_analysis.php                                          */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici syntaktickou analyzu jazyka IPPcode22       */
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici syntakticky analyzator
 * 
 * Pouziti navrhovy vzor: Singleton
 */
final class Parser {
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
            self::$instance = new Parser();
        }

        return self::$instance;
    }

    /*
     * Metoda provadejici syntaktickou analyzu vstupnich instrukci
     */
    public function parse() {        
        $scanner = Scanner::getInstance();

        $instruction = $scanner->getInstruction();
        $instTokens = $instruction->getInstTokens();

        if($instTokens[0]->getToken() !== TokenType::T_LANGUAGE_ID->value) {
            // TODO error
        }

        while(true) {
            $instruction = $scanner->getInstruction();
            $instTokens = $instruction->getInstTokens();                        
            
            if($instruction->getStatus() == INVALID) {                
                break;
            } elseif(strcmp($instTokens[0]->getType(), 'EOF') == 0) {                
                break;
            } elseif(strcmp($instTokens[0]->getType(), 'OPCODE') !== 0) {
                //TODO error
            }

            $array = [
                'GoodGuy' => [
                    '_attributes' => ['attr1' => 'value'],
                    'name' => 'Luke Skywalker',
                    'weapon' => 'Lightsaber'
                ],
                'BadGuy' => [
                    'name' => 'Sauron',
                    'weapon' => 'Evil Eye'
                ],
                'TheSurvivor' => [
                    '_attributes' => ['house'=>'Hogwarts'],
                    '_value' => 'Harry Potter'
                ]
            ];

            $result = Array2Xml::convert($array);
            echo $result;
            break;

            //var_dump($instruction);

            /* Prvni cast instrukce je operacni kod */
            $instOpCodeIdx = $instTokens[0]->getToken();

            $instOpCode = InstructionSet::$instructionCodes[$instOpCodeIdx];
            $instOperands = InstructionSet::$instructionSet[$instOpCode];
           
            /* Nespravny pocet operandu */

            if(count($instOperands) !== count($instTokens) - 1) {
                //TODO error
            }

            $operandIdx = 1;

            foreach($instOperands as $operand) {
                $operandToken = $instTokens[$operandIdx]->getToken();

                if(strcmp($operandToken, 'OPERAND') !== 0) {
                    //TODO error
                }

                switch($operand) {
                    case 'v': // var
                        if($operandToken != TokenType::T_VAR->value) {
                            // TODO error
                        }
                        break;
                    case 's': // symb  
                        if($operandToken != TokenType::T_VAR->value &&
                           $operandToken != TokenType::T_CONST->value) {
                            // TODO error
                        }
                        break;
                    case 'l': // label 
                        if($operandToken != TokenType::T_LABEL->value) {
                            // TODO error
                        }
                        break;                    
                    case 't': // type  
                        if($operandToken != TokenType::T_TYPE->value) {
                            // TODO error
                        }
                        break;
                }

                $operandIdx++;
            }                
        }
    }
}

?>