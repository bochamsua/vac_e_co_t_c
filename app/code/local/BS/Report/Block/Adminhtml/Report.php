<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report admin block
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_report';
        $this->_blockGroup         = 'bs_report';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_report')->__('Individual Report. You can only make report from 12:00 PM today to 11:59 AM the next day! Each day you can ONLY report ONCE FOR ALL!');
        $this->_updateButton('add', 'label', Mage::helper('bs_report')->__('Add Report'));

        $this->_removeButton('add');

        /*$this->addButton('filter_form_submit', array(
            'label'     => Mage::helper('bs_report')->__('Generate Report'),
            'onclick'   => 'filterFormSubmit()'
        ));*/

        /*$isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_report/report/new");
        $hour = date("H", Mage::getModel('core/date')->timestamp(time()));


        if(!$isAllowed || $hour<15){
                $this->_removeButton('add');
        }*/

    }

    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/report', array('_current' => true));
    }
}
