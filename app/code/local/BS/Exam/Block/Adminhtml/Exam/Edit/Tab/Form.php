<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam edit form tab
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Exam_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Exam_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('exam_');
        $form->setFieldNameSuffix('exam');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'exam_form',
            array('legend' => Mage::helper('bs_exam')->__('Exam'))
        );

        $currentExam = Mage::registry('current_exam');
        $curriculumId = null;
        if($this->getRequest()->getParam('product_id')){
            $productId = $this->getRequest()->getParam('product_id');
        }elseif ($currentExam){
            $productId = $currentExam->getCourseId();
        }



        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            //->addAttributeToFilter('status',1)
            //->addAttributeToFilter('course_report',0)
        ;

        if($productId){
            $values->addAttributeToFilter('entity_id',$productId);
        }else {
            //$values->addAttributeToFilter('course_start_date', array('from' => $currentDate))
            //->addAttributeToFilter('course_finish_date',array('from' => $currentDate))
            //;
        }


        $values = $values->toSkuOptionArray();

        $html = '<a style="color: #eb5e00;" href="{#url}" id="exam_course_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeProductIdLink() {
                if ($(\'exam_course_id\').value == \'\') {
                    $(\'exam_course_id_link\').hide();
                } else {
                    $(\'exam_course_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/catalog_product/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View course {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'exam_course_id\').value);
                    $(\'exam_course_id_link\').href = realUrl;
                    $(\'exam_course_id_link\').innerHTML = text.replace(\'{#name}\', $(\'exam_course_id\').options[$(\'exam_course_id\').selectedIndex].innerHTML);
                }
            }
            $(\'exam_course_id\').observe(\'change\', changeProductIdLink);
            changeProductIdLink();
            </script>';

        $popup = $this->getRequest()->getParam('popup', false);
        if($popup){
            $html = '';
        }
        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Course'),
                'name'      => 'course_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'exam_content',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Exam Content'),
                'name'  => 'exam_content',
                'required'  => true,
                'class' =>'required_entry',
                'note'  => 'Put the short code of subjects to be included in the exam. For example, ATA23 or M4'

            )
        );

        $fieldset->addField(
            'subject_ids',
            'multiselect',
            array(
                'label' => Mage::helper('bs_exam')->__('Subject'),
                'name'  => 'subject_ids',

            'values'=> Mage::getModel('bs_exam/exam_attribute_source_subjectids')->getAllOptions(false),
           )
        );

        $fieldset->addField(
            'exam_date',
            'date',
            array(
                'label' => Mage::helper('bs_exam')->__('Exam Date'),
                'name'  => 'exam_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );
        $fieldset->addField(
            'start_time',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Start Time'),
                'name'  => 'start_time',

            )
        );

        $fieldset->addField(
            'exam_qty',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Total Questions'),
                'name'  => 'exam_qty',

            )
        );
        $fieldset->addField(
            'exam_duration',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Total Minutes'),
                'name'  => 'exam_duration',

            )
        );

        $fieldset->addField(
            'examiners',
            'multiselect',
            array(
                'label' => Mage::helper('bs_exam')->__('Examiner'),
                'name'  => 'examiners',

            'values'=> Mage::getModel('bs_exam/exam_attribute_source_examiners')->getAllOptions(false),
           )
        );

        $values = Mage::getResourceModel('bs_logistics/classroom_collection')
            ->addFieldToSelect('*');




        $values = $values->toOptionArray();




        $fieldset->addField(
            'room_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Room'),
                'name'      => 'room_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,

            )
        );

        $fieldset->addField(
            'exam_times',
            'select',
            array(
                'label'  => Mage::helper('bs_exam')->__('Exam Times?'),
                'name'   => 'exam_times',
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_exam')->__('1st Exam'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_exam')->__('Retaking Exam'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'exam_note',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Note'),
                'name'  => 'exam_note',

           )
        );
       /* $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_exam')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_exam')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_exam')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_exam')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getExamData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getExamData());
            Mage::getSingleton('adminhtml/session')->setExamData(null);
        } elseif (Mage::registry('current_exam')) {
            $formValues = array_merge($formValues, Mage::registry('current_exam')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
