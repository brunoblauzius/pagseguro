<?php

require_once "Model/Pagseguro.php";

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PagSegurosController
 *
 * @author bruno.blauzius
 */
class PagSeguroInformation {
    //put your code here
    
    public function information(){
        $code = $_GET['pagseguro_id'];
        $id   = null;
        
        $pagseguro = new Pagseguro();
        
        if( $code ){
            $id = $pagseguro->inserirTransaction($code);
            if( $id ){
                header('Location: ');
            }
        }
    }

}

$p = new PagSeguroInformation();
$p->information();



