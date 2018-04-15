<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function edit form tab
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Block_Adminhtml_Taskfunction_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Tasktraining_Block_Adminhtml_Taskfunction_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('taskfunction_');
        $form->setFieldNameSuffix('taskfunction');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'taskfunction_form',
            array('legend' => Mage::helper('bs_tasktraining')->__('Instructor Function'))
        );

        $current = Mage::registry('current_taskfunction');

        $instructorId = null;
        $categoryId = null;
        if($this->getRequest()->getParam('instructor_id')){
            $instructorId = $this->getRequest()->getParam('instructor_id');
        }elseif ($current){
            $instructorId = $current->getInstructorId();
        }


        if($this->getRequest()->getParam('category_id')){
            $categoryId = $this->getRequest()->getParam('category_id');
        }elseif ($current){
            $categoryId = $current->getCategoryId();
        }

        $values = Mage::getModel('catalog/category')->getCollection()->addFieldToFilter('path', array('like'=>'1/2/122%'));
        if($categoryId){
            $values->addAttributeToFilter('entity_id', $categoryId);
        }

        $cats = array();
        foreach ($values as $value) {
            $cat = Mage::getModel('catalog/category')->load($value->getId());
            if($cat){
                $cats[] = array(
                    'label' => $value->getId().'-'.$cat->getName(),
                    'value' => $value->getId()
                );
            }

        }

        $fieldset->addField(
            'category_id',
            'select',
            array(
                'label'     => Mage::helper('bs_tasktraining')->__('Category'),
                'name'      => 'category_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $cats,
            )
        );


        $values = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection();
        if($instructorId){
            $values->addFieldToFilter('entity_id', $instructorId);
        }

        $ins = array();
        foreach ($values as $value) {
            $cat = Mage::getModel('bs_tasktraining/taskinstructor')->load($value->getId());
            if($cat){
                $ins[] = array(
                    'label' => $cat->getName(),
                    'value' => $value->getId()
                );
            }

        }

        $fieldset->addField(
            'instructor_id',
            'select',
            array(
                'label'     => Mage::helper('bs_tasktraining')->__('Instructor'),
                'name'      => 'instructor_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $ins,
            )
        );





        $fieldset->addField(
            'approved_course',
            'text',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Approved Course'),
                'name'  => 'approved_course',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'approved_function',
            'text',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Approved Function'),
                'name'  => 'approved_function',

           )
        );

        $fieldset->addField(
            'approved_doc',
            'text',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Approved Doc'),
                'name'  => 'approved_doc',

            )
        );


        $fieldset->addField(
            'approved_date',
            'date',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Approved Date'),
                'name'  => 'approved_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );
        $fieldset->addField(
            'expire_date',
            'date',
            array(
                'label' => Mage::helper('bs_tasktraining')->__('Expire Date'),
                'name'  => 'expire_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        /*$fieldset->addField(
            'is_ti',
            'select',
            array(
                'label'  => Mage::helper('bs_tasktraining')->__('Is Theoretical Instructor?'),
                'name'   => 'is_ti',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_tasktraining')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_tasktraining')->__('No'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'is_te',
            'select',
            array(
                'label'  => Mage::helper('bs_tasktraining')->__('Is Theoretical Evaluator?'),
                'name'   => 'is_te',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_tasktraining')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_tasktraining')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'is_pi',
            'select',
            array(
                'label'  => Mage::helper('bs_tasktraining')->__('Is Practical Instructor?'),
                'name'   => 'is_pi',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_tasktraining')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_tasktraining')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'is_pe',
            'select',
            array(
                'label'  => Mage::helper('bs_tasktraining')->__('Is Practical Evaluator?'),
                'name'   => 'is_pe',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_tasktraining')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_tasktraining')->__('No'),
                    ),
                ),
            )
        );*/
        
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
                'label' => Mage::helper('bs_tasktraining')->__('OR Import'),
                'name'  => 'import',
                'note'  => 'Format: CatId -- VAECOID -- Course -- Function'

            )
        );
        
        $formValues = Mage::registry('current_taskfunction')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTaskfunctionData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTaskfunctionData());
            Mage::getSingleton('adminhtml/session')->setTaskfunctionData(null);
        } elseif (Mage::registry('current_taskfunction')) {
            $formValues = array_merge($formValues, Mage::registry('current_taskfunction')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('taskfunction_import') != undefined){
                        $('taskfunction_import').observe('keyup', function(){
                            taskfunction_approved_course.setValue('This will be ignored');
                        });
                        
                        $('taskfunction_category_id').observe('change', function(){
                            
                             new Ajax.Request('".$this->getUrl('*/tasktraining_taskfunction/getCourse')."', {
                            method : 'post',
                            parameters: {
                                'cat_id'   : $('taskfunction_category_id').getValue(),
                            },
                            onSuccess : function(transport){
                                try{
                                    response = eval('(' + transport.responseText + ')');
                                } catch (e) {
                                    response = {};
                                }
                                if(response.course){

                                    if($('taskfunction_approved_course') != undefined){
                                        $('taskfunction_approved_course').value = response.course;
                                    }
                                    if($('taskfunction_approved_function') != undefined){
                                        $('taskfunction_approved_function').value = response.function;
                                    }

                                }else {
                                    alert('Please check your data');
                                }
                
                            },
                            onFailure : function(transport) {
                                alert('Please check your data')
                            }
                        });
                        });
                        
                    }
                    
                    



                </script>";
        return parent::_afterToHtml($html);
    }
}
