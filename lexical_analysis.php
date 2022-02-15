<?php
/************************************************************************/
/*                                                                      */
/* Soubor: scanner.php                                                  */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-15                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici syntaktickou analyzu jazyka IPPcode22       */
/*                                                                      */
/************************************************************************/

/************* DEFINICE ************/
define("LANGUAGE_ID", ".IPPcode22");

/*
 * Funkce slouzi pro odstraneni jednoradkoveho
 * komentare nasledujiciho za samotnou instrukci
 * 
 * @params $input_line Vstupni radek
 * @return             Vstupni radek bez komentare
 */
function remove_comment($input_line) { 
    return explode('#', $input_line)[0];
}

/*
 * Funkce slouzi pro odstraneni znaku noveho
 * radku nebo bilych znaku na konci vstupniho radku
 * 
 * @param $input line Vstupni radek obsahujici znak noveho radku
 * @return            Vstupni radek bez znaku noveho radku
 */
function trim_line_end($input_line) {
    $no_new_line = rtrim($input_line, "\n");
    $no_white_spaces = rtrim($input_line);
    
    return $no_white_spaces;
}

/*
 * Funkce prevede vstupni retezec na pole obsahujici
 * jednotliva slova retezce (tokeny)
 * Pritom jsou ignorovany vsechny bile znaky
 * 
 * @param $str Vstupni retecez
 * @return     Vysledne pole obsahujici tokeny
 */ 
function str_2_arr($str) {
    return preg_split("/\s+/", $str);
}

/*
 * Funkce slouzi pro nacteni jedne instrukce ze vstupu
 * Pritom jsou ignorovany komentare, prazdne radky a bile znaky
 * 
 * @return Nactena instrukce
 */
function read_instruction() {  
    while(($input_line = fgets(STDIN)) !== false){
        if (str_starts_with($input_line, '#') ||
            str_starts_with($input_line, '\n')) {

            continue;
        } else {            
            $input_line = remove_comment($input_line);
            $input_line = trim_line_end($input_line);

            return str_2_arr($input_line);            
        }
    }
    
    /* EOF */
    return array(token::T_EOF->value);
}

/*
 * Funkce slouzi pro urceni, zda dany token
 * je operacni kod (nazev instrukce)
 * Pro operacni kod plati, ze nezalezi
 * na velikosti pismen
 * 
 * @param token Vstupni token
 * @return      V pripade kladne odpovedi true, jinak false
 */ 
function is_op_code($token) {
    foreach($instruction_set as $op_code) {
        if(strcasecmp($op_code, $token) == 0) {
            return true;
        }
    }

    return false;
}

/*
 * Funkce slouzi pro urceni, zda dany token
 * je datovy typ
 * 
 * @param token Vstupni token
 * @return      V pripade kladne odpovedi true, jinak false
 */ 
function is_data_type($token) {
    foreach($data_type as $type) {
        if(strcmp($type, $token) == 0) {
            return true;
        }
    }

    return false;
}

/*
 * Funkce slouzi pro urceni, zda dany token
 * je identifikator jazyka: '.IPPcode22', kdy
 * nezalezi na velikosti pismen
 * 
 * @param token Vstupni token
 * @return      V pripade kladne odpovedi true, jinak false
 */ 
function is_language_id($token) {
    if(strcasecmp(LANGUAGE_ID, $token) == 0) {
        return true;
    }

    return false;
}

/*
 * Funkce slouzi pro urceni, zda dany token
 * je promenna
 * 
 * @param token Vstupni token
 * @return      V pripade kladne odpovedi true, jinak false
 */ 
function is_var($token) {
    $var_frame = explode('@', $token)[0];

    foreach($frame_type as $frame) {
        if(strcmp($frame, $var_frame) == 0) {
            return true;
        }
    }

    return false;
}

/*
 * Funkce provadejici lexikalni analyzu vstupni instrukce
 * 
 * @param $instruction Vstupni instrukce
 */ 
function lexical_analysis($instruction) {    
    foreach($instruction as $token) {
        if(!str_contains($token, '@')) {
            /* Operacni kod, typ, navesti, identifikator jazyka */
            if(is_op_code($token)) {

            } elseif (is_data_type($token)) {

            } elseif (is_language_id($token)) {

            } else {
                /* Navesti */
            }
        } else {
            /* Promenna, konstanta */
            if(is_var($token)) {

            } else {
                /* Konstanta */
            }
        }
    }    
}

/*
 * Funkce slouzi pro ziskani instrukce
 */
function get_instruction() {    
    /*
    while(($instruction = read_instruction()) !== array(0))
    {
        var_dump($instruction);
    }
    */

    $instruction = read_instruction();
    lexical_analysis($instruction);
    
}

?>