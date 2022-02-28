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

class DirectoryStructure {
    public $dirs;
    public $files;

    public function __construct() {
        $this->dirs = array();
        $this->files = array();
    }

    private function addTestDir($dir) {
        if(!in_array($dir, $this->dirs)) {
            array_push($this->dirs, $dir);
        }
    }

    private function checkFiles($dir, $testName) {
        $testPath = $dir.$testName;
        $fileName = $testPath.'rc';

        if (!file_exists($fileName)) {
            file_put_contents($fileName, "0");
        }

        $fileName = $testPath.'in';
        if (!file_exists($fileName)) {
            file_put_contents($fileName, "");
        }

        $fileName = $testPath.'out';
        if (!file_exists($fileName)) {
            file_put_contents($fileName, "");
        }
    }

    public function initDirectoryStructure($dir, $recFlag) {
        $dirIter = new RecursiveDirectoryIterator($dir);
        $iter = ($recFlag) ? new RecursiveDirectoryIterator($dirIter) : new IteratorIterator($dirIter);
        $regex = new RegexIterator($iter, '/^.+\.src$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($regex as $test) {
            $testName = preg_replace('/^(.*\/)?(.+)\.src$/','\2', $test[0]);
            $dir = preg_replace('/^(.*\/).+\.(in|out|rc|src)$/','\1', $test[0]);

            $this->addTestDir($dir);
            $this->checkFiles($dir, $testName);
        }
    }
}

?>