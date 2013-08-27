<?php
class Xpd_Sigep_Model_Mysql4_Sigep extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("sigep/sigep", "id");
    }
}