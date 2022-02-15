<?php
/************************************************************************/
/*                                                                      */
/* Soubor: syntax.php                                                   */
/* Vytvoren: 2022-02-14                                                 */
/* Posledni zmena: 2022-02-14                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript obsahujici syntaktickou analyzu jazyka IPPcode22       */
/*                                                                      */
/************************************************************************/

function parser() {
    global $instruction_set;
    /*
    for ($i = 1; $i <= 10; $i++) {
        $inst = get_instruction();
        var_dump($inst);
    }
    */
    
    while(true) {
        $instruction = get_instruction();

        if($instruction[0] == INVALID) {
            break;
        } elseif($instruction[1][0] == token::T_EOF->value) {
            break;
        } elseif($instruction[1][0] == token::T_LANGUAGE_ID->value) {
            continue;
        }

        //var_dump($instruction);

        /* instruction[1][0] -> Prvni cast instrukce je operacni kod */        
        $inst_operands = $instruction_set[$instruction[1][0]];

        $inst_ops_len = count($inst_operands);

        // Nespravny pocet operandu
        if($inst_ops_len !== count($instruction[1])-1){
            //TODO error
        }

        var_dump($inst_operands);

        $inst_idx = 1;

        foreach($inst_operands as $operand) {
            switch($operand) {
                case 'v': /* var   */
                    if($instruction[1][$inst_idx] != token::T_VAR->value) {
                        // TODO error
                    }
                    break;
                case 's': /* symb  */
                    if($instruction[1][$inst_idx] != token::T_VAR->value &&
                       $instruction[1][$inst_idx] != token::T_CONST->value) {
                        // TODO error
                    }
                    break;
                case 'l': /* label */
                    if($instruction[1][$inst_idx] != token::T_LABEL->value) {
                        // TODO error
                    }
                    break;                    
                case 't': /* type  */
                    if($instruction[1][$inst_idx] != token::T_TYPE->value) {
                        // TODO error
                    }
                    break;
            }

            $inst_idx++;
        }
    }
}

?>