<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor edit form tab
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskinstructor_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskinstructor_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('taskinstructor_');
        $form->setFieldNameSuffix('taskinstructor');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'taskinstructor_form',
            array('legend' => Mage::helper('bs_tasktraining')->__('Instructor'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Name'),
                'name'  => 'name',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('VAECO ID'),
                'name'  => 'vaeco_id',

           )
        );

        $fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Note'),
                'name'  => 'note',

           )
        );

        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('OR Import from this'),
                'name'  => 'import',
                'note'  => 'Enter VAECO ID one on each line. Can be in any of following format: VAE02907, 02907 or just 2907'

            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_tasktraining')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_tasktraining')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_tasktraining')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_taskinstructor')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTaskinstructorData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTaskinstructorData());
            Mage::getSingleton('adminhtml/session')->setTaskinstructorData(null);
        } elseif (Mage::registry('current_taskinstructor')) {
            $formValues = array_merge($formValues, Mage::registry('current_taskinstructor')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
