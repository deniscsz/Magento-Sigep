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
            fwrite($file,"1SIGEP DESTINATARIO NACIONAL");
            //fwrite($file,"Número;CNPJ/CPF;Nome;EMAIL;Cep;Logradouro;Número;Complemento;Bairro;Cidade;Telefone;Celular\r\n");
            $shippings = $sigep->getShipping2Csv($_file,$orderIds);
            
            foreach ($shippings as $_incrementId => $_fields)
            {
                $write = fwrite($file,"2".
                    str_pad($_fields['cpf'], 14, " ", STR_PAD_RIGHT).
                    str_pad($_fields['nome'], 50, " ", STR_PAD_RIGHT).
                    str_pad($_fields['email'], 50, " ", STR_PAD_RIGHT).
                    str_pad($_fields['cep'], 8, " ", STR_PAD_RIGHT).
                    str_pad($_fields['logradouro'], 50, " ", STR_PAD_RIGHT).
                    str_pad($_fields['numero'], 6, " ", STR_PAD_RIGHT).
                    str_pad($_fields['complemento'], 30, " ", STR_PAD_RIGHT).
                    str_pad($_fields['bairro'], 50, " ", STR_PAD_RIGHT).
                    str_pad($_fields['cidade'], 50, " ", STR_PAD_RIGHT).
                    str_pad($_fields['telefone'], 18, " ", STR_PAD_RIGHT).
                    str_pad($_fields['celular'], 12, " ", STR_PAD_RIGHT)
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
            
            fwrite($file,"9".str_pad(count($shippings), 6, "0", STR_PAD_LEFT));
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