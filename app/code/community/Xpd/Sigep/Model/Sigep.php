<?php

class Xpd_Sigep_Model_Sigep extends Mage_Core_Model_Abstract
{
    protected function _construct()
	{
		$this->_init("sigep/sigep");
	}
	
	public function buildData()
	{
		$erro = 0;
		
		if($customer) {
            $fields['nome'] = $customer->getName() ? $customer->getName() : $customer->getFirstname() . ' ' . $customer->getLastname();
            
            if($customer->getCpfcnpj() || $billingAddress->getCpfcnpj()) {
                $cpf0 = $customer->getCpfcnpj() ? $customer->getCpfcnpj() : $billingAddress->getCpfcnpj();
                $fields['cpf'] = str_replace('-','',str_replace('.','',$cpf0));
            } elseif($customer->getTaxvat()) {
                $fields['cpf'] = str_replace('-','',str_replace('.','',$customer->getTaxvat()));
            } elseif($customer->getCpf()) {
                $fields['cpf'] = str_replace('-','',str_replace('.','',$customer->getCpf()));
            }
            
            $fields['email'] = $customer->getEmail();
        }
        else {
            $fields['nome'] = $billingAddress->getFirstname() . ' ' . $billingAddress->getLastname();
            
            if($billingAddress->getCpfcnpj()) {
                $fields['cpf'] = str_replace('-','',str_replace('.','',$billingAddress->getCpfcnpj()));
            } elseif($order->getCustomerTaxvat()) {
                $fields['cpf'] = str_replace('-','',str_replace('.','',$order->getCustomerTaxvat()));
            } elseif($order->getCustomerCpf()) {
                $fields['cpf'] = str_replace('-','',str_replace('.','',$order->getCustomerCpf()));
            }
        
            $fields['email'] = $order->getCustomerEmail();
        }
		
		$telefone = $billingAddress->getData('telephone');
        $telefone = $this->removeCharInvalidos($telefone); 
        if(substr($telefone,0,1) == '0') {
            $telefone = substr($telefone,1);
        }
        
        $celular = $billingAddress->getData('celular') ? $billingAddress->getData('celular') : $billingAddress->getData('fax');
        $celular = $this->removeCharInvalidos($celular); 
        if(substr($celular,0,1) == '0') {
            $celular = substr($celular,1);
        }
		
		$fields['telefone'] = $telefone;
        $fields['celular'] = $celular;
		
		$shippingAddress = $order->getShippingAddress();
        if($shippingAddress) {
            if($shippingAddress->getStreet(1) && $shippingAddress->getStreet(2) && $shippingAddress->getStreet(3) && $shippingAddress->getStreet(4)) {
                $fields['logradouro'] = $shippingAddress->getStreet(1);
                $fields['numero'] = $shippingAddress->getStreet(2);
                $fields['complemento'] = $shippingAddress->getStreet(3);
                $fields['bairro'] = $shippingAddress->getStreet(4);
            }
            else {
                if($shippingAddress->getStreet(1) && $shippingAddress->getStreet(2) && $shippingAddress->getStreet(3) && !$shippingAddress->getStreet(4)) {
                    $fields['logradouro'] = $shippingAddress->getStreet(1);
                    $fields['numero'] = $shippingAddress->getStreet(2);
                    $fields['complemento'] = $shippingAddress->getStreet(2);
                    $fields['bairro'] = $shippingAddress->getStreet(3);
                }
                else {
                    $fields['logradouro'] = $shippingAddress->getStreet(1);
                    $fields['numero'] = $shippingAddress->getStreet(2);
                    $fields['complemento'] = $shippingAddress->getStreet(2);
                    $fields['bairro'] = $shippingAddress->getStreet(2);
                }
            }
            $fields['cep'] = str_replace('.','',removeCharInvalidos($shippingAddress->getData('postcode')));
            $fields['cidade'] = $shippingAddress->getData('city');
            $fields['estado'] = $shippingAddress->getRegionCode() ? $shippingAddress->getRegionCode() : $billingAddress->getRegionCode();
            $fields['pais'] = $shippingAddress->getCountry() ? Mage::helper('paybrasboleto')->convertCodeCountry($shippingAddress->getCountry()) : "BRA";
        }
        else {
            Mage::log('Sigep - Erro ao recuperar informações do endereço de entrega');
			$erro = 1;
        }
	}
	
	public function removeCharInvalidos($str) {
        $invalid = array(' '=>'', '-'=>'', '{'=>'', '}'=>'', '('=>'', ')'=>'', '_'=>'', '['=>'', ']'=>'', '+'=>'', '*'=>'', '#'=>'', '/'=>'', '|'=>'', "`" => '', "´" => '', "„" => '', "`" => '', "´" => '', "“" => '', "”" => '', "´" => '', "~" => '', "’" => '', "." => '', 'a' => '', 'a' => '' , 'b' => '' , 'c' => '' , 'd' => '' , 'e' => '' , 'f' => '' , 'g' => '' , 'h' => '' , 'i' => '' , 'j' => '' , 'l' => '' , 'k' => '' , 'm' => '' , 'n' => '' , 'o' => '' , 'p' => '' , 'q' => '' , 'r' => '' , 's' => '' , 't' => '' , 'u' => '' , 'v' => '' , 'x' => '' , 'z' => '' , 'y' => '' , 'w' => '' , 'A' => '' , 'B' => '' , 'C' => '' , 'D' => '' , 'E' => '' , 'F' => '' , 'G' => '' , 'H' => '' , 'I' => '' , 'J' => '' , 'L' => '' , 'K' => '' , 'M' => '' , 'N' => '' , 'O' => '' , 'P' => '' , 'Q' => '' , 'R' => '' , 'S' => '' , 'T' => '' , 'U' => '' , 'V' => '' , 'X' => '' , 'Z' => '' , 'Y' => '' , 'W' => '');
        
        $str = str_replace(array_keys($invalid), array_values($invalid), $str);
        
        return $str;
    }

}