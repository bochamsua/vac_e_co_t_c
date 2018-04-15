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
 * Curriculum Document edit form tab
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('curriculumdoc_');
        $form->setFieldNameSuffix('curriculumdoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'curriculumdoc_form',
            array('legend' => Mage::helper('bs_curriculumdoc')->__('Curriculum Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_curriculumdoc/adminhtml_curriculumdoc_helper_file')
        );

        $fieldset->addField(
            'cdoc_name',
            'text',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Document Name'),
                'name'  => 'cdoc_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );



        $fieldset->addField(
            'cdoc_type',
            'select',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Document Type'),
                'name'  => 'cdoc_type',
                'values'=> Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdoctype')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'cdoc_date',
            'date',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Approved/Revised Date'),
                'name'  => 'cdoc_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );




        $fieldset->addField(
            'cdoc_file',
            'file',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('File'),
                'name'  => 'cdoc_file',

           )
        );

        $fieldset->addField(
            'cdoc_page',
            'text',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Number of Pages'),
                'name'  => 'cdoc_page',

            )
        );

        $fieldset->addField(
            'cdoc_content',
            'textarea',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Content'),
                'name'  => 'cdoc_content',

            )
        );

        $fieldset->addField(
            'cdoc_amm',
            'select',
            array(
                'label'  => Mage::helper('bs_curriculumdoc')->__('AMM/CMM...?'),
                'name'   => 'cdoc_amm',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_curriculumdoc')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_curriculumdoc')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'cdoc_rev',
            'select',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Revision'),
                'name'  => 'cdoc_rev',

            'values'=> Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdocrev')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_curriculumdoc')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_curriculumdoc')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_curriculumdoc')->__('Disabled'),
                    ),
                ),
            )
        );

        $curriculumId = $this->getRequest()->getParam('curriculum_id', false);
        $fieldset->addField(
            'hidden_curriculum_id',
            'text',
            array(
                'label' => Mage::helper('bs_curriculumdoc')->__('Hidden Curriculum Id'),
                'name'  => 'hidden_curriculum_id',


            )
        );

        $formValues = Mage::registry('current_curriculumdoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCurriculumdocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCurriculumdocData());
            Mage::getSingleton('adminhtml/session')->setCurriculumdocData(null);
        } elseif (Mage::registry('current_curriculumdoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_curriculumdoc')->getData());
        }

        if($curriculumId){
            $formValues = array_merge($formValues, array('hidden_curriculum_id' => $curriculumId));
        }

        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {

        if(Mage::registry('current_curriculumdoc')->getId()){
            $html .= "<script>

                    if($('curriculumdoc_cdoc_type') != undefined){
                        $('curriculumdoc_cdoc_type').up('tr').setStyle({'display':'none'});
                    }


                </script>";
        }

        return parent::_afterToHtml($html);
    }
}
