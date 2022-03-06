<?php
/************************************************************************/
/*                                                                      */
/* Soubor: data_type.php                                                */
/* Vytvoren: 2022-02-15                                                 */
/* Posledni zmena: 2022-02-17                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Datove typy jazyka IPPcode22                                  */ 
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici datove typy
 */ 
final class DataType {
    /************ DATOVE TYPY **********/
    public static $dataType = array(
        'int'    => 0,
        'bool'   => 1,
        'string' => 2,
        'nil'    => 3,
    );
}

?>