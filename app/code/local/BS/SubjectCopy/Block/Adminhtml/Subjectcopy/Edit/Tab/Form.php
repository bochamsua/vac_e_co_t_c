<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Copy edit form tab
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('subjectcopy_');
        $form->setFieldNameSuffix('subjectcopy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'subjectcopy_form',
            array('legend' => Mage::helper('bs_subjectcopy')->__('Subject Copy'))
        );

        $currentSubjectCopy = Mage::registry('current_subjectcopy');
        $cFromId = null;
        $familiar = false;
        if($this->getRequest()->getParam('familiar')){
            $familiar = true;
        }

        if($this->getRequest()->getParam('c_from')){
            $cFromId = $this->getRequest()->getParam('c_from');
        }elseif ($currentSubjectCopy){
            $cFromId = $currentSubjectCopy->getCFrom();
        }

        $cToId = null;
        if($this->getRequest()->getParam('c_to')){
            $cToId = $this->getRequest()->getParam('c_to');
        }elseif ($currentSubjectCopy){
            $cToId = $currentSubjectCopy->getCTo();
        }



        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection');
        if($cFromId){
            $values->addFieldToFilter('entity_id', $cFromId);
        }else {


            if($familiar){

                $include = array();
                if($cToId){
                    $cuTo = Mage::getModel('bs_traininglist/curriculum')->load($cToId);
                    $cuToCode = $cuTo->getCCode();
                    $cuToCode = explode("-", $cuToCode);
                    $check = array('A320/321', 'A330', 'A350', 'B777', 'B787', 'ATR72', 'F70');
                    foreach ($cuToCode as $code) {
                        if(!in_array($code, $check)){
                            $include[] = $code;
                        }
                    }

                }

                $like = array();
                foreach ($include as $in) {
                    $like[] = array('like'=>'%'.$in.'%');
                }

                $values->addAttributeToFilter('c_code',$like);

            }



        }
        $values = $values->toOptionArray();



        $fieldset->addField(
            'c_from',
            'select',
            array(
                'label'     => Mage::helper('bs_subject')->__('Copy from Curriculum'),
                'name'      => 'c_from',
                'required'  => true,
                'values'    => $values,
                'after_element_html' => ''
            )
        );




        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection');
        if($cToId){
            $values->addFieldToFilter('entity_id', $cToId);
        }
        $values = $values->toOptionArray();



        $fieldset->addField(
            'c_to',
            'select',
            array(
                'label'     => Mage::helper('bs_subject')->__('Copy to Curriculum'),
                'name'      => 'c_to',
                'required'  => true,
                'values'    => $values,
                'after_element_html' => ''
            )
        );

        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('OR import from this content'),
                'name'  => 'import',
                'note'  => 'Format: Title, level, hour. Currently only support simple subject (without content or subcontent), useful for Type Training courses.'

            )
        );



        $fieldset->addField(
            'include_sub',
            'select',
            array(
                'label' => Mage::helper('bs_subjectcopy')->__('Include Subcontent'),
                'name'  => 'include_sub',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_subjectcopy')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_subjectcopy')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'replace_all',
            'select',
            array(
                'label' => Mage::helper('bs_subjectcopy')->__('Replace All Existing Subjects'),
                'name'  => 'replace_all',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_subjectcopy')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_subjectcopy')->__('No'),
                ),
            ),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_subjectcopy')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_subjectcopy')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_subjectcopy')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_subjectcopy')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getSubjectcopyData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getSubjectcopyData());
            Mage::getSingleton('adminhtml/session')->setSubjectcopyData(null);
        } elseif (Mage::registry('current_subjectcopy')) {
            $formValues = array_merge($formValues, Mage::registry('current_subjectcopy')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
