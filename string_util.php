<?php
/************************************************************************/
/*                                                                      */
/* Soubor: string_util.php                                              */
/* Vytvoren: 2022-02-15                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript se tridou obsahujici metody pro praci s retezci        */ 
/*                                                                      */
/************************************************************************/

/*
 * Trida pro praci s retezci
 */ 
class StringUtil {
    /*
     * Metoda slouzi pro odstraneni jednoradkoveho
     * komentare nasledujiciho za samotnou instrukci
     * 
     * @params $inputLine Vstupni radek
     * @return            Vstupni radek bez komentare
     */
    private static function removeComment($inputLine) {
        return explode('#', $inputLine)[0];
    }

    /*
     * Metoda slouzi pro odstraneni bilych znaku na zacatku vstupniho radku
     * 
     * @param $inputLine Vstupni radek
     * @return           Vstupni radek bez bilych znaku na zacatku
     */
    private static function trimBegWhiteSpc($inputLine) {
        return ltrim($inputLine);
    }

    /*
     * Metoda slouzi pro odstraneni znaku noveho
     * radku nebo bilych znaku na konci vstupniho radku
     * 
     * @param $inputLine Vstupni radek obsahujici znak noveho radku
     * @return           Vstupni radek bez znaku noveho radku
     */
    private static function trimLineEnd($inputLine) {
        $noNewLine = rtrim($inputLine, "\n");
        $noWhiteSpaces = rtrim($inputLine);
        
        return $noWhiteSpaces;
    }

    /*
     * Metoda prevede vstupni retezec na pole obsahujici
     * jednotliva slova retezce (tokeny)
     * Pritom jsou ignorovany vsechny bile znaky
     * 
     * @param $str Vstupni retecez
     * @return     Vysledne pole obsahujici tokeny
     */ 
    private static function str2Arr($str) {
        return preg_split("/\s+/", $str);
    }

    /*
     * Metoda slouzi pro nacteni jedne instrukce ze vstupu
     * Pritom jsou ignorovany komentare, prazdne radky a bile znaky
     * 
     * @return Nactena instrukce
     */
    public static function readInstruction() {
        while(($inputLine = fgets(STDIN)) !== false){            
            if (str_starts_with($inputLine, '#') ||                
                preg_match("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", $inputLine)) {
                    
                continue;
            } else {
                $inputLine = self::removeComment($inputLine);
                $inputLine = self::trimLineEnd($inputLine);
                $inputLine = self::trimBegWhiteSpc($inputLine);

                return self::str2Arr($inputLine);
            }
        }

        /* EOF */             
        return array(TokenType::T_EOF->value);
    }
}

?>