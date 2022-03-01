<?php
/************************************************************************/
/*                                                                      */
/* Soubor: exit_code.php                                                */
/* Vytvoren: 2022-02-28                                                 */
/* Posledni zmena: 2022-02-28                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 2 pro predmet IPP                                     */
/* Popis: Chybove kody pro testovaci skript                             */ 
/*                                                                      */
/************************************************************************/

enum TestType : int {
    case PARSE_ONLY = 0;
    case INT_ONLY   = 1;
    case BOTH       = 2;
}

?>