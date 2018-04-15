<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Manage admin block
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Manage extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_manage';
        $this->_blockGroup         = 'bs_report';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_report')->__('Manage All Reports');

        $this->addButton('filter_form_submit', array(
            'label'     => Mage::helper('bs_report')->__('Generate Report'),
            'onclick'   => 'filterFormSubmit()'
        ));
        $this->_removeButton('add');

    }

    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/report', array('_current' => true));
    }
}
