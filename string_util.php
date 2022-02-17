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
     * @params $input_line Vstupni radek
     * @return             Vstupni radek bez komentare
     */
    private static function removeComment($input_line) {
        return explode('#', $input_line)[0];
    }

    /*
     * Metoda slouzi pro odstraneni znaku noveho
     * radku nebo bilych znaku na konci vstupniho radku
     * 
     * @param $input line Vstupni radek obsahujici znak noveho radku
     * @return            Vstupni radek bez znaku noveho radku
     */
    private static function trimLineEnd($input_line) {
        $no_new_line = rtrim($input_line, "\n");
        $no_white_spaces = rtrim($input_line);
        
        return $no_white_spaces;
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
        while(($input_line = fgets(STDIN)) !== false){
            if (str_starts_with($input_line, '#') ||
                str_starts_with($input_line, '\n')) {

                continue;
            } else {
                $input_line = self::removeComment($input_line);
                $input_line = self::trimLineEnd($input_line);

                return self::str2Arr($input_line);            
            }
        }

        /* EOF */             
        return array(TokenType::T_EOF->value);
    }
}

?>