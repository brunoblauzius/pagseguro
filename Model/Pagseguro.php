<?php


require_once 'AppModel.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagseguro
 *
 * @author bruno.blauzius
 */
class Pagseguro extends AppModel {
    //put your code here
    
    
    public $useTable   = 'pagseguro_pagamentos';

    public $name       = 'Pagseguro';

    public $primaryKey = 'id';
    
    /**
     * @metodo que gera o updade da notificação vinda do pagseguro
     * @author bruno blauzius
     * @version 1.0
     * 
     * @param array $dados
     * @return boolean
     * @throws Exception
     */
    public function updateNotificacao( array $dados ){
        try {
            
            $sql = "UPDATE agentus_database.pagseguro_pagamentos SET
                        pagseguro_status_transacao_id             = {$dados['pagseguro_status_transacao_id']},
                        pagseguro_tipo_meio_pagamento_id          = {$dados['pagseguro_tipo_meio_pagamento_id']},
                        pagseguro_tipo_transacao_id               = {$dados['pagseguro_tipo_transacao_id']},
                        pagseguro_identificador_meio_pagamento_id = {$dados['pagseguro_identificador_meio_pagamento_id']},
                        created   = '{$dados['created']}',
                        modified  = '{$dados['modified']}',
                        reference = '{$dados['reference']}'
                    WHERE codigo  = '{$dados['codigo']}'; ";
            
            return $this->query($sql);

        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    /**
     * @metodo que gera o updade da notificação vinda do pagseguro
     * @author bruno blauzius
     * @version 1.0
     * 
     * @param array $dados
     * @return boolean
     * @throws Exception
     */
    public function updateNotificacaoPagseguro( array $dados ){
        try {
            
            $sql = "UPDATE agentus_database.pagseguro_pagamentos SET
                        pagseguro_status_transacao_id             = {$dados['pagseguro_status_transacao_id']},
                        pagseguro_tipo_meio_pagamento_id          = {$dados['pagseguro_tipo_meio_pagamento_id']},
                        pagseguro_tipo_transacao_id               = {$dados['pagseguro_tipo_transacao_id']},
                        pagseguro_identificador_meio_pagamento_id = {$dados['pagseguro_identificador_meio_pagamento_id']},
                        modified  = '{$dados['modified']}',
                        codigo  = '{$dados['codigo']}'
                    WHERE reference = '{$dados['reference']}'; ";
            
            return $this->query($sql);

        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    
    public function inserirTransaction( $code ){
        try {
            
            $sql = "INSERT INTO `agentus_database`.`pagseguro_pagamentos`
                                ( `pagseguro_status_transacao_id`, `created`, `codigo` ) VALUES (1, NOW(), '$code');";
            
            return $this->query($sql);
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    public function inserirCreatePayment( $reference ){
        try {
            
            $sql = "INSERT INTO `agentus_database`.`pagseguro_pagamentos`
                                ( `pagseguro_status_transacao_id`, `created`, `reference` ) VALUES (1, NOW(), '$reference');";
            
            return $this->query($sql);
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
}
