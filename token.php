<?php
/************************************************************************/
/*                                                                      */
/* Soubor: token.php                                                    */
/* Vytvoren: 2022-02-16                                                 */
/* Posledni zmena: 2022-03-06                                           */
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
    private $tokenCode;
    private $tokenVal;

    /*
     * Abstraktni metoda pro ziskani typu tokenu
     */
    abstract function getType();

    /*
     * Konstruktor
     * 
     * @param $token Kod tokenu
     */ 
    public function __construct(int $tokenCode, string $tokenVal) {
        $this->tokenCode = $tokenCode;
        $this->tokenVal = $tokenVal;
    }

    /*
     * Metoda pro ziskani tokenu
     * 
     * @return Navraci token
     */ 
    public function getTokenCode() {
        return $this->tokenCode;
    }

    public function getTokenVal() {
        return $this->tokenVal;
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
    public function __construct(int $opCode, string $opCodeVal){
        parent::__construct($opCode, $opCodeVal);
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
    public function __construct(int $operand, string $operandVal){
        parent::__construct($operand, $operandVal);
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
     * @param $eof Kod konce vstupniho souboru
     */   
    public function __construct(int $eofCode){
        parent::__construct($eofCode, '');        
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

class LanguageIdentifier extends Token {
    private static $instance = NULL;
    /*
     * Konstruktor
     * 
     * @param $languageId 
     */   
    public function __construct(int $languageIdCode){
        parent::__construct($languageIdCode, '');
    }

    /*
     * Metoda pro ziskani typu instance tokenu
     * 
     * @return Navraci typ instance tokenu
     */ 
    public function getType() {
        return 'LANGUAGE_ID';
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
    abstract public function createToken(int $tokenCode, string $tokenVal) : Token;
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
    public function createToken(int $tokenCode, string $tokenVal) : Token {
        return new OpCode($tokenCode, $tokenVal);
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
    public function createToken(int $tokenCode, string $tokenVal) : Token {
        return new Operand($tokenCode, $tokenVal);
    }
}

?>
