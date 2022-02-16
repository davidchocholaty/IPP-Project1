<?php
/************************************************************************/
/*                                                                      */
/* Soubor: token_type.php                                               */
/* Vytvoren: 2022-02-15                                                 */
/* Posledni zmena: 2022-02-15                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Typy tokenu pro skripty lexikalni a syntakticke analyzy       */ 
/*                                                                      */
/************************************************************************/
enum token_type : int {
        case T_OP_CODE     = 0;
        case T_TYPE        = 1;
        case T_LANGUAGE_ID = 2;
        case T_LABEL       = 3;
        case T_VAR         = 4;
        case T_CONST       = 5;
        case T_EOF         = 6;
    }


/* Navrhovy vzor Abstraktni tovarna */

abstract class Token {
    private $token;

    public function __construct($token) {
        $this->token = $token;
    }

    public function getToken() {
        return $this->token;
    }
}

class OpCode extends Token {
    public function __construct($opCode){
        parent::__construct($opCode);        
    }    
}

class Operand extends Token {
    public function __construct($operand){
        parent::__construct($operand);        
    }
}

class EndOfFile extends Token {
    public function __construct($eof){
        parent::__construct($eof);        
    }
}

abstract class TokenFactory {
    abstract public function createToken($token) : Token;
}

class OpCodeFactory extends TokenFactory {
    public function createToken($token) : Token {
        return new OpCode($token);
    }
}

class OperandFactory extends TokenFactory {
    public function createToken($token) : Token {
        return new Operand($token);
    }
}

class EndOfFileFactory extends TokenFactory {
    public function createToken($token) : Token {
        return new EndOfFile($token);
    }
}

?>