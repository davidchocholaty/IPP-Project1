<?php
/************************************************************************/
/*                                                                      */
/* Soubor: instruction_set.php                                          */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici instrukcni sadu jazyka IPPcode22            */
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici instrukcni sadu
 */
final class InstructionSet {    
    /********* INSTRUKCNI SADA *********/
    public static $instructionSet = array(
    /*
     * Z duvodu zabraneni mnoha porovnavani celych
     * retezcu jsou typy operandu znaceny pouze
     * znakem reprezentujicim pocatecni pismeno
     * nazvu daneho typu operandu
     *  
     * Vyznam:
     * v -> var
     * s -> symb
     * l -> label
     * t -> type
     */ 
    
    /* Prace s ramci, volani funkci */
    'MOVE'        => ['v', 's'],
    'CREATEFRAME' => [],
    'PUSHFRAME'   => [],
    'POPFRAME'    => [],
    'DEFVAR'      => ['v'],
    'CALL'        => ['l'],
    'RETURN'      => [],
    /* Prace s datovym zasobnikem */
    'PUSHS'       => ['s'],
    'POPS'        => ['v'],
    /* Aritmeticke, relacni, booleovske a konverzni instrukce */
    'ADD'         => ['v', 's', 's'],
    'SUB'         => ['v', 's', 's'],
    'MUL'         => ['v', 's', 's'],
    'IDIV'        => ['v', 's', 's'],
    'LT'          => ['v', 's', 's'],
    'GT'          => ['v', 's', 's'],
    'EQ'          => ['v', 's', 's'],
    'AND'         => ['v', 's', 's'],
    'OR'          => ['v', 's', 's'],
    'NOT'         => ['v', 's', 's'],
    'INT2CHAR'    => ['v', 's'],
    'STRI2INT'    => ['v', 's', 's'],
    /* Vstupne vystupni instrukce */
    'READ'        => ['v', 't'],
    'WRITE'       => ['s'],
    /* Prace s retezci */
    'CONCAT'      => ['v', 's', 's'],
    'STRLEN'      => ['v', 's'],
    'GETCHAR'     => ['v', 's', 's'],
    'SETCHAR'     => ['v', 's', 's'],
    /* Prace s typy */
    'TYPE'        => ['v', 's'],
    /* Instrukce pro rizeni toku programu */
    'LABEL'       => ['l'],
    'JUMP'        => ['l'],
    'JUMPIFEQ'    => ['v', 's', 's'],
    'JUMPIFNEQ'   => ['v', 's', 's'],
    'EXIT'        => ['s'],
    /* Ladici instrukce */
    'DPRINT'      => ['s'],
    'BREAK'       => [],
    );
}

?>