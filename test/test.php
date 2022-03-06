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
include 'test_type.php';

class Tester {
    private $arguments;
    private $dirStruct;

    public function __construct() {
        $this->arguments = new Argument();
        $this->dirStruct = new DirectoryStructure();
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
        if ($this->arguments->$parseOnlyFlag) {
            $testType = TestType::PARSE_ONLY->value;
            $parseTester = new ParseTester();
        } elseif ($this->arguments->$intOnlyFlag) {
            $testType = TestType::INT_ONLY->value;
            $intTester = new IntTester();            
        } else {
            $testType = TestType::BOTH->value;
            $parseTester = new ParseTester();
            $intTester = new IntTester();            
        }

        foreach ($this->dirStruct->$dirs as $dir) {
            foreach ($this->dirStruct->$files as $file) {
                $src = $dir.$file['testName'].'.src';
                $in  = $dir.$file['testName'].'.in';                
                $out = $dir.$file['testName'].'.out';
                $rc = $dir.$file['testName'].'.rc';

                switch($testType) {
                    case TestType::PARSE_ONLY->value:
                        $parseTester->run();
                        break;

                    case TestType::INT_ONLY->value:
                        $intTester->run();
                        break;

                    case TestType::BOTH->value:
                        $parseTester->run();
                        $intTester->run();
                        break;

                    default:
                        break;
                }
            }
        }
    }

    public function createPage() {
        $gen = new HTMLGen();
        $gen->generateHTMLPage();
    }
}

/******************** HLAVNI SKRIPT ********************/

$tester = new Tester();

$status = $tester->initTester($argc);

if ($status !== ExitCode::EXIT_SUCCESS->value) {
    exit($status);
}

$tester->run();
//$htmlPage = $tester->createPage();

exit(ExitCode::EXIT_SUCCESS->value);

?>