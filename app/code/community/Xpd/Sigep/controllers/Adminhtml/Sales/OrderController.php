<?php
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';
class Xpd_Sigep_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    public function getSigep()
    {
        return Mage::getSingleton('sigep/sigep');
    }
    
    public function exporttosigepAction()
    {
        $orderIds = $this->getRequest()->getPost('order_ids');
        
		$_baseDir = Mage::getBaseDir('tmp');
        $_file = $_baseDir . 'layout.sigepweb.txt';
        
        $sigep = $this->getSigep();
        $file = fopen($_file,"a");
        
        if($file)
        {   
            fwrite($file,"Número;CNPJ/CPF;Nome;EMAIL;Cep;Logradouro;Número;Complemento;Bairro;Cidade;Telefone;Celular\r\n");
            $shippings = $sigep->getShipping2Csv($_file,$orderIds);
            
            foreach ($shippings as $_incrementId => $_fields)
            {
                $write = fwrite($file,"2;".
                    $_fields['cpf'].";".
                    $_fields['nome'].";".
                    $_fields['email'].";".
                    $_fields['cep'].";".
                    $_fields['logradouro'].";".
                    $_fields['numero'].";".
                    $_fields['complemento'].";".
                    $_fields['bairro'].";".
                    $_fields['cidade'].";".
                    $_fields['telefone'].";".
                    $_fields['celular']."\r\n"
                );
                
                if($write)
                {
                    Mage::log('CSV ATUALIZADO');
                }
                else
                {
                    Mage::log('CSV NÃO FOI ATUALIZADO');
                }
            }
        }
        else
        {
            Mage::log('NO OPEN');
        }
        fclose($file);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($_file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($_file));
        
        ob_clean();
        flush();
        
        readfile($_file);
        
        unlink($_file);
        
        $this->_redirect('*/*/');	
    }
}