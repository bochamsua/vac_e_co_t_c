<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document admin edit tabs
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('traineedoc_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_traineedoc')->__('Trainee Document'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_traineedoc',
            array(
                'label'   => Mage::helper('bs_traineedoc')->__('Trainee Document'),
                'title'   => Mage::helper('bs_traineedoc')->__('Trainee Document'),
                'content' => $this->getLayout()->createBlock(
                    'bs_traineedoc/adminhtml_traineedoc_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'trainees',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Associated trainees'),
                'url'   => $this->getUrl('*/*/trainees', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve trainee document entity
     *
     * @access public
     * @return BS_TraineeDoc_Model_Traineedoc
     * @author Bui Phong
     */
    public function getTraineedoc()
    {
        return Mage::registry('current_traineedoc');
    }
}
