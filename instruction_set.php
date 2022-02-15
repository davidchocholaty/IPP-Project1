<?php
/************************************************************************/
/*                                                                      */
/* Soubor: instruction_set.php                                          */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-15                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici instrukcni sadu jazyka IPPcode22            */
/*                                                                      */
/************************************************************************/

$instruction_set = array(
/* Prace s ramci, volani funkci */
'MOVE'        =>  0,
'CREATEFRAME' =>  1,
'PUSHFRAME'   =>  2,
'POPFRAME'    =>  3,
'DEFVAR'      =>  4,
'RETURN'      =>  5,
/* Prace s datovym zasobnikem */
'PUSHS'       =>  6,
'POPS'        =>  7,
/* Aritmeticke, relacni, booleovske a konverzni instrukce */
'ADD'         =>  8,
'SUB'         =>  9,
'MUL'         => 10,
'IDIV'        => 11,
'LT'          => 12,
'GT'          => 13,
'EQ'          => 14,
'AND'         => 15,
'OR'          => 16,
'NOT'         => 17,
'INT2CHAR'    => 18,
'STRI2INT'    => 19,
/* Vstupne vystupni instrukce */
'READ'        => 20,
'WRITE'       => 21,
/* Prace s retezci */
'CONCAT'      => 22,
'STRLEN'      => 23,
'GETCHAR'     => 24,
'SETCHAR'     => 25,
/* Prace s typy */
'TYPE'        => 26,
/* Instrukce pro rizeni toku programu */
'LABEL'       => 27,
'JUMP'        => 28,
'JUMPIFEQ'    => 29,
'JUMPIFNEQ'   => 30,
'EXIT'        => 31,
/* Ladici instrukce */
'DPRINT'      => 32,
'BREAK'       => 33,
);

?>