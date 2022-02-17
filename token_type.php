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

final class TokenType {
    public const T_OP_CODE     = 0,
                 T_TYPE        = 1,
                 T_LANGUAGE_ID = 2,
                 T_LABEL       = 3,
                 T_VAR         = 4,
                 T_CONST       = 5,
                 T_EOF         = 6;
}

?>