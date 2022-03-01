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

class ParseTester {
    private $dirStruct;
    private $parseScript;
    private $jexamxmlPath;

    public function __construct($dirStruct,
                                $parseScript,
                                $jexamxmlPath) {
        $this->dirStruct = $dirStruct;
        $this->parseScript = $parseScript;
        $this->jexamxmlPath = $jexamxmlPath;
    }

    /* TODO return */
    public function run($dir,
                        $file,                                                
                        $src,
                        $expectedOut,
                        $expectedRc) {
        $command = "php8.1 " . $this->parseScript . " < " . $src;
        exec($command, $parseOut, $parseRc);        

        if ($parseRc == $expectedRc) {
            $tmpFile = tmpfile();
            


            if ($parseRc == 0) {
                exec('java -jar ' . $this->jexamxmlPath . " " . $tmpFile . $out, $xmlOut, $xmlRc);

                $testDone = ($xmlOut == 0) ? true : false;                                

                fclose($tmpFile);
            }
        } else {
        
        }

        $this->dirStruct->saveTest($dir,
                                $file['testName'],
                                TestType::PARSE_ONLY->value,
                                $parseRc,
                                null,
                                $testDone);
    }
}

?>