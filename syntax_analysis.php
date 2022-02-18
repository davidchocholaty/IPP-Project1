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
        $prog = array('instruction' => []);

        $instruction = $scanner->getInstruction();
        $instTokens = $instruction->getInstTokens();

        if($instTokens[0]->getTokenCode() !== TokenType::T_LANGUAGE_ID->value) {
            // TODO error
        }

        $order = 1;

        while(true) {            
            $instruction = $scanner->getInstruction();
            $instTokens = $instruction->getInstTokens();                        
            
            if($instruction->getStatus() == INVALID) {
                //TODO error
                break;
            } elseif(strcmp($instTokens[0]->getType(), 'EOF') == 0) {                
                break;
            } elseif(strcmp($instTokens[0]->getType(), 'OPCODE') !== 0) {
                //TODO error
            }          

            /* Prvni cast instrukce je operacni kod */                        
            $instOpCode = $instTokens[0]->getTokenVal();
            $instOperands = InstructionSet::$instructionSet[$instOpCode];
           
            /* Nespravny pocet operandu */
            if(count($instOperands) !== count($instTokens) - 1) {
                //TODO error
            }

            /* Pridani instrukce do vystupniho pole pro xml */            
            //$progInstruction = array('_attributes' => ['order' => $order, 'opcode' => $instOpCode],'name' => 'Luke Skywalker', 'weapon' => 'Lightsaber');
            $progInstruction = array('_attributes' => ['order' => $order, 'opcode' => $instOpCode]);

            $operandIdx = 1;

            foreach($instOperands as $operand) {
                $operandToken = $instTokens[$operandIdx]->getTokenCode();
                $operandTokenVal = $instTokens[$operandIdx]->getTokenVal();

                if(strcmp($operandToken, 'OPERAND') !== 0) {
                    //TODO error
                }

                switch($operand) {
                    case 'v': // var
                        if($operandToken !== TokenType::T_VAR->value) {
                            // TODO error                         
                        }
                        
                        $arg = array('_attributes' => ['type' => 'var']);
                        break;
                    case 's': // symb                          
                        if($operandToken !== TokenType::T_VAR->value &&
                           $operandToken !== TokenType::T_CONST->value) {
                            // TODO error
                        }

                        if($operandToken == TokenType::T_VAR->value) {
                            /* Promenna */
                            $arg = array('_attributes' => ['type' => 'var']);
                        } else {
                            /* Konstanta */
                            $type = TokenUtil::getConstDataType($operandTokenVal);
                            $arg = array('_attributes' => ['type' => $type]);
                        }

                        //XML type="var", "bool", "string", "int", "nil", ...
                        //$arg = array('_attributes' => ['type' => 'TODO']);
                        break;
                    case 'l': // label
                        if($operandToken !== TokenType::T_LABEL->value) {
                            // TODO error
                        }

                        $arg = array('_attributes' => ['type' => 'label']);
                        break;
                    case 't': // type  
                        if($operandToken !== TokenType::T_TYPE->value) {
                            // TODO error
                        }

                        $arg = array('_attributes' => ['type' => 'type']);
                        break;
                }

                $argTag = 'arg' . $operandIdx;
                $arg += array('_value' => $operandTokenVal);
                $progInstruction += array($argTag => $arg);

                //var_dump($progInstruction);

                $operandIdx++;
            }            
            
            array_push($prog['instruction'], $progInstruction);
            $order++;
        }

        return $prog;
    }
}

?>