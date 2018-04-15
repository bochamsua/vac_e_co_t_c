<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function edit form
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskfunction_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Edit_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/*/save',
                    array(
                        'id' => $this->getRequest()->getParam('id')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
