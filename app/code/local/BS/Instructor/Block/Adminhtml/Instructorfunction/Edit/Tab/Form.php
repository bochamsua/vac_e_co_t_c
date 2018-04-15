<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function edit form tab
 *
 * @category    BS
 * @package     BS_Instructor
 * @author Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructorfunction_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructorfunction_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('instructorfunction_');
        $form->setFieldNameSuffix('instructorfunction');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'instructorfunction_form',
            array('legend' => Mage::helper('bs_instructor')->__('Instructor Function'))
        );


        $current = Mage::registry('current_instructorfunction');

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

        //Only get ATO-103, AMO-104 and TC-110?


        $cats = array();

        if($categoryId){
            $cat = Mage::getModel('catalog/category')->load($categoryId);
            $cats[] = array(
                'label' => $cat->getName(),
                'value' => $categoryId
            );
        }else {
            $catArray = array(103, 104, 110);
            foreach ($catArray as $item) {
                $cat = Mage::getModel('catalog/category')->load($item);
                $children = $cat->getChildrenCategories();
                foreach ($children as $childId) {
                    $child = Mage::getModel('catalog/category')->load($childId->getId());
                    if(count($child->getChildrenCategories())){
                        foreach ($child->getChildrenCategories() as $childrenCategory) {
                            $child1 = Mage::getModel('catalog/category')->load($childrenCategory->getId());
                            if(count($child1->getChildrenCategories())){

                                foreach ($child1->getChildrenCategories() as $childrenCategory1) {
                                    $child2 = Mage::getModel('catalog/category')->load($childrenCategory1->getId());
                                    if(count($child2->getChildrenCategories())){
                                        foreach ($child2->getChildrenCategories() as $childrenCategory2) {
                                            $child3 = Mage::getModel('catalog/category')->load($childrenCategory2->getId());
                                            $cats[] = array(
                                                'value' => $child3->getId(),
                                                'label' => $cat->getName().' -> '.$child->getName().' -> '.$child1->getName().' -> '.$child2->getName().' -> '.$child3->getName()
                                            );
                                        }

                                    }else {
                                        $cats[] = array(
                                            'value' => $child2->getId(),
                                            'label' => $cat->getName().' -> '.$child->getName().' -> '.$child1->getName().' -> '.$child2->getName()
                                        );
                                    }
                                }
                            }else {
                                $cats[] = array(
                                    'value' => $child1->getId(),
                                    'label' => $cat->getName().' -> '.$child->getName().' -> '.$child1->getName()
                                );
                            }
                        }

                    }else {
                        $cats[] = array(
                            'value' => $child->getId(),
                            'label' => $cat->getName().' -> '.$child->getName()
                        );
                    }

                }
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


        $values = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToSelect('*');
        $values->addFieldToFilter('ivaecoid', array('notnull'=>true));
        $values->setOrder('ivaecoid', 'ASC');

        if($instructorId){
            $values->addFieldToFilter('entity_id', $instructorId);
        }

        $ins = array();
        foreach ($values as $value) {
            $cat = Mage::getModel('bs_instructor/instructor')->load($value->getId());
            if($cat){
                $ins[] = array(
                    'label' => $cat->getIname().' - '.$cat->getIvaecoid(),
                    'value' => $value->getId()
                );
            }

        }

        $fieldset->addField(
            'instructor_id',
            'select',
            array(
                'label'     => Mage::helper('bs_instructor')->__('Instructor'),
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
                'label' => Mage::helper('bs_instructor')->__('Approved Course'),
                'name'  => 'approved_course',

           )
        );

        $fieldset->addField(
            'approved_function',
            'text',
            array(
                'label' => Mage::helper('bs_instructor')->__('Approved Function'),
                'name'  => 'approved_function',

           )
        );

        $fieldset->addField(
            'approved_doc',
            'text',
            array(
                'label' => Mage::helper('bs_instructor')->__('Approved Doc'),
                'name'  => 'approved_doc',

            )
        );


        $fieldset->addField(
            'approved_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructor')->__('Approved Date'),
                'name'  => 'approved_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );
        $fieldset->addField(
            'expire_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructor')->__('Expire Date'),
                'name'  => 'expire_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );
        $fieldset->addField(
            'is_ti',
            'select',
            array(
                'label'  => Mage::helper('bs_instructor')->__('Is Theoretical Instructor?'),
                'name'   => 'is_ti',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructor')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructor')->__('No'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'is_te',
            'select',
            array(
                'label'  => Mage::helper('bs_instructor')->__('Is Theoretical Evaluator?'),
                'name'   => 'is_te',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructor')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructor')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'is_pi',
            'select',
            array(
                'label'  => Mage::helper('bs_instructor')->__('Is Practical Instructor?'),
                'name'   => 'is_pi',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructor')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructor')->__('No'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'is_pe',
            'select',
            array(
                'label'  => Mage::helper('bs_instructor')->__('Is Practical Evaluator?'),
                'name'   => 'is_pe',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructor')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructor')->__('No'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_instructor')->__('Note'),
                'name'  => 'note',

           )
        );

        $formValues = Mage::registry('current_instructorfunction')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getInstructorfunctionData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInstructorfunctionData());
            Mage::getSingleton('adminhtml/session')->setInstructorfunctionData(null);
        } elseif (Mage::registry('current_instructorfunction')) {
            $formValues = array_merge($formValues, Mage::registry('current_instructorfunction')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
