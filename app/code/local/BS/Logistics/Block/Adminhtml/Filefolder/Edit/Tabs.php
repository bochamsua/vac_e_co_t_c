<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder admin edit tabs
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('filefolder_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_logistics')->__('File Folder'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_filefolder',
            array(
                'label'   => Mage::helper('bs_logistics')->__('File Folder'),
                'title'   => Mage::helper('bs_logistics')->__('File Folder'),
                'content' => $this->getLayout()->createBlock(
                    'bs_logistics/adminhtml_filefolder_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_logistics')->__('Contained Courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'exams',
            array(
                'label' => Mage::helper('bs_logistics')->__('Contained Docwise Exams'),
                'url'   => $this->getUrl('*/*/exams', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'foldercontent',
            array(
                'label' => Mage::helper('bs_logistics')->__('Folder Content'),
                'url'   => $this->getUrl('*/*/foldercontents', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve file folder entity
     *
     * @access public
     * @return BS_Logistics_Model_Filefolder
     * @author Bui Phong
     */
    public function getFilefolder()
    {
        return Mage::registry('current_filefolder');
    }
}
