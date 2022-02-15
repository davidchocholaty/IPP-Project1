<?php
/************************************************************************/
/*                                                                      */
/* Soubor: parse.php                                                    */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-15                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Hlavni skript lexikalni a syntakticke analyzy                 */
/*        pro jazyk IPPcode22                                           */
/*                                                                      */
/************************************************************************/

include 'exit_code.php';
include 'instruction_set.php';
include 'token_type.php';
include 'data_type.php';
include 'frame_type.php';
include 'lexical_analysis.php';
include 'syntax_analysis.php';

/************* DEFINICE ************/
define("ARGS_CNT", 2);
define("ARG_IDX", 1);

/************ PARAMETRY ************/
$shortopts = "h";
$longopts = array("help");
$options = getopt($shortopts, $longopts);

if (array_key_exists("help", $options) ||
    array_key_exists("h", $options)) {

    if ($argc == ARGS_CNT) {
        if($argv[ARG_IDX] == "-h" || $argv[ARG_IDX] == "--help") {            
            echo "parse.php napoveda:\n";
            echo "-h, --help              Vypise tuto napovedu.\n";
            //echo "-v, --verbose         Prints debug information.\n";
            //echo "-s FILE, --stats FILE Select file for statistics. One of the following parameters is required.\n";
            //echo "-l, --loc             Saves to statistic file count of instructions.\n";
            //echo "-c, --comments        Saves to statistic file count of comments.\n";
            
            exit(exit_code::EXIT_SUCCESS->value);
        }
        else {
            exit(exit_code::WRONG_PARAM->value);            
        }
    }
    else {
        exit(exit_code::WRONG_PARAM->value);        
    }
}

parser();

?>