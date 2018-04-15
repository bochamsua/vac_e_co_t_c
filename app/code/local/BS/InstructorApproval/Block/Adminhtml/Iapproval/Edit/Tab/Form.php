<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval edit form tab
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Block_Adminhtml_Iapproval_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_InstructorApproval_Block_Adminhtml_Iapproval_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('iapproval_');
        $form->setFieldNameSuffix('iapproval');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'iapproval_form',
            array('legend' => Mage::helper('bs_instructorapproval')->__('Instructor Approval'))
        );

        $fieldset->addField(
            'iapproval_title',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Approval Title'),
                'name'  => 'iapproval_title',
            'note'	=> $this->__('Specify instructor title. E.g.: “A330 type training instructor”.'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'iapproval_function',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Function'),
                'name'  => 'iapproval_function',
            'note'	=> $this->__('Specify function/ rating for instructor. E.g.: “Airframe and power plant”; “All”.'),

           )
        );

        $fieldset->addField(
            'pna',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('PNA'),
                'name'  => 'pna',
                'note'	=> $this->__('E.g.: "Specialized Training Instructor".'),


            )
        );

        $fieldset->addField(
            'iapproval_compliance',
            'multiselect',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Compliance With'),
                'name'  => 'iapproval_compliance',

            'values'=> Mage::getModel('bs_instructorapproval/iapproval_attribute_source_iapprovalcompliance')->getAllOptions(false),
           )
        );

        $fieldset->addField(
            'iapproval_compliance_other',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Compliance With Other'),
                'name'  => 'iapproval_compliance_other',

           )
        );

        $fieldset->addField(
            'vaeco_ids',
            'textarea',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('VAECO IDs and related training'),
                'name'  => 'vaeco_ids',
            'note'	=> $this->__('VAE02907 -- A320/321 Type Training -- Vietnam -- 2014 -- CAAV/TC-01'),

           )
        );
        $fieldset->addField(
            'related_working',
            'textarea',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Related Working Experience'),
                'name'  => 'related_working',
                'note'	=> $this->__('VAE02907 -- Training Center -- Aviation Engineer -- Since 07/2014'),

            )
        );


        $fieldset->addField(
            'iapproval_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Prepared Date'),
                'name'  => 'iapproval_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'type',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorapproval')->__('Type'),
                'name'   => 'type',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorapproval')->__('Supplemental'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorapproval')->__('Initial'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'generate_evaluation',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorapproval')->__('Generate Instructor Evaluation 2068/2069'),
                'name'   => 'generate_evaluation',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorapproval')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorapproval')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'evaluation_subject',
            'textarea',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Evaluation Subject'),
                'name'  => 'evaluation_subject',

            )
        );

        $fieldset->addField(
            'include_function',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorapproval')->__('Include Function (Mechanic/Avionics'),
                'name'   => 'include_function',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorapproval')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorapproval')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Note'),
                'name'  => 'note',

            )
        );

        $fieldset->addField(
            'include_keyword',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Incl. Keyword'),
                'name'  => 'include_keyword',
                'note'  => Mage::helper('bs_instructorapproval')->__('Coma "," separated')

            )
        );
        $fieldset->addField(
            'exclude_keyword',
            'text',
            array(
                'label' => Mage::helper('bs_instructorapproval')->__('Excl. Keyword'),
                'name'  => 'exclude_keyword',
                'note'  => Mage::helper('bs_instructorapproval')->__('Coma "," separated')

            )
        );

        $fieldset->addField(
            'search_info',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorapproval')->__('Search info from Training Record?'),
                'name'   => 'search_info',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorapproval')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorapproval')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'compress',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorapproval')->__('Compress as ZIP'),
                'name'   => 'compress',
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorapproval')->__('No'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorapproval')->__('Yes'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_iapproval')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getIapprovalData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getIapprovalData());
            Mage::getSingleton('adminhtml/session')->setIapprovalData(null);
        } elseif (Mage::registry('current_iapproval')) {
            $formValues = array_merge($formValues, Mage::registry('current_iapproval')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
