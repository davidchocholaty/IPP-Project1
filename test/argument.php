<?php
/************************************************************************/
/*                                                                      */
/* Soubor: exit_code.php                                                */
/* Vytvoren: 2022-02-28                                                 */
/* Posledni zmena: 2022-02-28                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 2 pro predmet IPP                                     */
/* Popis: Chybove kody pro testovaci skript                             */ 
/*                                                                      */
/************************************************************************/

/******** DEFINICE ARGUMENTY ********/
define("ARGS_CNT", 2);
define("ARG_IDX", 1);

define("VALID", true);
define("INVALID", false);

class Argument {
    private $shortOpts = "hd:rp:i:PIj:n";
    private $longOpts = array("help",
                              "directory:",
                              "recursive",
                              "parse-script:",
                              "int-script:",
                              "parse-only",
                              "int-only",
                              "jexampath:",
                              "noclean");

    public $dir;
    public $recFlag;
    public $parseScript;
    public $intScript;
    public $parseOnlyFlag;
    public $intOnlyFlag;
    public $jexamxmlPath;
    public $noCleanFlag;

    public function __construct() {
        $this->dir           = "./";             /* Vychozi: Aktualni adresar */
        $this->recFlag       = false;            /* Vychozi: false */
        $this->parseScript   = "./parse.php";    /* Vychozi: parse.php v aktualnim adresari*/
        $this->intScript     = "./interpret.py"; /* Vychozi: interpret.py v aktualnim adresari*/
        $this->parseOnlyFlag = false;            /* Vychozi: false*/
        $this->intOnlyFlag   = false;            /* Vychozi: false*/
        $this->noCleanFlag   = false;            /* Vychozi: false*/

        /* Vychozi: Slozka obsahujici soubor na serveru Merlin*/
        $this->jexamxmlPath  = "/pub/courses/ipp/jexamxml/jexamxml.jar";
    }

    /*
     * Funkce slouzi pro vypis napovedy na standardni vystup
     */
    private function printHelp() {
        echo "test.php napoveda:\n";
        echo "-h, --help                    Vypise tuto napovedu\n";
        echo "-d=path, --directory=path     Adresar obsahujici testy\n";
        echo "-r, --recursive               Hledani testu rekurzivne ve vsech podadresarich adresare\n";
        echo "-p=file, --parse-script=file  Soubor se skriptem v PHP8.1 pro analyzu zdrojoveho kodu v IPPcode22\n";
        echo "-i=file, --int-script=file    Soubor se skriptem v Python3 pro interpret XML reprezentace kodu v IPPcode22\n";
        echo "-P, --parse-only              Testovani pouze skriptu pro analyzu zdrojoveho kodu v IPPcode22\n";
        echo "-I, --int-only                Testovani pouze skriptu pro intepret XML reprezentace kodu v IPPcode22\n";
        echo "-j=path, --jexampath=path     Cesta k adresari obsahujici soubor jexamxml.jar\n";
        echo "-n, --noclean                 Zakaz mazani pomocnych souboru s mezivysledky\n";
    }

    private function checkHelp($options) {
        if (array_key_exists("help", $options) ||
            array_key_exists("h", $options)) {

            if ($argc == ARGS_CNT) {
                if ($argv[ARG_IDX] == "-h" || $argv[ARG_IDX] == "--help") {
                    printHelp();
                    return ExitCode::PRINT_HELP->value;
                }
            }
            else {
                return ExitCode::WRONG_PARAM->value;
            }
        }

        return ExitCode::EXIT_SUCCESS->value;
    }

    private function checkIntOnlyCompatibility() {
        if ($this->parseOnlyFlag) {
            return INVALID;
        }

        return VALID;
    }

    private function checkParseScriptCompatibility() {
        if ($this->intOnlyFlag) {
            return INVALID;
        }

        return VALID;
    }

    private function checkIntScriptCompatibility() {
        if ($this->parseOnlyFlag) {
            return INVALID;
        }

        return VALID;
    }

    private function checkFolder() {
        if (!is_readable($this->dir) || !is_dir($this->dir)) {
            return INVALID;
        }

        return VALID;
    }

    private function checkFiles() {
        if (!is_readable($this->parseScript)) {
            return INVALID;
        }

        if (!is_readable($this->intScript)) {
            return INVALID;
        }

        if (!is_readable($this->jexamxmpPath)) {
            return INVALID;
        }

        return VALID;
    }

    private function setUserArgs($options) {
        /* DIRECTORY OPTION */
        if (array_key_exists("directory", $options)) {
            $this->dir = $options["directory"];
        } elseif (array_key_exists("d", $options)) {
            $this->dir = $options["d"];
        }

        /* RECURSIVE OPTION */
        if (array_key_exists("recursive", $options)) {
            $this->recFlag = true; 
        } elseif (array_key_exists("r", $options)) {
            $this->recFlag = true; 
        }

        /* PARSE-ONLY OPTION */
        if (array_key_exists("parse-only", $options)) {
            $this->parseOnlyFlag = true;
        } elseif (array_key_exists("P", $options)) {
            $this->parseOnlyFlag = true;
        }

        /* INT-ONLY OPTION */
        if (array_key_exists("int-only", $options)) {
            $this->intOnlyFlag = true;

            $status = checkIntOnlyCompatibility();

            if ($status == INVALID) {
                return ExitCode::WRONG_PARAM->value;
            }

        } elseif (array_key_exists("I", $options)) {
            $this->intOnlyFlag = true;

            $status = checkIntOnlyCompatibility();

            if ($status == INVALID) {
                return ExitCode::WRONG_PARAM->value;
            }
        }

        /* PARSE-SCRIPT OPTION */
        if (array_key_exists("parse-script", $options)) {
            $this->parseScript = $options["parse-script"];

            $status = checkParseScriptCompatibility();

            if ($status == INVALID) {
                return ExitCode::WRONG_PARAM->value;
            }
        } elseif (array_key_exists("p", $options)) {
            $this->parseScript = $options["p"];

            $status = checkParseScriptCompatibility();

            if ($status !== INVALID) {
                return ExitCode::WRONG_PARAM->value;
            }
        }

        /* INT-SCRIPT OPTION */
        if (array_key_exists("int-script", $options)) {
            $this->intScript = $option["int-script"];

            $status = checkIntScriptCompatibility();
            
            if ($status !== INVALID) {
                return ExitCode::WRONG_PARAM->value;
            }
        } elseif (array_key_exists("i", $options)) {
            $this->intScript = $option["i"];

            $status = checkIntScriptCompatibility();
            
            if ($status !== INVALID) {
                return ExitCode::WRONG_PARAM->value;
            }
        }

        /* JEXAMPATH OPTION */
        if (array_key_exists("jexampath", $options)) {
            $this->jexampath = $option["jexampath"];
        } elseif (array_key_exists("j", $options)) {
            $this->jexampath = $option["j"];
        }

        /* NOCLEAN OPTION */
        if (array_key_exists("noclean", $options)) {
            $this->noCleanFlag = $option["noclean"];
        } elseif (array_key_exists("n", $options)) {
            $this->noCleanFlag = $option["n"];
        }

        return ExitCode::EXIT_SUCCESS->value;
    }    

    public function parseArgs($argc) {
        $restIdx = null;
        $options = getopt($this->shortOpts, $this->longOpts);

        /* Wrong argument */
        if (count($options) !== ($argc - 1)) {
            return ExitCode::WRONG_PARAM->value;
        }

        /********* CHECK HELP ********/
        $status = $this->checkHelp($options);

        if ($status !== ExitCode::EXIT_SUCCESS->value) {
            return $status;
        }

        /******* SET USER ARGS *******/
        $status = $this->setUserArgs($options);

        if ($status !== ExitCode::EXIT_SUCCESS->value) {
            return $status;
        }

        /**** CHECK IF FOLDER EXISTS AND ACCESS ****/
        $status = $this->checkFolder();

        if ($status !== ExitCode::EXIT_SUCCESS->value) {
            return $status;
        }

        //***** CHECK IF FILES EXIST AND ACCESS *****
        $status = $this->checkFiles();

        if ($status !== ExitCode::EXIT_SUCCESS->value) {
            return $status;
        }
    }
}

?>