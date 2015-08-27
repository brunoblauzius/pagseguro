<?php

/*
 * ***********************************************************************
  Copyright [2011] [PagSeguro Internet Ltda.]

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 * ***********************************************************************
 */

require_once "PagSeguroLibrary/PagSeguroLibrary.php";
require_once "Model/Pagseguro.php";

class SearchTransactionByCode {

    private static $sendBoxUser = "bruno_newstudio@hotmail.com";
    private static $sendBoxKey = "57EFB4CCB6384D74B3ED0B23029755A7";
    private static $transactionCode = null;

    public static function getSendBoxUser() {
        return self::$sendBoxUser;
    }

    public static function getSendBoxKey() {
        return self::$sendBoxKey;
    }

    public static function getTransactionCode() {
        return self::$transactionCode;
    }

    public static function setSendBoxUser($sendBoxUser) {
        self::$sendBoxUser = $sendBoxUser;
        return self;
    }

    public static function setSendBoxKey($sendBoxKey) {
        self::$sendBoxKey = $sendBoxKey;
        return self;
    }

    public static function setTransactionCode($transactionCode) {
        self::$transactionCode = $transactionCode;
        return self;
    }

    
    
    public static function main() {

        try {
            /*
             * #### Credentials #####
             * Replace the parameters below with your credentials (e-mail and token)
             * You can also get your credentials from a config file. See an example:
             * $credentials = PagSeguroConfig::getAccountCredentials();
             */
            $credentials = new PagSeguroAccountCredentials(self::$sendBoxUser, self::$sendBoxKey);

            $transaction = PagSeguroTransactionSearchService::searchByCode($credentials, self::$transactionCode );

            self::printTransaction($transaction);
            
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }

    public static function printTransaction(PagSeguroTransaction $transaction) {
        
        $pagseguro = new Pagseguro();
        $dadosTransaction = array(
            'pagseguro_status_transacao_id'             => $transaction->getStatus()->getValue(),
            'pagseguro_tipo_meio_pagamento_id'          => $transaction->getPaymentMethod()->getType()->getValue(),
            'pagseguro_tipo_transacao_id'               => $transaction->getType()->getValue(),
            'pagseguro_identificador_meio_pagamento_id' => $transaction->getPaymentMethod()->getCode()->getValue(),
            'created'                                   => $transaction->getDate(),
            'modified'                                  => $transaction->getLastEventDate(),
            'codigo'                                    => $transaction->getCode(),
            'reference'                                 => $transaction->getReference(),
        );
        
        try {
                //echo '<pre>';
                //print_r( $dadosTransaction );
                return $pagseguro->updateNotificacao($dadosTransaction);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}

SearchTransactionByCode::setTransactionCode('611D1899-DB84-4143-8507-A2783C60C778');
SearchTransactionByCode::main();
