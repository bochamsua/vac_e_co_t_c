<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject edit form tab
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subject_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subject_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('subject_');
        $form->setFieldNameSuffix('subject');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'subject_form',
            array('legend' => Mage::helper('bs_subject')->__('Subject'))
        );


        $currentSubject = Mage::registry('current_subject');
        $curriculumId = null;
        if($this->getRequest()->getParam('curriculum_id')){
            $curriculumId = $this->getRequest()->getParam('curriculum_id');
        }elseif ($currentSubject){
            $curriculumId = $currentSubject->getCurriculumId();
        }


        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection');
        if($curriculumId){
            $values->addFieldToFilter('entity_id', $curriculumId);
        }
        $values = $values->toOptionArray();


        $html = '<a href="{#url}" id="subject_curriculum_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeCurriculumIdLink() {
                if ($(\'subject_curriculum_id\').value == \'\') {
                    $(\'subject_curriculum_id_link\').hide();
                } else {
                    $(\'subject_curriculum_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/traininglist_curriculum/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'subject_curriculum_id\').value);
                    $(\'subject_curriculum_id_link\').href = realUrl;
                    $(\'subject_curriculum_id_link\').innerHTML = text.replace(\'{#name}\', $(\'subject_curriculum_id\').options[$(\'subject_curriculum_id\').selectedIndex].innerHTML);
                }
            }
            $(\'subject_curriculum_id\').observe(\'change\', changeCurriculumIdLink);
            changeCurriculumIdLink();
            </script>';

        $popup = $this->getRequest()->getParam('popup', false);
        if($popup){
            $html = '';
        }
        $fieldset->addField(
            'curriculum_id',
            'select',
            array(
                'label'     => Mage::helper('bs_subject')->__('Curriculum'),
                'name'      => 'curriculum_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'subject_name',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Subject Name'),
                'name'  => 'subject_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        /*$fieldset->addField(
            'subject_code',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Code'),
                'name'  => 'subject_code',
                'readonly'=> true,
                'note'  => 'This field is READ ONLY. It will be automatically generated'

           )
        );*/

        $fieldset->addField(
            'subject_shortcode',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Shortcode'),
                'name'  => 'subject_shortcode',
                'note'  => 'Input the shortcode here, for example: M3, ATA29. This shortcode will be helpful when we generate report because we dont have enough space for long name.'

            )
        );

        $fieldset->addField(
            'subject_level',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Level'),
                'name'  => 'subject_level',

           )
        );

        $fieldset->addField(
            'subject_hour',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Hour'),
                'name'  => 'subject_hour',

           )
        );

        $fieldset->addField(
            'subject_content',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('Content'),
                'name'  => 'subject_content',
                'note'  => 'If the content has multiple lines. Enter each in one line'

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_subject')->__('Is this Training Chapter?'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_subject')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_subject')->__('No'),
                    ),
                ),
                'note' => 'The report will sum up this to total training chapters.'
            )
        );
        $fieldset->addField(
            'require_exam',
            'select',
            array(
                'label'  => Mage::helper('bs_subject')->__('Does this training chapter require exam?'),
                'name'   => 'require_exam',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_subject')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_subject')->__('No'),
                    ),
                ),
                'note' => 'This will be used to generate Exam result report and to input the mark for trainees.'
            )
        );
        /*$fieldset->addField(
            'subject_exam',
            'select',
            array(
                'label'  => Mage::helper('bs_subject')->__('Is this Exam Chapter?'),
                'name'   => 'subject_exam',
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_subject')->__('No'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_subject')->__('Yes'),
                    ),
                ),
                'note' => 'The report will sum up this to total exam chapters.'

            )
        );*/

        $fieldset->addField(
            'subject_ws',
            'select',
            array(
                'label'  => Mage::helper('bs_subject')->__('Refer to WS?'),
                'name'   => 'subject_ws',
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_subject')->__('No'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_subject')->__('Yes'),
                    ),
                ),
                'note'  => 'For Practical Part. If the curriculum has worksheet. If Yes, Content field will be updated after Save.'

            )
        );

        /*$fieldset->addField(
            'subject_onlycontent',
            'select',
            array(
                'label'  => Mage::helper('bs_subject')->__('Use only content of this subject?'),
                'name'   => 'subject_onlycontent',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_subject')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_subject')->__('No'),
                    ),
                ),
                'note' => Mage::helper('bs_subject')->__('This is useful for Recurrent subject that we merge content into one row only')

            )
        );*/





        $fieldset->addField(
            'subject_order',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Order'),
                'name'  => 'subject_order',

           )
        );

        $fieldset->addField(
            'subject_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('Remark'),
                'name'  => 'subject_note',

            )
        );

        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('OR import from this?'),
                'name'  => 'import',
                'note'  => Mage::helper('bs_subject')->__('Name--level--hour--training chapter--require exam | Name--level--hour--training chapter--require exam--order'),

            )
        );

        $formValues = Mage::registry('current_subject')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getSubjectData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getSubjectData());
            Mage::getSingleton('adminhtml/session')->setSubjectData(null);
        } elseif (Mage::registry('current_subject')) {
            $formValues = array_merge($formValues, Mage::registry('current_subject')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
