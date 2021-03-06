<?php
/*
 ************************************************************************
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
 ************************************************************************
 */

require_once "../PagSeguroLibrary/PagSeguroLibrary.php";

class SearchTransactionByCode
{

    public static function main()
    {

        $transaction_code = '85EE5FFD-F42B-4692-8BCC-C267C0E300FE';

        try {

            /*
             * #### Credentials #####
             * Replace the parameters below with your credentials (e-mail and token)
             * You can also get your credentials from a config file. See an example:
             * $credentials = PagSeguroConfig::getAccountCredentials();
             */
            $credentials = new PagSeguroAccountCredentials(
                "bruno_newstudio@hotmail.com",
                "57EFB4CCB6384D74B3ED0B23029755A7");
            
            $transaction = PagSeguroTransactionSearchService::searchByCode($credentials, $transaction_code);

            self::printTransaction($transaction);

        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }

    }

    public static function printTransaction(PagSeguroTransaction $transaction)
    {
        
        echo "<h2>Transaction search by code result";
        echo "<h3>Code: " . $transaction->getCode() . '</h3>';
        echo "<h3>Status: " . $transaction->getStatus()->getTypeFromValue() . '</h3>';
        echo "<h4>Reference: " . $transaction->getReference() . "</h4>";

        if ($transaction->getSender()) {
            echo "<h4>Sender data:</h4>";
            echo "Name: " . $transaction->getSender()->getName() . '<br>';
            echo "Email: " . $transaction->getSender()->getEmail() . '<br>';
            if ($transaction->getSender()->getPhone()) {
                echo "Phone: " . $transaction->getSender()->getPhone()->getAreaCode() . " - " .
                    $transaction->getSender()->getPhone()->getNumber();
            }
        }

        if ($transaction->getItems()) {
            echo "<h4>Items:</h4>";
            if (is_array($transaction->getItems())) {
                foreach ($transaction->getItems() as $key => $item) {
                    echo "Id: " . $item->getId() . '<br>'; // prints the item id, e.g. I39
                    echo "Description: " . $item->getDescription() .
                        '<br>'; // prints the item description, e.g. Notebook prata
                    echo "Quantidade: " . $item->getQuantity() . '<br>'; // prints the item quantity, e.g. 1
                    echo "Amount: " . $item->getAmount() . '<br>'; // prints the item unit value, e.g. 3050.68
                    echo "<hr>";
                }
            }
        }

        if ($transaction->getShipping()) {
            echo "<h4>Shipping information:</h4>";
            if ($transaction->getShipping()->getAddress()) {
                echo "Postal code: " . $transaction->getShipping()->getAddress()->getPostalCode() . '<br>';
                echo "Street: " . $transaction->getShipping()->getAddress()->getStreet() . '<br>';
                echo "Number: " . $transaction->getShipping()->getAddress()->getNumber() . '<br>';
                echo "Complement: " . $transaction->getShipping()->getAddress()->getComplement() . '<br>';
                echo "District: " . $transaction->getShipping()->getAddress()->getDistrict() . '<br>';
                echo "City: " . $transaction->getShipping()->getAddress()->getCity() . '<br>';
                echo "State: " . $transaction->getShipping()->getAddress()->getState() . '<br>';
                echo "Country: " . $transaction->getShipping()->getAddress()->getCountry() . '<br>';
            }
            echo "Shipping type: " . $transaction->getShipping()->getType()->getTypeFromValue() . '<br>';
            echo "Shipping cost: " . $transaction->getShipping()->getCost() . '<br>';
        }

    }
}

SearchTransactionByCode::main();
