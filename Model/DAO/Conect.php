<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conect
 *
 * @author brunoblauzius
 */
class Conect {
    
    const SERVER        = 'mysql';
    const HOST          = '192.185.223.74';
    const DATA_BASE     = 'agentus_database';
    const USER          = 'agentus_user';
    const PASS          = 'youshallnotpass852';
    
    private static $conn = null;

    public function __construct(){}

    /**
     * @version 0.1
     * @todo metodo de conexão compadrão de projeto singleton
     * @return PDO connect
     */
    public static function conecta(){
        try{
            if(is_null(self::$conn)){
                self::$conn = new PDO(self::SERVER . ':host=' . self::HOST . ';dbname='. self::DATA_BASE, self::USER, self::PASS);
            }
            return self::$conn;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
 
    public static function destroy(){
        self::$conn = NULL;
    }
    
}

//Conect::conecta();
