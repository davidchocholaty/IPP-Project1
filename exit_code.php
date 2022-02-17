<?php
/************************************************************************/
/*                                                                      */
/* Soubor: exit_code.php                                                */
/* Vytvoren: 2022-02-15                                                 */
/* Posledni zmena: 2022-02-15                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Chybove kody pro skripty lexikalni a syntakticke analyzy      */ 
/*                                                                      */
/************************************************************************/

/*********** CHYBOVE KODY **********/
final class ExitCode {
    public const EXIT_SUCCESS =  0,
                 WRONG_PARAM  = 10,
                 BAD_HEADER   = 21,
                 BAD_OP_CODE  = 22,
                 LEX_STX_ERR  = 23,
                 INTERN_ERR   = 99;    
}

?>