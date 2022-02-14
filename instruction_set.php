<?php
/************************************************************************/
/*                                                                      */
/* Soubor: instruction_set.php                                          */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-14                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici instrukcni sadu jazyka IPPcode22            */
/*                                                                      */
/************************************************************************/

$instruction_set = array(
/* Prace s ramci, volani funkci */
"MOVE",
"CREATEFRAME",
"PUSHFRAME",
"POPFRAME",
"DEFVAR",
"RETURN",
/* Prace s datovym zasobnikem */
"PUSHS",
"POPS",
/* Aritmeticke, relacni, booleovske a konverzni instrukce */
"ADD",
"SUB",
"MUL",
"IDIV",
"LT",
"GT",
"EQ",
"AND",
"OR",
"NOT",
"INT2CHAR",
"STRI2INT",
/* Vstupne vystupni instrukce */
"READ",
"WRITE",
/* Prace s retezci */
"CONCAT",
"STRLEN",
"GETCHAR",
"SETCHAR",
/* Prace s typy */
"TYPE",
/* Instrukce pro rizeni toku programu */
"LABEL",
"JUMP",
"JUMPIFEQ",
"JUMPIFNEQ",
"EXIT",
/* Ladici instrukce */
"DPRINT",
"BREAK",
)

?>