<?php

class Xpd_Sigep_Block_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid {

    public function __construct()
	{
        parent::__construct();
    }
	
	/*protected function _prepareLayout()
    {
        $this->setChild('analyze_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Analyse Database'),
                    'onclick'   => 'doAnalyze()',
                    'class'   => 'delete'
                ))
        );

        return parent::_prepareLayout();
    }*/
	
    protected function _prepareCollection()
	{
        $actions = array();
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view'))
		{
            $actions[] = array(
                'caption' => Mage::helper('sales')->__('View'),
                'url' => array('base' => '*/sales_order/view'),
                'field' => 'order_id'
            );
        }
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/delete'))
		{
            $actions[] = array(
                'caption' => Mage::helper('sales')->__('Delete'),
                'url' => array('base' => 'orderseraser/adminhtml_orderseraser/delete'),
                'confirm' => Mage::helper('sales')->__('Are your sure your want to delete this order and to erase all linked data ? '),
                'field' => 'order_id'
            );
        }


        $this->addColumn('action', array(
            'header' => Mage::helper('sales')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => $actions,
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() 
	{
        return parent::_prepareColumns();
    }
	
    protected function _prepareMassaction()
	{
        parent::_prepareMassaction();
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/delete'))
		{
            $this->getMassactionBlock()->addItem('delete_order', array(
                'label' => Mage::helper('sales')->__('Delete'),
                'url' => $this->getUrl('orderseraser/adminhtml_orderseraser/massdelete'),
            ));
        }
        return $this;
    }

}