<?php
/************************************************************************/
/*                                                                      */
/* Soubor: token.php                                                    */
/* Vytvoren: 2022-02-16                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript se tridou reprezentujici tokeny                        */ 
/*                                                                      */
/************************************************************************/

/*
 * Abstraktni trida reprezentujici token
 * 
 * Pouzity navrhovy vzor: Abstraktni tovarna
 */
abstract class Token {
    private $token;

    /*
     * Abstraktni metoda pro ziskani typu tokenu
     */ 
    abstract function getType();

    /*
     * Konstruktor
     * 
     * @param $token Kod tokenu
     */ 
    public function __construct(int $token) {
        $this->token = $token;
    }

    /*
     * Metoda pro ziskani tokenu
     * 
     * @return Navraci token
     */ 
    public function getToken() {
        return $this->token;
    }
}

/*
 * Trida reprezentujici operacni kod
 */ 
class OpCode extends Token {
    /*
     * Konstruktor
     * 
     * @param $opCode Kod operacniho kodu
     */ 
    public function __construct(int $opCode){
        parent::__construct($opCode);        
    }

    /*
     * Metoda pro ziskani typu instance tokenu
     * 
     * @return Navraci typ instance tokenu
     */ 
    public function getType() {
        return 'OPCODE';
    }
}

/*
 * Trida reprezentujici operand
 */ 
class Operand extends Token {
    /*
     * Konstruktor
     * 
     * @param $operand Kod operandu
     */
    public function __construct(int $operand){
        parent::__construct($operand);        
    }

    /*
     * Metoda pro ziskani typu instance tokenu
     * 
     * @return Navraci typ instance tokenu
     */ 
    public function getType() {
        return 'OPERAND';
    }
}

/*
 * Trida reprezentujici konec vstupniho toku
 */ 
class EndOfFile extends Token {
    /*
     * Konstruktor
     * 
     * @param $operand Kod konce vstupniho souboru
     */   
    public function __construct(int $eof){
        parent::__construct($eof);        
    }

    /*
     * Metoda pro ziskani typu instance tokenu
     * 
     * @return Navraci typ instance tokenu
     */ 
    public function getType() {
        return 'EOF';
    }
}

/*
 * Abstraktni trida reprezentujici tovarnu tokenu
 */
abstract class TokenFactory {
    /*
     * Abstraktni metoda pro vytvoreni noveho tokenu
     * 
     * @param $token Kod tokenu
     */
    abstract public function createToken(int $token) : Token;
}

/*
 * Trida reprezentujici tovatru operacnich kodu
 */ 
class OpCodeFactory extends TokenFactory {
    /*
     * Metoda pro vytvoreni noveho tokenu operacniho kodu
     * 
     * @param $token Kod tokenu operacniho kodu
     * @return       Nova instance operacniho kodu
     */
    public function createToken(int $token) : Token {
        return new OpCode($token);
    }
}

/*
 * Trida reprezentujici tovarnu operandu
 */ 
class OperandFactory extends TokenFactory {
    /*
     * Metoda pro vytvoreni noveho tokenu operandu
     * 
     * @param $token Kod tokenu operandu
     * @return       Nova instance operandu
     */
    public function createToken(int $token) : Token {
        return new Operand($token);
    }
}

?>