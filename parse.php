<?php
/************************************************************************/
/*                                                                      */
/* Soubor: parse.php                                                    */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-14                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Hlavni skript lexikalni a syntakticke analyzy                 */
/*        pro jazyk IPPcode22                                           */
/*                                                                      */
/************************************************************************/

include 'scanner.php'
include 'syntax.php'

/************* DEFINICE ************/
define("ARGS_CNT", 2);
define("ARG_IDX", 1);

/*********** CHYBOVE KODY **********/
enum exit_code : int {
    case EXIT_SUCCESS = 0;
    case WRONG_PARAM = 10;    
    case BAD_HEADER = 21;
    case BAD_OP_CODE = 22;
    case LEX_STX_ERR = 23;
    case INTERN_ERR = 99;
}



/************ PARAMETRY ************/
$shortopts = "h";
$longopts = array("help");
$options = getopt($shortopts, $longopts);

if (array_key_exists("help", $options) ||
    array_key_exists("h", $options)) {

    if ($argc == ARGS_CNT)
    {
        if($argv[ARG_IDX] == "-h" || $argv[ARG_IDX] == "--help") {
            // TODO do cestiny bez diakritiky
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



?>