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
define("VALID", true);
define("INVALID", false);

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
 * @param $instruction_set Sada instrukci jazyka IPPcode22
 * @param $token           Vstupni token
 * @return                 V pripade kladne odpovedi true, jinak false
 */ 
function is_op_code($instruction_set, $token) {
    foreach(array_keys($instruction_set) as $op_code) {
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
 * @param $data_type Datove typy jazyka IPPcode22
 * @param $token     Vstupni token
 * @return           V pripade kladne odpovedi true, jinak false
 */ 
function is_data_type($data_type, $token) {
    foreach(array_keys($data_type) as $type) {
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
 * Funkce slouzi pro overeni validniho zapisu nazvu promenne
 * 
 * @param $var Nazev promenne
 * @return     V pripade validniho zapisu VALID (true), jinak INVALID (false)
 */
function valid_var($var) {
    $pattern = "~^(GF|LF|TF)@[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~";

    if(preg_match($pattern, $var)) {        
        return VALID;
    }

    return INVALID;
}

/*
 * Funkce slouzi pro overeni validniho zapisu nazvu navesti
 * 
 * @param $label Nazev navesti
 * @return     V pripade validniho zapisu VALID (true), jinak INVALID (false)
 */
function valid_label($label) {
    $pattern = "~^[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~";

    if(preg_match($pattern, $label)) {        
        return VALID;
    }

    return INVALID;
}

/*
 * Funkce slouzi pro overeni validniho zapisu konstanty
 *
 * @param $data_type Datove typy jazyka IPPcode22
 * @param $const     Konstanta
 * @return           V pripade validniho zapisu VALID (true), jinak INVALID (false)
 */
function valid_const($data_type, $const) {
    $const_parts = explode('#', $const);
    
    $const_type = $const_parts[0];
    $const_val = $const_parts[1];

    if(is_data_type($const_type)) {
        // TODO jestli nil muze byt hodnotou dalsich datovych typu
        switch($const_type) {
            case 'int':
                $pattern = "~^[+-]?[0-9]+$~";
                break;
            case 'bool':
                $pattern = "~^(true|false)$~";
                break;
            case 'string':
                // TODO pattern
                $pattern = "";
                break;
            case 'nil';
                $pattern = "~^(nil)$~";
                break;
        }        

        if(preg_match($pattern, $const)) {        
            return VALID;
        }
    }    

    return INVALID;
}

/*
 * Funkce provadejici lexikalni analyzu vstupni instrukce
 * 
 * @param $instruction Vstupni instrukce
 * @return             V pripade validniho zapisu instrukce array(VALID, tokeny instrukce),
 *                     jinak array(INVALID)
 */ 
function lexical_analysis($instruction) {
    global $instruction_set;
    global $data_type;

    $lex_status = VALID;
    $inst_tokens = array();    

    foreach($instruction as $token) {
        if(!str_contains($token, '@')) {
            /* Operacni kod, typ, navesti, identifikator jazyka */
            if(is_op_code($instruction_set, $token)) {
                array_push($inst_tokens,
                           token::T_OP_CODE->value,
                           $instruction_set[$token]);
            } elseif (is_data_type($data_type, $token)) {
                array_push($inst_tokens,
                           token::T_TYPE->value,
                           $data_type[$token]);
            } elseif (is_language_id($token)) {
                array_push($inst_tokens,
                           token::T_LANGUAGE_ID->value);
            } else {
                /* Navesti */
                if(!valid_name($token)) {
                    return array(INVALID);
                }
                
                array_push($inst_tokens,
                           token::T_LABEL->value,
                           $token);
            }
        } else {
            /* Promenna, konstanta */
            if(is_var($token)) {                
                if(!valid_name($token)) {
                    return array(INVALID);
                }

                array_push($inst_tokens,
                           token::T_VAR->value,
                           $token);
            } else {
                /* Konstanta */
                if(!valid_const($data_type, $token)) {
                    return array(INVALID);
                }                

                array_push($inst_tokens,
                           token::T_CONST->value,
                           $token);
            }
        }
    }
    
    return array(VALID, $inst_tokens);
}

/*
 * Funkce slouzi pro ziskani instrukce
 * pro syntakticky analyzator
 * 
 * 1. Nacteni vstupni intrukce
 * 2. Provedeni lexikalni analyzy vstupni instukce
 * 
 * @return V pripade validniho zapisu instrukce array(VALID, tokeny instrukce),
 *         jinak array(INVALID)
 */
function get_instruction() {    
    $instruction = read_instruction();

    return lexical_analysis($instruction);    
}

?>