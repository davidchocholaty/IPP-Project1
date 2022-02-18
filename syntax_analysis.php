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
    private static $status;
    private static $parseProg;

    /*
     * Zabraneni vytvareni vice instanci
     * s pomoci soukromeho konstruktoru
     */
    private function __construct() {
        self::$status = ExitCode::INTERN_ERR->value;
        self::$parseProg = array();
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

    public static function getStatus() {
        return self::$status;
    }

    public static function getParseProg() {
        return self::$parseProg;
    }

    /*
     * Metoda provadejici syntaktickou analyzu vstupnich instrukci
     */
    public function parse() {
        $scanner = Scanner::getInstance();
        $prog = array('instruction' => []);

        $instruction = $scanner->getInstruction();
        $instTokens = $instruction->getInstTokens();

        /* Hlavicka s identifikatorem jazyka */
        if($instruction->getStatus() !== ExitCode::EXIT_SUCCESS->value ||
           $instTokens[0]->getTokenCode() !== TokenType::T_LANGUAGE_ID->value) {            
            self::$status = ExitCode::BAD_HEADER->value;
            return;
        }

        $order = 1;

        while(true) {
            $instruction = $scanner->getInstruction();
            $instTokens = $instruction->getInstTokens();                        
            
            $instStatus = $instruction->getStatus();

            if($instStatus == ExitCode::BAD_OP_CODE->value) {                
                self::$status = $instStatus;
                return;
            } elseif($instStatus !== ExitCode::EXIT_SUCCESS->value) {                
                self::$status = $instStatus;
                return;
            } elseif(strcmp($instTokens[0]->getType(), 'EOF') == 0) {                
                break;
            }            

            /* Prvni cast instrukce je operacni kod */                        
            $instOpCode = $instTokens[0]->getTokenVal();
            $instOperands = InstructionSet::$instructionSet[$instOpCode];
           
            /* Nespravny pocet operandu */
            if(count($instOperands) !== count($instTokens) - 1) {                
                self::$status = ExitCode::LEX_STX_ERR->value;
                return;
            }

            /* Pridani instrukce do vystupniho pole pro xml */            
            //$progInstruction = array('_attributes' => ['order' => $order, 'opcode' => $instOpCode],'name' => 'Luke Skywalker', 'weapon' => 'Lightsaber');
            $progInstruction = array('_attributes' => ['order' => $order, 'opcode' => $instOpCode]);

            $operandIdx = 1;

            foreach($instOperands as $operand) {
                $operandType = $instTokens[$operandIdx]->getType();
                $operandToken = $instTokens[$operandIdx]->getTokenCode();
                $operandTokenVal = $instTokens[$operandIdx]->getTokenVal();

                if(strcmp($operandType, 'OPERAND') !== 0) {
                    self::$status = ExitCode::LEX_STX_ERR->value;
                    return;
                }

                switch($operand) {
                    case 'v': // var
                        if($operandToken !== TokenType::T_VAR->value) {
                            
                        }
                        
                        $arg = array('_attributes' => ['type' => 'var']);
                        break;
                    case 's': // symb                          
                        if($operandToken !== TokenType::T_VAR->value &&
                           $operandToken !== TokenType::T_CONST->value) {
                            self::$status = ExitCode::LEX_STX_ERR->value;
                            return;
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
                            self::$status = ExitCode::LEX_STX_ERR->value;
                            return;
                        }

                        $arg = array('_attributes' => ['type' => 'label']);
                        break;
                    case 't': // type  
                        if($operandToken !== TokenType::T_TYPE->value) {
                            self::$status = ExitCode::LEX_STX_ERR->value;
                            return;
                        }

                        $arg = array('_attributes' => ['type' => 'type']);
                        break;
                }

                $argTag = 'arg' . $operandIdx;
                $arg += array('_value' => $operandTokenVal);
                $progInstruction += array($argTag => $arg);                

                $operandIdx++;
            }
            
            array_push($prog['instruction'], $progInstruction);
            $order++;
        }

        self::$status = ExitCode::EXIT_SUCCESS->value;
        self::$parseProg = $prog;
    }
}

?>