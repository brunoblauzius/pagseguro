<?php
require_once 'DAO/DAO.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppModel
 *
 */
class AppModel extends DAO {
    
    
    
    public $validateErros = array();
    /**
     * @todo atributto para gera um tipo de validação na minha model
     * @var array usada para gerar o tipo de validação na model 
     */
    public $validate    = null;

    public $paramsValue = null;
    
    public $params      = null;
    
    public $data        = null;
    
    /**
     * @todo metodo de validação para os meus models 
     * @param array $params
     * @return boolean
     * @throws Exception
     */
    public function validates( ) {
        
        if( !empty($this->validate) ){
            
            foreach ( $this->validate as $key => $validacao ){
                foreach ($validacao as $chave => $valor) {
                    
                    $regra = $valor['rule'];
                    $mensagemErro = $valor['mensagem'];
                    
                    if( is_array($regra) ) {
                        if( isset($regra[1])) {
                            $this->paramsValue = $regra[1];
                        }
                        $regra = $regra[0];
                    }
                    try{
                        if( method_exists($this, $regra) ) { 
                            if( isset($this->data[$key]) ) {
                                if( call_user_func_array(array($this,$regra), array($this->data[$key])) ) {
                                    throw new Exception( $mensagemErro );
                                }
                            } 
                        } 
                    } catch (Exception $ex) {
                        $this->validateErros[$key] =  $ex->getMessage();
                        break;
                    }
                }
            }
            /// se conter validações com erro ele retorna false e exibe o erro na controller
            if( !empty($this->validateErros )) {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    /**
     * @todo metodo que valida o email 
     * @param string $email
     * @return boolean
     */
    public function email( $email = NULL ) {
        return !filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * @todo metodo que verifica se o meu valor está vazio
     * @param string $valor
     * @return boolean
     */
    public function notEmpty( $valor = null ){
        if( $valor == NULL ){
            return true;
        }
        return false;
    }
    
    public function numerico( $valor = NULL){
        if( !is_numeric($valor) ){
            return true;
        }
        return false;
    }
    
    public function equalsPassword( $senha = null ){
        $confirma_senha = $this->data['confirm_senha'];
        if( $confirma_senha !== $senha ) {
            return true;
        }
        return false;
    }
    
    
    public function minLenght( $valor = null ){
        if( strlen($valor) < $this->paramsValue ){
            return TRUE;
        }
        return FALSE;
    }
    
    public function maxLenght( $valor = null ){
        if( strlen($valor) > $this->paramsValue ){
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 
     * @todo validação da data ela não pode conter dados que não seja padrão a quantidade de dias no mes e nem quantidade de meses do ano
     * @version 2.0
     * @param type $data
     * @return type
     */
    public function checkDate( $data ){
        $explodeDate = explode('/', ($data));
        return !checkdate($explodeDate[1], $explodeDate[0], $explodeDate[2]);
    }
    
    /**
     * 
     * @todo validador de captcha na model
     * @version 2.0
     * @param type $valor
     * @return type
     * 
     */
    public function validaCaptcha( $valor ){
        $captcha = new CaptchasController();
        return ( $captcha->getValue() != $valor );
    }
    
    
    
    /**
     * 
     * @todo metodo que verifica se o email ja foi cadastrado no sistema
     * @version 2.0
     * @param type $valor
     * @return type
     * 
     */
    public function verificaEmail( $email ){
        $this->useTable = 'pessoas';
        $verificaMail = $this->find('all', array('email' => $email ));
        $verificaMail = array_shift($verificaMail);
        return ( count($verificaMail) > 0 );
    }
    
    
    /**
     * 
     * @todo metodo que verifica o cpf ou cnpj cadastrado no sistema
     * @version 2.0
     * @param type $valor
     * @return type
     * 
     */
    public function verificaCpfCnpj( $cpfCnpj ){
        if( $this->data['tipo_pessoa'] == 1 ){
            $this->useTable = 'pessoaFisica';
        } else {
            $this->useTable = 'pessoaJuridica';
        }
        $verifica = $this->find('all', array('cpf_cnpj' => $cpfCnpj ));
        $verifica = array_shift($verifica);
        return ( count($verifica) > 0 );
    }
    
   
   /**
     * 
     * @todo metodo que verifica o cpf é valido
     * @version 2.0
     * @param string $cpfCnpj
     * @return boolean
     * 
     */
   public function isValidCPF($cpfCnpj){
       return Utils::validaCpf($cpfCnpj);
   }
   
    /**
     * 
     * @todo metodo que verifica o cnpj é valido
     * @version 2.0
     * @param string $cpfCnpj
     * @return boolean
     * 
     */
   public function isValidCNPJ($cpfCnpj){
       return Utils::validaCnpj($cpfCnpj);
   }
    
}
