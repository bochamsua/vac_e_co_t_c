<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Docwise Document admin edit tabs
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Docwisement_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('docwisement_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_docwise')->__('Docwise Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Docwisement_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_docwisement',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Docwise Document'),
                'title'   => Mage::helper('bs_docwise')->__('Docwise Document'),
                'content' => $this->getLayout()->createBlock(
                    'bs_docwise/adminhtml_docwisement_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'exams',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exams'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/docwise_exam_docwisement/exams',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve docwise document entity
     *
     * @access public
     * @return BS_Docwise_Model_Docwisement
     * @author Bui Phong
     */
    public function getDocwisement()
    {
        return Mage::registry('current_docwisement');
    }
}
