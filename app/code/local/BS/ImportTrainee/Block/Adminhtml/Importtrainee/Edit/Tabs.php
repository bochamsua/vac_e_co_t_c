<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee admin edit tabs
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
class BS_ImportTrainee_Block_Adminhtml_Importtrainee_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('importtrainee_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_importtrainee')->__('Import Trainee'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_ImportTrainee_Block_Adminhtml_Importtrainee_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_importtrainee',
            array(
                'label'   => Mage::helper('bs_importtrainee')->__('Import Trainee'),
                'title'   => Mage::helper('bs_importtrainee')->__('Import Trainee'),
                'content' => $this->getLayout()->createBlock(
                    'bs_importtrainee/adminhtml_importtrainee_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve import trainee entity
     *
     * @access public
     * @return BS_ImportTrainee_Model_Importtrainee
     * @author Bui Phong
     */
    public function getImporttrainee()
    {
        return Mage::registry('current_importtrainee');
    }
}
