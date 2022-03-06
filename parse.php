<?php
/************************************************************************/
/*                                                                      */
/* Soubor: parse.php                                                    */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-03-06                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Hlavni skript lexikalni a syntakticke analyzy                 */
/*        pro jazyk IPPcode22                                           */
/*                                                                      */
/************************************************************************/

ini_set('display_errors', 'stderr');

include 'array_to_xml.php';
include 'exit_code.php';
include 'instruction_set.php';
include 'definitions.php';
include 'token.php';
include 'token_type.php';
include 'data_type.php';
include 'frame_type.php';
include 'instruction.php';
include 'token_util.php';
include 'string_util.php';
include 'lexical_analysis.php';
include 'syntax_analysis.php';

/*
 * Funkce slouzi pro vypis napovedy na standardni vystup
 */
function printHelp() {
    echo "parse.php napoveda:\n";
    echo "-h, --help              Vypise tuto napovedu\n";
}

function createXml($prog) {
    $root = array(
        'rootElementName' => 'program',
        '_attributes' => [
            'language' => 'IPPcode22',
        ],
    );
    
    return Array2Xml::convert($prog, $root);
}

function printXml($xml) {
    echo $xml;
}

/******************** HLAVNI SKRIPT ********************/

/************ PARAMETRY ************/
$shortOpts = "h";
$longOpts = array("help");
$options = getopt($shortOpts, $longOpts);

if (array_key_exists("help", $options) ||
    array_key_exists("h", $options)) {

    if ($argc == ARGS_CNT) {
        if($argv[ARG_IDX] == "-h" || $argv[ARG_IDX] == "--help") {       
            printHelp();            
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

/************* ANALYZA *************/
Parser::parse();

$status = Parser::getStatus();

if($status !== ExitCode::EXIT_SUCCESS->value) {
    exit($status);
}

$prog = Parser::getParseProg();

$xml = createXml($prog);
printXml($xml);

exit(ExitCode::EXIT_SUCCESS->value);

?>
