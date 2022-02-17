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
include 'token.php';
include 'token_type.php';
include 'token_util.php';
include 'data_type.php';
include 'frame_type.php';
include 'string_util.php';
include 'lexical_analysis.php';
include 'syntax_analysis.php';

/************* DEFINICE ************/
define("ARGS_CNT", 2);
define("ARG_IDX", 1);

/*
 * Funkce slouzi pro vypis napovedy na standardni vystup
 */
function print_help() {
    //TODO print echo funkce     
    echo "parse.php napoveda:\n";
    echo "-h, --help              Vypise tuto napovedu.\n";
    //echo "-v, --verbose         Prints debug information.\n";
    //echo "-s FILE, --stats FILE Select file for statistics. One of the following parameters is required.\n";
    //echo "-l, --loc             Saves to statistic file count of instructions.\n";
    //echo "-c, --comments        Saves to statistic file count of comments.\n";
}

/************ PARAMETRY ************/
$shortopts = "h";
$longopts = array("help");
$options = getopt($shortopts, $longopts);

if (array_key_exists("help", $options) ||
    array_key_exists("h", $options)) {

    if ($argc == ARGS_CNT) {
        if($argv[ARG_IDX] == "-h" || $argv[ARG_IDX] == "--help") {       
            print_help();            
            exit(ExitCode::EXIT_SUCCESS->value);
        }
        else {
            exit(ExitCode::WRONG_PARAM->value);
        }
    }
    else {
        exit(ExitCode::WRONG_PARAM->value);
    }
}

$parser = Parser::getInstance();
$parser->parse();

?>