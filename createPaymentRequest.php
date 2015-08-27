<?php //

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
require_once "Model/Pagseguro.php";
require_once "PagSeguroLibrary/PagSeguroLibrary.php";

/**
 * Class with a main method to illustrate the usage of the domain class PagSeguroPaymentRequest
 */
class CreatePaymentRequest
{
    
    
    private static $sendBoxUser               = "bruno_newstudio@hotmail.com";
    private static $sendBoxKey                = "57EFB4CCB6384D74B3ED0B23029755A7";    
    private static $notificationURL           = "http://www.agentus.com.br/pagseguro/PagSeguroNotification.php";
    private static $urlRedirect               = "http://www.agentus.com.br/pagseguro/PagSeguroInformation.php";
    
    private static $sendBoxUrlRedirect        = "http://192.168.1.204/rewrite/pagseguro/PagSeguroInformation.php";
    private static $sendBoxNotificationURL    = "http://192.168.1.204/rewrite/pagseguro/notification.php";


    private static $reference = null;
    
    public static function getReference() {
        return self::$reference;
    }

    public static function setReference($reference) {
        self::$reference = $reference;
        return self;
    }
    
    public static function getSendBoxUser() {
        return self::$sendBoxUser;
    }

    public static function getSendBoxKey() {
        return self::$sendBoxKey;
    }

    public static function setSendBoxUser($sendBoxUser) {
        self::$sendBoxUser = $sendBoxUser;
        return self;
    }

    public static function setSendBoxKey($sendBoxKey) {
        self::$sendBoxKey = $sendBoxKey;
        return self;
    }


    public static function main()
    {
        // Instantiate a new payment request
        $paymentRequest = new PagSeguroPaymentRequest();
        $pagseguro      = new Pagseguro();
        // Set the currency
        $paymentRequest->setCurrency("BRL");

        // Add an item for this payment request
        $paymentRequest->addItem( 2, 'Lincença SMART - Sistema de gerenciamento AGENTUS', 1, 30.00 );
        $paymentRequest->addItem( 6, 'Módulo Contrato', 1, 10.00 );
        $paymentRequest->addItem( 5, 'Funcionários adicionais', 2, 10.00 );

        // Add another item for this payment request
        //$paymentRequest->addItem('0002', 'Notebook rosa', 2, 560.00);

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $paymentRequest->setReference( self::$reference );

        // Set shipping information for this payment request
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $paymentRequest->setShippingType($sedexCode);
 
        
        /**
         * REGISTRO DE ENDEREÇO DO CLIENTE
         */
            $paymentRequest->setShippingAddress(
                '01452002',
                'Av. Brig. Faria Lima',
                '1384',
                'apto. 114',
                'Jardim Paulistano',
                'São Paulo',
                'SP',
                'BRA'
            );

        /**
         * DADOS DO CLIENTE
         */
        // Set your customer information.
        $paymentRequest->setSender(
            'João Comprador',
            'comprador@s2it.com.br',
            '11',
            '56273440',
            'CPF',
            '156.009.442-76'
        );
        
        // Set the url used by PagSeguro to redirect user after checkout process ends
        $paymentRequest->setRedirectUrl( self::$urlRedirect );

        // Add checkout metadata information
        $paymentRequest->addMetadata('PASSENGER_CPF', '15600944276', 1);
        $paymentRequest->addMetadata('GAME_NAME', 'DOTA');
        $paymentRequest->addMetadata('PASSENGER_PASSPORT', '23456', 1);

        // Another way to set checkout parameters
        $paymentRequest->addParameter('notificationURL', self::$notificationURL );
        $paymentRequest->addParameter('senderBornDate', '07/05/1981');
//        $paymentRequest->addIndexedParameter('itemId', '0003', 3);
//        $paymentRequest->addIndexedParameter('itemDescription', 'Notebook Preto', 3);
//        $paymentRequest->addIndexedParameter('itemQuantity', '1', 3);
//        $paymentRequest->addIndexedParameter('itemAmount', '200.00', 3);

        try {

            /*
             * #### Credentials #####
             * Replace the parameters below with your credentials (e-mail and token)
             * You can also get your credentials from a config file. See an example:
             * $credentials = PagSeguroConfig::getAccountCredentials();
             */
            $pagseguro->inserirCreatePayment( self::$reference );
            $credentials = new PagSeguroAccountCredentials( self::$sendBoxUser, self::$sendBoxKey );

            // Register this payment request in PagSeguro to obtain the payment URL to redirect your customer.
            $url = $paymentRequest->register($credentials);

            self::printPaymentUrl($url);
            
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }

    public static function printPaymentUrl($url)
    {
        if ($url) {
            echo "<h2>Criando requisi&ccedil;&atilde;o de pagamento</h2>";
            echo "<p>URL do pagamento: <strong>$url</strong></p>";
            echo "<p><a title=\"URL do pagamento\" href=\"$url\">Ir para URL do pagamento.</a></p>";
        }
    }
}

CreatePaymentRequest::setReference( date('Ymdhis').'26' );
CreatePaymentRequest::main();
