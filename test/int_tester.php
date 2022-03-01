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

class IntTester {
    private $dirStruct;
    private $intScript;    

    public function __construct($dirStruct, $intScript) {
        $this->dirStruct = $dirStruct;
        $this->intScript = $intScript;        
    }

    public function run($dir,
                        $file,
                        $src,
                        $in,
                        $out,
                        $expectedRc) {
        $command = "python 3.8 " . $this->intScript . " --source=" . $src . " --input=" . $in;
        exec($command, $intOut, $intRc);
        
        if ($intRc == $expectedRc) {
            if ($intRc == 0) {
                $command = "python 3.8 " . $this->intScript . " --source=" . $src . " --input=" . $in . " 2>/dev/null | diff " . $out . " -";
                exec($command, $diffOut, $diffRc);
        
                $testDone = ($diffRc == 0) ? true : false;                
            } else {
                $testDone = true;
            }
        } else {
            $testDone = false;
        }

        $this->dirStruct->saveTest($dir,
                                   $file['testName'],
                                   TestType::INT_ONLY->value,
                                   null,
                                   $intRc,
                                   $testDone);

        
    }
}

?>