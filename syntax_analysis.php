<?php
/************************************************************************/
/*                                                                      */
/* Soubor: syntax_analysis.php                                          */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-03-06                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici syntaktickou analyzu jazyka IPPcode22       */
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici syntakticky analyzator
 */
final class Parser {
    private static $status;
    private static $parseProg;

    public static function getStatus() {
        return self::$status;
    }

    public static function getParseProg() {
        return self::$parseProg;
    }

    /*
     * Metoda provadejici syntaktickou analyzu vstupnich instrukci
     */
    public static function parse() {
	self::$status = ExitCode::INTERN_ERR->value;
	self::$parseProg = array();
    
        $prog = array('instruction' => []);

        $instruction = Scanner::getInstruction();
        $instTokens = $instruction->getInstTokens();
        
        /* Hlavicka s identifikatorem jazyka */
        if($instruction->getStatus() !== ExitCode::EXIT_SUCCESS->value ||
           $instTokens[0]->getTokenCode() !== TokenType::T_LANGUAGE_ID->value) {                        
            self::$status = ExitCode::BAD_HEADER->value;
            return;            
        }
        
        $order = 1;
        
        while(true) {
            $instruction = Scanner::getInstruction();
            $instTokens = $instruction->getInstTokens();                        
            
            $instStatus = $instruction->getStatus();

            if($instStatus == ExitCode::BAD_OP_CODE->value) {                
                self::$status = $instStatus;                
                return;
            } elseif($instStatus !== ExitCode::EXIT_SUCCESS->value) {                
                self::$status = $instStatus;                
                return;
            } elseif(strcmp($instTokens[0]->getType(), 'EOF') == 0) {
                if ($prog == array('instruction' => [])) {
                    $prog = array();
                }

                break;
            }
                        
            /* Prvni cast instrukce je operacni kod */                        
            $instOpCode = $instTokens[0]->getTokenVal();
            $instOpCode = strtoupper($instOpCode);

            /* Pokud se operacni kod nenachazi v instrukcni sade, jedna se o chybu */
            if (!in_array($instOpCode, InstructionSet::$instructionCodes)) {
                self::$status = ExitCode::BAD_OP_CODE->value;
                return;
            }

            $instOperands = InstructionSet::$instructionSet[$instOpCode];
                        
            /* Nespravny pocet operandu */
            if(count($instOperands) !== count($instTokens) - 1) {                
                self::$status = ExitCode::LEX_STX_ERR->value;
                return;
            }
            
            /* Pridani instrukce do vystupniho pole pro xml */
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
                            self::$status = ExitCode::LEX_STX_ERR->value;
                            return;                            
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
                            $operandTokenVal = explode('@', $operandTokenVal)[1];
                        }

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
