<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy edit form
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form
     *
     * @access protected
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Edit_Form
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
