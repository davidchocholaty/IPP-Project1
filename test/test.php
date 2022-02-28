<?php
/************************************************************************/
/*                                                                      */
/* Soubor: test.php                                                     */
/* Vytvoren: 2022-02-28                                                 */
/* Posledni zmena: 2022-02-28                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 2 pro predmet IPP                                     */
/* Popis: Testovaci skript                                              */
/*                                                                      */
/************************************************************************/

include 'exit_code.php';
include 'argument.php';
include 'html_gen.php';
include 'directory_structure.php';
include 'parse_tester.php';
include 'int_tester.php';

class Tester {
    private $arguments;
    private $dirStruct;

    public function __construct() {
        $arguments = new Argument();
        $dirStruct = new DirectoryStructure();
    }

    public function initTester($argc) {
        $status = $this->arguments->parseArgs($argc);

        if ($status !== ExitCode::EXIT_SUCCESS->value) {
            return $status;
        }

        $this->dirStruct->initDirectoryStructure($this->arguments->$dir,
                                                 $this->arguments->$recFlag);

        return ExitCode::EXIT_SUCCESS->value;
    }

    public function run() {
        foreach ($this->dirStruct->$dirs as $dir) {
            foreach ($this->dirStruct->$files as $file) {
                
            }
        }
    }

    public function createPage() {
        $gen = new HTMLGen();
        $gen->generateHTMLPage();
    }
}




//$arguments = new Argument();

//$status = $arguments->parseArgs($argc);

//if ($status !== ExitCode::EXIT_SUCCESS->value) {
//    exit($status);
//}

//$iter = new Iterator();
//$iter->init($arguments->dir, $arguments->recFlag);







/******************** HLAVNI SKRIPT ********************/

$tester = new Tester();

$status = $tester->initTester($argc);

if ($status !== ExitCode::EXIT_SUCCESS->value) {
    exit($status);
}

$tester->run();
$htmlPage = $tester->createPage();

exit(ExitCode::EXIT_SUCCESS->value);

?>