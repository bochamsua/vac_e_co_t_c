<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Result edit form tab
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Examresult_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Examresult_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('examresult_');
        $form->setFieldNameSuffix('examresult');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'examresult_form',
            array('legend' => Mage::helper('bs_exam')->__('Exam Result Info'))
        );

        $currentExamresult = Mage::registry('current_examresult');
        $currentId = null;
        if($currentExamresult){
            $currentId = $currentExamresult->getId();
        }

        $productId = null;
        $curriculumId = null;
        if($this->getRequest()->getParam('product_id')){
            $productId = $this->getRequest()->getParam('product_id');
        }elseif ($currentExamresult){
            $productId = $currentExamresult->getCourseId();
        }

        $subjectId = null;
        if($this->getRequest()->getParam('subject_id')){
            $subjectId = $this->getRequest()->getParam('subject_id');
        }elseif ($currentExamresult){
            $subjectId = $currentExamresult->getSubjectId();
        }

        $traineeId = null;
        if($this->getRequest()->getParam('trainee_id')){
            $traineeId = $this->getRequest()->getParam('trainee_id');
        }elseif ($currentExamresult){
            $traineeId = $currentExamresult->getTraineeId();
        }





        Mage::register('examresult_product_id', $productId);
        Mage::register('examresult_subject_id', $subjectId);
        Mage::register('examresult_trainee_id', $traineeId);


        $currentDate = Mage::getModel('core/date')->gmtDate(null, 'tomorrow');

        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToFilter('status',1)
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

        //array_unshift($values, array('label' => '', 'value' => ''));

        /*$html = '<a style="color: #eb5e00;" href="{#url}" id="examresult_course_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeProductIdLink() {
                if ($(\'examresult_course_id\').value == \'\') {
                    $(\'examresult_course_id_link\').hide();
                } else {
                    $(\'examresult_course_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/catalog_product/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View course {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'examresult_course_id\').value);
                    $(\'examresult_course_id_link\').href = realUrl;
                    $(\'examresult_course_id_link\').innerHTML = text.replace(\'{#name}\', $(\'examresult_course_id\').options[$(\'examresult_course_id\').selectedIndex].innerHTML);
                }
            }
            $(\'examresult_course_id\').observe(\'change\', changeProductIdLink);
            changeProductIdLink();
            </script>';*/

        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Course'),
                'name'      => 'course_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => ''
            )
        );

        $values = Mage::getResourceModel('bs_subject/subject_collection');

        if($subjectId){
            $values->addFieldToFilter('entity_id', $subjectId);
        }
        if($productId){

            $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
            $curriculums->addProductFilter($productId);

            if($cu = $curriculums->getFirstItem()){
                $curriculumId = $cu->getId();
                $values->addFieldToFilter('curriculum_id',$curriculumId);
            }

        }

        $values = $values->toFullOptionArray();

        if(!count($values)){
            //Register for a subject that doesnt belong to the course
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_exam')->__('The chosen subject does not belong to the course. Please choose another one or add the subject to the course first. Then save and try again.')
            );

            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/catalog_product/edit", array('id'=>$productId,'back'=>'edit/tab/product_info_tabs_subjects')));


        }

        /*$html = '<a style="color: #eb5e00;" href="{#url}" id="examresult_subject_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeSubjectIdLink() {
                if ($(\'examresult_subject_id\').value == \'\') {
                    $(\'examresult_subject_id_link\').hide();
                } else {
                    $(\'examresult_subject_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/subject_subject/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View subject {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'examresult_subject_id\').value);
                    $(\'examresult_subject_id_link\').href = realUrl;
                    $(\'examresult_subject_id_link\').innerHTML = text.replace(\'{#name}\', $(\'examresult_subject_id\').options[$(\'examresult_subject_id\').selectedIndex].innerHTML);
                }
            }
            $(\'examresult_subject_id\').observe(\'change\', changeSubjectIdLink);
            changeSubjectIdLink();
            </script>';*/

        $fieldset->addField(
            'subject_id',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Subject'),
                'name'      => 'subject_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => ''
            )
        );


        /*$values = Mage::getResourceModel('bs_trainee/trainee_collection')
            ->addAttributeToSelect('*');

        if($traineeId){
            $values->addAttributeToFilter('entity_id', $traineeId);
        }
        if($productId){
            $values->addProductFilter($productId);
        }

        $values = $values->toFullOptionArray();*/
        //array_unshift($values, array('label' => '', 'value' => ''));

        /*$html = '<a style="color: #eb5e00;" href="{#url}" id="examresult_trainee_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeTraineeIdLink() {
                if ($(\'examresult_trainee_id\').value == \'\') {
                    $(\'examresult_trainee_id_link\').hide();
                } else {
                    $(\'examresult_trainee_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/trainee_trainee/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View trainee {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'examresult_trainee_id\').value);
                    $(\'examresult_trainee_id_link\').href = realUrl;
                    $(\'examresult_trainee_id_link\').innerHTML = text.replace(\'{#name}\', $(\'examresult_trainee_id\').options[$(\'examresult_trainee_id\').selectedIndex].innerHTML);
                }
            }
            $(\'examresult_trainee_id\').observe(\'change\', changeTraineeIdLink);
            changeTraineeIdLink();
            </script>';*/

        /*$fieldset->addField(
            'trainee_id',
            'text',
            array(
                'label'     => Mage::helper('bs_exam')->__('Trainee'),
                'name'      => 'trainee_id',
            )
        );*/
        
        



        $fieldset->addField(
            'marks',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Mark Times'),
                'name'      => 'marks',
                'required'  => true,
                'class' => 'required-entry',
                'values' => array(
                    array(
                        'value' => null,
                        'label' => Mage::helper('bs_exam')->__(''),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_exam')->__('1st Mark'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('bs_exam')->__('2nd Mark'),
                    ),
                    array(
                        'value' => 3,
                        'label' => Mage::helper('bs_exam')->__('3rd Mark'),
                    ),
                ),
            )
        );


        $fieldset->addField(
            'current_examresult',
            'text',
            array(
                'label'     => Mage::helper('bs_exam')->__('Current Exam result'),
                'name'      => 'current_examresult',

            )
        );




        /*$fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
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

        $formValues = Mage::registry('current_examresult')->getDefaultValues();

        if (!is_array($formValues)) {
            $formValues = array();
        }
        $formValues['current_examresult'] = Mage::registry('current_examresult')->getId();
        if (Mage::getSingleton('adminhtml/session')->getExamresultData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getExamresultData());
            Mage::getSingleton('adminhtml/session')->setExamresultData(null);
        } elseif (Mage::registry('current_examresult')) {
            $formValues = array_merge($formValues, Mage::registry('current_examresult')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
