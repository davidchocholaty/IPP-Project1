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

/*********** CHYBOVE KODY **********/
enum ExitCode : int {
    case EXIT_SUCCESS          =  0;
    case WRONG_PARAM           = 10;
    case INPUT_FILE_OPEN_ERR   = 11;
    case OUTPUT_FILE_OPEN_ERR  = 12;
    case FILE_FOLDER_NOT_EXIST = 41;
    case PRINT_HELP            = 98;
    case INTERN_ERR            = 99;
}

?>