<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document admin edit tabs
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Block_Adminhtml_Coursedoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('coursedoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_coursedoc')->__('Course Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_CourseDoc_Block_Adminhtml_Coursedoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_coursedoc',
            array(
                'label'   => Mage::helper('bs_coursedoc')->__('Course Document'),
                'title'   => Mage::helper('bs_coursedoc')->__('Course Document'),
                'content' => $this->getLayout()->createBlock(
                    'bs_coursedoc/adminhtml_coursedoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Related Courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve course doc entity
     *
     * @access public
     * @return BS_CourseDoc_Model_Coursedoc
     * @author Bui Phong
     */
    public function getCoursedoc()
    {
        return Mage::registry('current_coursedoc');
    }
}
