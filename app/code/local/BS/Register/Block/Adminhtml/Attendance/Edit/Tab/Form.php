<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Attendance edit form tab
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Adminhtml_Attendance_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Attendance_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('attendance_');
        $form->setFieldNameSuffix('attendance');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'attendance_form',
            array('legend' => Mage::helper('bs_register')->__('Absence Record'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_register/adminhtml_attendance_helper_file')
        );

        $currentAttendance = Mage::registry('current_attendance');

        $productId = null;
        if($this->getRequest()->getParam('product_id')){
            $productId = $this->getRequest()->getParam('product_id');
        }elseif ($currentAttendance){
            $productId = $currentAttendance->getCourseId();
        }

        $traineeId = null;
        if($this->getRequest()->getParam('trainee_id')){
            $traineeId = $this->getRequest()->getParam('trainee_id');
        }elseif ($currentAttendance){
            $traineeId = $currentAttendance->getTraineeId();
        }

        Mage::register('register_product_id', $productId);
        Mage::register('register_trainee_id', $traineeId);



        $currentDate = Mage::getModel('core/date')->gmtDate();

        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku');

        if($productId){
            $values->addAttributeToFilter('entity_id',$productId);
        }else {
            $values->addFieldToFilter('course_start_date',
                        array('to' => $currentDate))
                    ->addFieldToFilter('course_finish_date',
                        array('from' => $currentDate)
                    );
        }


        $values = $values->toSkuOptionArray();

        //array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a style="color: #eb5e00;" href="{#url}" id="attendance_course_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeProductIdLink() {
                if ($(\'attendance_course_id\').value == \'\') {
                    $(\'attendance_course_id_link\').hide();
                } else {
                    $(\'attendance_course_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/catalog_product/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View course {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'attendance_course_id\').value);
                    $(\'attendance_course_id_link\').href = realUrl;
                    $(\'attendance_course_id_link\').innerHTML = text.replace(\'{#name}\', $(\'attendance_course_id\').options[$(\'attendance_course_id\').selectedIndex].innerHTML);
                }
            }
            $(\'attendance_course_id\').observe(\'change\', changeProductIdLink);
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
        $values = Mage::getResourceModel('bs_trainee/trainee_collection')
            ->addAttributeToSelect('*');

        if($traineeId){
            $values->addAttributeToFilter('entity_id', $traineeId);
        }
        if($productId){
            $values->addProductFilter($productId);
        }

        $values = $values->toFullOptionArray();
        //array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a style="color: #eb5e00;" href="{#url}" id="attendance_trainee_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeTraineeIdLink() {
                if ($(\'attendance_trainee_id\').value == \'\') {
                    $(\'attendance_trainee_id_link\').hide();
                } else {
                    $(\'attendance_trainee_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/trainee_trainee/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View trainee {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'attendance_trainee_id\').value);
                    $(\'attendance_trainee_id_link\').href = realUrl;
                    $(\'attendance_trainee_id_link\').innerHTML = text.replace(\'{#name}\', $(\'attendance_trainee_id\').options[$(\'attendance_trainee_id\').selectedIndex].innerHTML);
                }
            }
            $(\'attendance_trainee_id\').observe(\'change\', changeTraineeIdLink);
            changeTraineeIdLink();
            </script>';

        if($popup){
            $html = '';
        }
        $fieldset->addField(
            'trainee_id',
            'select',
            array(
                'label'     => Mage::helper('bs_register')->__('Trainee'),
                'name'      => 'trainee_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => $html
            )
        );



        $fieldset->addField(
            'att_start_date',
            'date',
            array(
                'label' => Mage::helper('bs_register')->__('OFF From Date'),
                'name'  => 'att_start_date',
                'note'	=> $this->__('OFF From Date'),
                'required'  => true,
                'class' => 'required-entry',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'att_start_time',
            'select',
            array(
                'label' => Mage::helper('bs_register')->__('OFF From Time'),
                'name'  => 'att_start_time',
                'note'	=> $this->__('Select Morning or Afternoon. If not select, it means all day.'),

            'values'=> Mage::getModel('bs_register/attendance_attribute_source_attstarttime')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'att_finish_date',
            'date',
            array(
                'label' => Mage::helper('bs_register')->__('OFF To Date'),
                'name'  => 'att_finish_date',
                'required'  => true,
                'class' => 'required-entry',
                'note'	=> $this->__('OFF To Date'),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'att_finish_time',
            'select',
            array(
                'label' => Mage::helper('bs_register')->__('OFF To Time'),
                'name'  => 'att_finish_time',
            'note'	=> $this->__('Select Morning or Afternoon. If not select, it means all day.'),

            'values'=> Mage::getModel('bs_register/attendance_attribute_source_attfinishtime')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'att_excuse',
            'select',
            array(
                'label' => Mage::helper('bs_register')->__('Excuse'),
                'name'  => 'att_excuse',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_register')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_register')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'att_file',
            'file',
            array(
                'label' => Mage::helper('bs_register')->__('File'),
                'name'  => 'att_file',

            )
        );


        $fieldset->addField(
            'att_note',
            'text',
            array(
                'label' => Mage::helper('bs_register')->__('Note'),
                'name'  => 'att_note',


           )
        );
        /*$fieldset->addField(
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
        );*/
        $formValues = Mage::registry('current_attendance')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getAttendanceData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAttendanceData());
            Mage::getSingleton('adminhtml/session')->setAttendanceData(null);
        } elseif (Mage::registry('current_attendance')) {
            $formValues = array_merge($formValues, Mage::registry('current_attendance')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
