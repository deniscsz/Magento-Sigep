<?php
class Xpd_Sigep_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function convertOrderId($OrderId)
    {
        $orderStr = (string)$OrderId;
        $tam = strlen($orderStr);
        
        for($i=0;$i<8-$tam;$i++) {
            $orderStr = '0'.$orderStr;
        }
        $orderStr = '1'.$orderStr;
        
        return $orderStr;
    }
}
	 