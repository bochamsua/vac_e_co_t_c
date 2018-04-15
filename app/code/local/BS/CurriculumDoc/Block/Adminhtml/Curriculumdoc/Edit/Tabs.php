<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum Document admin edit tabs
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('curriculumdoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_curriculumdoc')->__('Curriculum Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_curriculumdoc',
            array(
                'label'   => Mage::helper('bs_curriculumdoc')->__('Document Info'),
                'title'   => Mage::helper('bs_curriculumdoc')->__('Document Info'),
                'content' => $this->getLayout()->createBlock(
                    'bs_curriculumdoc/adminhtml_curriculumdoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'curriculums',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Related Curriculums'),
                'url'   => $this->getUrl('*/*/curriculums', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve curriculum doc entity
     *
     * @access public
     * @return BS_CurriculumDoc_Model_Curriculumdoc
     * @author Bui Phong
     */
    public function getCurriculumdoc()
    {
        return Mage::registry('current_curriculumdoc');
    }
}
