<?php
/************************************************************************/
/*                                                                      */
/* Soubor: exit_code.php                                                */
/* Vytvoren: 2022-02-15                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Chybove kody pro skripty lexikalni a syntakticke analyzy      */ 
/*                                                                      */
/************************************************************************/

/*********** CHYBOVE KODY **********/
enum ExitCode : int {
    case EXIT_SUCCESS =  0;
    case WRONG_PARAM  = 10;
    case BAD_HEADER   = 21;
    case BAD_OP_CODE  = 22;
    case LEX_STX_ERR  = 23;
    case INTERN_ERR   = 99;
}

?>