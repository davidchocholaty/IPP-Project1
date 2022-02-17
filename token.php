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

/* Navrhovy vzor Abstraktni tovarna */
abstract class Token {
    private $token;

    abstract function getTokenType();

    public function __construct(int $token) {
        $this->token = $token;
    }

    public function getToken() {
        return $this->token;
    }
}

class OpCode extends Token {
    public function __construct(int $opCode){
        parent::__construct($opCode);        
    }

    public function getTokenType() {
        return 'OPCODE';
    }
}

class Operand extends Token {
    public function __construct(int $operand){
        parent::__construct($operand);        
    }

    public function getTokenType() {
        return 'OPERAND';
    }
}

class EndOfFile extends Token {    
    public function __construct(int $eof){
        parent::__construct($eof);        
    }

    public function getTokenType() {
        return 'EOF';
    }
}

abstract class TokenFactory {
    abstract public function createToken(int $token) : Token;
}

class OpCodeFactory extends TokenFactory {
    public function createToken(int $token) : Token {
        return new OpCode($token);
    }
}

class OperandFactory extends TokenFactory {
    public function createToken(int $token) : Token {
        return new Operand($token);
    }
}

class EndOfFileFactory extends TokenFactory {
    public function createToken(int $token) : Token {
        return new EndOfFile($token);
    }
}

?>