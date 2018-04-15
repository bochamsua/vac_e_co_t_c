<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule edit form tab
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Schedule_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Schedule_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('schedule_');
        $form->setFieldNameSuffix('schedule');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'schedule_form',
            array('legend' => Mage::helper('bs_register')->__('Course Schedule'))
        );



        $currentSchedule = Mage::registry('current_schedule');

        $productId = null;
        $curriculumId = null;
        if($this->getRequest()->getParam('product_id')){
            $productId = $this->getRequest()->getParam('product_id');
        }elseif ($currentSchedule){
            $productId = $currentSchedule->getCourseId();
        }

        $subjectId = null;
        if($this->getRequest()->getParam('subject_id')){
            $subjectId = $this->getRequest()->getParam('subject_id');
        }elseif ($currentSchedule){
            $subjectId = $currentSchedule->getSubjectId();
        }

        $instructorId = null;
        if($this->getRequest()->getParam('instructor_id')){
            $instructorId = $this->getRequest()->getParam('instructor_id');
        }elseif ($currentSchedule){
            $instructorId = $currentSchedule->getInstructorId();
        }

        $roomId = null;
        if($this->getRequest()->getParam('room_id')){
            $roomId = $this->getRequest()->getParam('room_id');
        }elseif ($currentSchedule){
            $roomId = $currentSchedule->getRoomId();
        }



        Mage::register('register_product_id', $productId);
        Mage::register('register_instructor_id', $instructorId);
        //Mage::register('register_subject_id', $subjectId);
        Mage::register('register_room_id', $roomId);


        $currentDate = Mage::getModel('core/date')->gmtDate(null, 'tomorrow');

        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            //->addAttributeToFilter('status',1)
            ->addAttributeToFilter('course_report',0);

        if($productId){
            $values->addAttributeToFilter('entity_id',$productId);
        }else {
            //$values->addAttributeToFilter('course_start_date', array('from' => $currentDate))
                   //->addAttributeToFilter('course_finish_date',array('from' => $currentDate))
            //;
        }


        $values = $values->toSkuOptionArray();

        //array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a style="color: #eb5e00;" href="{#url}" id="schedule_course_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeProductIdLink() {
                if ($(\'schedule_course_id\').value == \'\') {
                    $(\'schedule_course_id_link\').hide();
                } else {
                    $(\'schedule_course_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/catalog_product/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View course {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'schedule_course_id\').value);
                    $(\'schedule_course_id_link\').href = realUrl;
                    $(\'schedule_course_id_link\').innerHTML = text.replace(\'{#name}\', $(\'schedule_course_id\').options[$(\'schedule_course_id\').selectedIndex].innerHTML);
                }
            }
            $(\'schedule_course_id\').observe(\'change\', changeProductIdLink);
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
                'label'     => Mage::helper('bs_register')->__('Course'),
                'name'      => 'course_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => $html
            )
        );


        $values = Mage::getResourceModel('bs_subject/subject_collection');

        if($subjectId){
            //$values->addFieldToFilter('entity_id', $subjectId);
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
            if($subjectId){
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('The subject does not belong to the curriculum!')
                );
            }else {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('The curriculum of this course doesn\'t have any subjects!')
                );
            }


            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/catalog_product/edit", array('id'=>$productId,'back'=>'edit/tab/product_info_tabs_subjects', 'popup'=>1)));



        }


        $fieldset->addField(
            'schedule_subjects',
            'multiselect',
            array(
                'label'     => Mage::helper('bs_register')->__('Subjects'),
                'name'      => 'schedule_subjects',
                //'required'  => true,
                //'class' => 'required-entry',
                'values'    => $values,
                //'after_element_html' => $html
            )
        );



        $values = Mage::getResourceModel('bs_instructor/instructor_collection')
            ->addAttributeToSelect('*');

        if($instructorId){
            //$values->addAttributeToFilter('entity_id', $instructorId);
        }
        if($productId){
            $values->addProductFilter($productId);
        }
        if($curriculumId){
            $values->addCurriculumFilter($curriculumId);
        }

        $values = $values->toFullOptionArray();

        if(!count($values)){
            //Register for a subject that doesnt belong to the course
            if($instructorId){
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('The instructor was not assigned to instruct the course!')
                );
            }else {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('Please select instructors first!')
                );
            }


            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/catalog_product/edit", array('id'=>$productId,'back'=>'edit/tab/product_info_tabs_instructors', 'popup'=>1)));


        }

        $html = '<a style="color: #eb5e00;" href="{#url}" id="schedule_instructor_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeInstructorIdLink() {
                if ($(\'schedule_instructor_id\').value == \'\') {
                    $(\'schedule_instructor_id_link\').hide();
                } else {
                    $(\'schedule_instructor_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/instructor_instructor/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View instructor {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'schedule_instructor_id\').value);
                    $(\'schedule_instructor_id_link\').href = realUrl;
                    $(\'schedule_instructor_id_link\').innerHTML = text.replace(\'{#name}\', $(\'schedule_instructor_id\').options[$(\'schedule_instructor_id\').selectedIndex].innerHTML);
                }
            }
            $(\'schedule_instructor_id\').observe(\'change\', changeInstructorIdLink);
            changeInstructorIdLink();
            </script>';

        if($popup){
            $html = '';
        }
        $fieldset->addField(
            'instructor_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Instructor'),
                'name'      => 'instructor_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => $html
            )
        );






        $values = Mage::getResourceModel('bs_logistics/classroom_collection')
            ->addFieldToSelect('*');

        if($roomId){
            //$values->addFieldToFilter('entity_id', $roomId);
        }
        if($productId){
            $values->addProductFilter($productId);
        }

        $values = $values->toOptionArray();


        if(!count($values)){
            //Register for a subject that doesnt belong to the course
            if($roomId){
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('The room was not assigned to the course yet.')
                );
            }else {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('Please select the room to conduct the course!')
                );
            }


            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/catalog_product/edit", array('id'=>$productId,'back'=>'edit/tab/product_info_tabs_rooms', 'popup'=>1)));


        }

        $html = '<a style="color: #eb5e00;" href="{#url}" id="schedule_room_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeRoomIdLink() {
                if ($(\'schedule_room_id\').value == \'\') {
                    $(\'schedule_room_id_link\').hide();
                } else {
                    $(\'schedule_room_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_classroom/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View room {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'schedule_room_id\').value);
                    $(\'schedule_room_id_link\').href = realUrl;
                    $(\'schedule_room_id_link\').innerHTML = text.replace(\'{#name}\', $(\'schedule_room_id\').options[$(\'schedule_room_id\').selectedIndex].innerHTML);
                }
            }
            $(\'schedule_room_id\').observe(\'change\', changeRoomIdLink);
            changeRoomIdLink();
            </script>';

        if($popup){
            $html = '';
        }
        $fieldset->addField(
            'room_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Room'),
                'name'      => 'room_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => $html
            )
        );


        $fieldset->addField(
            'schedule_start_date',
            'date',
            array(
                'label' => Mage::helper('bs_register')->__('Start Date'),
                'name'  => 'schedule_start_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        /*$fieldset->addField(
            'schedule_start_time',
            'multiselect',
            array(
                'label' => Mage::helper('bs_register')->__('Start Time'),
                'name'  => 'schedule_start_time',

                'note'	=> $this->__('If not select, it means all day.'),
                'values'=> Mage::getModel('bs_register/schedule_attribute_source_schedulestarttime')->getAllOptions(false),
            )
        );*/

        $fieldset->addField(
            'schedule_finish_date',
            'date',
            array(
                'label' => Mage::helper('bs_register')->__('Finish Date'),
                'name'  => 'schedule_finish_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        /*$fieldset->addField(
            'schedule_finish_time',
            'multiselect',
            array(
                'label' => Mage::helper('bs_register')->__('Finish Time'),
                'name'  => 'schedule_finish_time',
                'note'	=> $this->__('If not select, it means all day.'),

                'values'=> Mage::getModel('bs_register/schedule_attribute_source_schedulefinishtime')->getAllOptions(false),
            )
        );*/

        $fieldset->addField(
            'schedule_hours',
            'text',

            array(
                'label' => Mage::helper('bs_register')->__('Total hours'),
                'name'  => 'schedule_hours',
                'required'  => true,
                'class' => 'required-entry',
                'note'	=> $this->__('Please provide total hours here. This must be the total hours of the subject OR total hours of subject contents in case the subject is divide into several blocks.'),

            )
        );

        $fieldset->addField(
            'schedule_order',
            'text',
            array(
                'label' => Mage::helper('bs_register')->__('Sort Order'),
                'name'  => 'schedule_order',

            )
        );

        $fieldset->addField(
            'schedule_note',
            'text',
            array(
                'label' => Mage::helper('bs_register')->__('Note'),
                'name'  => 'schedule_note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_register')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_register')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_register')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_schedule')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getScheduleData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getScheduleData());
            Mage::getSingleton('adminhtml/session')->setScheduleData(null);
        } elseif (Mage::registry('current_schedule')) {
            $formValues = array_merge($formValues, Mage::registry('current_schedule')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
