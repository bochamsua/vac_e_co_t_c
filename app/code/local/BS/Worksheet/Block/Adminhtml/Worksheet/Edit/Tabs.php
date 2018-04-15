<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet admin edit tabs
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('worksheet_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_worksheet')->__('Worksheet'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_worksheet',
            array(
                'label'   => Mage::helper('bs_worksheet')->__('Worksheet Info'),
                'title'   => Mage::helper('bs_worksheet')->__('Worksheet Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_worksheet/adminhtml_worksheet_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'curriculums',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Related curriculums'),
                'url'   => $this->getUrl('*/*/curriculums', array('_current' => true)),
                'class' => 'ajax'
            )
        );

        /*$this->addTab(
            'worksheetdocs',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('Worksheet Documents'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/worksheetdoc_worksheetdoc_worksheet_worksheet/worksheetdocs',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );*/
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve worksheet entity
     *
     * @access public
     * @return BS_Worksheet_Model_Worksheet
     * @author Bui Phong
     */
    public function getWorksheet()
    {
        return Mage::registry('current_worksheet');
    }
}
