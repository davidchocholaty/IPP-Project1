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

enum TokenType : int {    
    case T_OP_CODE     = 0;
    case T_TYPE        = 1;
    case T_LANGUAGE_ID = 2;
    case T_LABEL       = 3;
    case T_VAR         = 4;
    case T_CONST       = 5;
    case T_EOF         = 6;
}

?>