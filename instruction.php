<?php
/************************************************************************/
/*                                                                      */
/* Soubor: instruction.php                                              */
/* Vytvoren: 2022-02-17                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript se tridou reprezentujici instrukci obsahujici tokeny   */
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici instrukci obsahujici tokeny
 */
class Instruction {
    private $status;
    private $instruction;

    /*
     * Konstruktor
     */
    public function __construct() {
        $args = func_get_args();
        $numOfArgs = func_num_args();

        if(method_exists($this, $function = '__construct'.$numOfArgs)) {
            call_user_func_array(array($this, $function), $args);
        }
    }

    /*
     * Konstruktor s jednim parametrem
     * - promenna $status
     */ 
    public function __construct1(int $status) {
        $this->status = $status;
        $this->instruction = NULL;
    }

    /*
     * Konstruktor se dvema parametry
     * - promenna $status
     * - pole $instruction
     */ 
    public function __construct2(int $status, array $instruction) {
        $this->status = $status;
        $this->instruction = $instruction;
    }

    /*
     * Metoda pro ziskani statusu instrukce (VALID nebo INVALID)
     * 
     * @return Status instrukce
     */ 
    public function getStatus() {
        return $this->status;
    }

    /*
     * Metoda pro ziskani instrukce obsahujici tokeny
     * 
     * @return Instrukce s tokeny
     */
    public function getInstTokens() {
        return $this->instruction;
    }
}

?>