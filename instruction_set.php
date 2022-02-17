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
    /********* KODY INSTRUKCI **********/
    public static $instructionCodes = array (
        /* Prace s ramci, volani funkci */
         0 => 'MOVE',
         1 => 'CREATEFRAME',
         2 => 'PUSHFRAME',
         3 => 'POPFRAME',
         4 => 'DEFVAR',
         5 => 'CALL',
         6 => 'RETURN',
        /* Prace s datovym zasobnikem */
         7 => 'PUSHS',
         8 => 'POPS',
        /* Aritmeticke, relacni, booleovske a konverzni instrukce */
         9 => 'ADD',
        10 => 'SUB',
        11 => 'MUL',
        12 => 'IDIV',
        13 => 'LT',
        14 => 'GT',
        15 => 'EQ',
        16 => 'AND',
        17 => 'OR',
        18 => 'NOT',
        19 => 'INT2CHAR',
        20 => 'STRI2INT',
        /* Vstupne vystupni instrukce */
        21 => 'READ',
        22 => 'WRITE',
        /* Prace s retezci */
        23 => 'CONCAT',
        24 => 'STRLEN',
        25 => 'GETCHAR',
        26 => 'SETCHAR',
        /* Prace s typy */
        27 => 'TYPE',
        /* Instrukce pro rizeni toku programu */
        28 => 'LABEL',
        29 => 'JUMP',
        30 => 'JUMPIFEQ',
        31 => 'JUMPIFNEQ',
        32 => 'EXIT',
        /* Ladici instrukce */
        33 => 'DPRINT',
        34 => 'BREAK',
    );


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