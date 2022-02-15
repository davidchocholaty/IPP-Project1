<?php
/************************************************************************/
/*                                                                      */
/* Soubor: syntax.php                                                   */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-14                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici syntaktickou analyzu jazyka IPPcode22       */
/*                                                                      */
/************************************************************************/

function parser() {
    for ($i = 1; $i <= 10; $i++) {
        $inst = get_instruction();
        var_dump($inst);
    }    
    
}

?>