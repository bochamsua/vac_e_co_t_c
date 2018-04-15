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
    protected function _prepareLayout()
    {
        $type = $this->getScheduleType();
        if($type == 1){
            $this->setTemplate('bs_register/schedule/form.phtml');
        }

        return parent::_prepareLayout();
    }

    public function getScheduleType(){
        $type = $this->getRequest()->getParam('v2', false);
        if($type){
            return 1;
        }
        return 0;
    }

    public function getAddButtonId(){
        return 'add_new_row';
    }

    public function getFieldId(){
        return 'schedule';
    }

    public function getProductId()
    {
        return $this->getRequest()->getParam('product_id');
    }

    public function getSubjects($multi = false)
    {
        $values = Mage::getResourceModel('bs_subject/subject_collection');

        $productId = $this->getProductId();
        $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
        $curriculums->addProductFilter($productId);

        if($cu = $curriculums->getFirstItem()){
            $curriculumId = $cu->getId();
            $values->addFieldToFilter('curriculum_id',$curriculumId);
        }

        $values->setOrder('subject_order','ASC')->setOrder('entity_id', 'ASC');

        if($values->count()){
            $select = $this->getLayout()->createBlock('adminhtml/html_select');

            if($multi){
                $select->setData(array(
                    'id' => $this->getFieldId().'_multiselect_{{id}}',
                    'class' => 'select multiselect',
                ));
                $select->setName('schedule[{{id}}][schedule_subjects][]');
            }else {
                $select->setData(array(
                    'id' => $this->getFieldId().'_single_{{id}}',
                    'class' => 'select',
                ));
                $select->setName('schedule[{{id}}][subject_id]');
            }

            $select->setOptions($values->toFullOptionArray());
            $html = $select->getHtml();

            if($multi){
                $html = str_replace("class=\"select multiselect\"", "multiple=\"multiple\" class=\"select multiselect\"", $html);
            }



            return $html;
        }

        return array();
    }
    public function getSubjectTypeSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_type_select_{{id}}',
                'class' => 'select select-sub-type'
            ))
            ->setName('schedule[{{id}}][subject_type]')
            ->setOptions(array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_register')->__('Single'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_register')->__('Multiple'),
                ),
            ));

        return $select->getHtml();
    }

    public function getInstructorSelectHtml()
    {
        $productId = $this->getProductId();
        $values = Mage::getResourceModel('bs_instructor/instructor_collection')
            ->addAttributeToSelect('*');

        if($productId){
            $values->addProductFilter($productId);
        }
        $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
        $curriculums->addProductFilter($productId);

        if($cu = $curriculums->getFirstItem()){
            $curriculumId = $cu->getId();
            $values->addCurriculumFilter($curriculumId);
        }

        $values = $values->toFullOptionArray();
        $values[] = array(
            'value' => 223,
            'label' => 'TC Examiner'
        );

        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_instructor_select_{{id}}',
                'class' => 'select select-instructor'
            ))
            ->setName('schedule[{{id}}][instructor_id]')
            ->setOptions($values);

        return $select->getHtml();
    }

    public function getRoomSelectHtml()
    {
        $productId = $this->getProductId();
        $values = Mage::getResourceModel('bs_logistics/classroom_collection')
            ->addFieldToSelect('*');

        if($productId){
            $values->addProductFilter($productId);
        }

        $values = $values->toOptionArray();

        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_room_select_{{id}}',
                'class' => 'select select-room'
            ))
            ->setName('schedule[{{id}}][room_id]')
            ->setOptions($values);

        return $select->getHtml();
    }

    public function getStartDate(){
        $productId = $this->getProductId();
        $course = Mage::getModel('catalog/product')->load($productId);
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $course->getCourseStartDate());

        return $startDate;
    }

    public function getFinishDate(){
        $productId = $this->getProductId();
        $course = Mage::getModel('catalog/product')->load($productId);
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $course->getCourseFinishDate());

        return $finishDate;
    }



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

        $productId = $this->getRequest()->getParam('product_id');
        $course = Mage::getModel('catalog/product')->load($productId);
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $course->getCourseStartDate());
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $course->getCourseFinishDate());

        $fieldset = $form->addFieldset(
            'schedule_form',
            array('legend' => Mage::helper('bs_register')->__('Course from %s to %s', $startDate, $finishDate))
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



        $popup = $this->getRequest()->getParam('popup', false);

        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Course'),
                'name'      => 'course_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
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
        $values->setOrder('subject_order','ASC')->setOrder('entity_id', 'ASC');

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
            'schedule_start_date',
            'date',
            array(
                'label' => Mage::helper('bs_register')->__('From Date'),
                'name'  => 'schedule_start_date',
                //'renderer'  => 'BS_Register_Block_Adminhtml_Helper_Form_Renderer_Date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                //'from'     => $startDate,
                //'to'       => $finishDate
            )
        );



        $fieldset->addField(
            'schedule_finish_date',
            'date',
            array(
                'label' => Mage::helper('bs_register')->__('To Date'),
                'name'  => 'schedule_finish_date',
                //'renderer'  => 'BS_Register_Block_Adminhtml_Helper_Form_Renderer_Date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                //'from'     => $startDate,
                //'to'       => $finishDate
            )
        );

        $fieldset->addField(
            'subject_type',
            'select',
            array(
                'label'  => Mage::helper('bs_register')->__('Subject Type'),
                'name'   => 'subject_type',
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_register')->__('Single'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_register')->__('Multiple'),
                    ),
                ),
            )
        );



        $fieldset->addField(
            'subject_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Subject'),
                'name'      => 'subject_id',
                //'required'  => true,
                //'class' => 'required-entry',
                'values'    => $values,
            )
        );

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
        $values[] = array(
            'value' => 223,
            'label' => 'TC Examiner'
        );

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




        $fieldset->addField(
            'instructor_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Instructor'),
                'name'      => 'instructor_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
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

        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap("schedule_subject_id", 'subject_id')
            ->addFieldMap("schedule_schedule_subjects", 'schedule_subjects')
            ->addFieldMap("schedule_subject_type", 'subject_type')
            ->addFieldDependence('subject_id', 'subject_type', array('0'))
            ->addFieldDependence('schedule_subjects', 'subject_type', array('1'))
        );
        return parent::_prepareForm();
    }
}
