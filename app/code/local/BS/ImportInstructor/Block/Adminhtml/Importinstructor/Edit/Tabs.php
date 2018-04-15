<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Instructor admin edit tabs
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Block_Adminhtml_Importinstructor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('importinstructor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_importinstructor')->__('Import Instructor'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_ImportInstructor_Block_Adminhtml_Importinstructor_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_importinstructor',
            array(
                'label'   => Mage::helper('bs_importinstructor')->__('Import Instructor'),
                'title'   => Mage::helper('bs_importinstructor')->__('Import Instructor'),
                'content' => $this->getLayout()->createBlock(
                    'bs_importinstructor/adminhtml_importinstructor_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve import instructor entity
     *
     * @access public
     * @return BS_ImportInstructor_Model_Importinstructor
     * @author Bui Phong
     */
    public function getImportinstructor()
    {
        return Mage::registry('current_importinstructor');
    }
}
