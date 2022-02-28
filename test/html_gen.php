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

class HTMLGen {
    public function __construct() {

    }

    public function generateHTMLPage() {
        $html = "
        <!DOCTYPE html>
        <html lang=\"cz\">
        <head>
            <meta charset=\"utf-8\">
            <title>Test results</title>
            <meta name=\"Testovani skriptu parse.php a interpret.py\">
            <style>
            </style>
        </head>
        <body>

        </body>
        </html>
        ";

        echo $html;
    }
}

?>