<?php


require_once "Model/Pagseguro.php";
require_once "Library/CurlStatic.php";
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PagSeguroNotification
 *
 * @author bruno.blauzius
 */
class PagSeguroNotification {
    //put your code here
    
    private static $sendBoxUser               = "bruno_newstudio@hotmail.com";
    private static $sendBoxKey                = "57EFB4CCB6384D74B3ED0B23029755A7"; 
    
    private static $notificationCode = null;
    private static $notificationType = null;
    
    public static function getNotificationType() {
        return self::$notificationType;
    }

    public static function setNotificationType($notificationType) {
        self::$notificationType = $notificationType;
        return self;
    }

        
    public static function getNotificationCode() {
        return self::$notificationCode;
    }

    public static function setNotificationCode($notificationCode) {
        self::$notificationCode = $notificationCode;
        return self;
    }

        
    public static function main(){
        
        if( !empty(self::$notificationCode) && self::$notificationType == 'transaction'){
            $retorno = CurlStatic::send(
                            array('email' => self::$sendBoxUser, 'token' => self::$sendBoxKey ), 
                            'JSON', 
                            'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/'.self::$notificationCode,
                            'GET'
                        );
            
            $notificacao = simplexml_load_string($retorno);
            
            if( is_array($notificacao) ) {
                
                $pagseguro = new Pagseguro();
                $dadosTransaction = array(
                    'pagseguro_status_transacao_id'             => $notificacao['transaction']['status'],
                    'pagseguro_tipo_meio_pagamento_id'          => $notificacao['transaction']['paymentmethod']['type'],
                    'pagseguro_tipo_transacao_id'               => $notificacao['transaction']['type'],
                    'pagseguro_identificador_meio_pagamento_id' => $notificacao['transaction']['paymentmethod']['code'],
                    'modified'                                  => $notificacao['transaction']['date'],
                    'codigo'                                    => $notificacao['transaction']['code'],
                    'reference'                                 => $notificacao['transaction']['reference'],
                );
                
                try {
                        //echo '<pre>';
                        //print_r( $dadosTransaction );
                        return $pagseguro->updateNotificacaoPagseguro($dadosTransaction);
        
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                
            }
        }
        
    }

}

PagSeguroNotification::setNotificationCode( $_POST['notificationCode'] );
PagSeguroNotification::setNotificationType( $_POST['notificationType'] );
PagSeguroNotification::main();