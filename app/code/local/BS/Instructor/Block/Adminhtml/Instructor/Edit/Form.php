<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor edit form
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Edit_Form
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
                        'id' => $this->getRequest()->getParam('id'),
                        'store' => $this->getRequest()->getParam('store')
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
