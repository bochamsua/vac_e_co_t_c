<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content edit form tab
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subjectcontent_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('subjectcontent_');
        $form->setFieldNameSuffix('subjectcontent');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'subjectcontent_form',
            array('legend' => Mage::helper('bs_subject')->__('Subject Content'))
        );

        $currentSubContent = Mage::registry('current_subjectcontent');
        $subjectId = null;
        if($this->getRequest()->getParam('subject_id')){
            $subjectId = $this->getRequest()->getParam('subject_id');
        }elseif ($currentSubContent){
            $subjectId = $currentSubContent->getSubjectId();
        }


        $values = Mage::getResourceModel('bs_subject/subject_collection');
        if($subjectId){
            $values->addFieldToFilter('entity_id', $subjectId);
        }
        $values = $values->toFullOptionArray();


        $html = '<a href="{#url}" id="subjectcontent_subject_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeSubjectIdLink() {
                if ($(\'subjectcontent_subject_id\').value == \'\') {
                    $(\'subjectcontent_subject_id_link\').hide();
                } else {
                    $(\'subjectcontent_subject_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/subject_subject/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'subjectcontent_subject_id\').value);
                    $(\'subjectcontent_subject_id_link\').href = realUrl;
                    $(\'subjectcontent_subject_id_link\').innerHTML = text.replace(\'{#name}\', $(\'subjectcontent_subject_id\').options[$(\'subjectcontent_subject_id\').selectedIndex].innerHTML);
                }
            }
            $(\'subjectcontent_subject_id\').observe(\'change\', changeSubjectIdLink);
            changeSubjectIdLink();
            </script>';

        $popup = $this->getRequest()->getParam('popup', false);
        if($popup){
            $html = '';
        }

        $fieldset->addField(
            'subject_id',
            'select',
            array(
                'label'     => Mage::helper('bs_subject')->__('Subject'),
                'name'      => 'subject_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'subcon_title',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Content Title'),
                'name'  => 'subcon_title',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'subcon_code',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Code'),
                'name'  => 'subcon_code',
                'readonly'=> true,
                'note'  => 'This field is READ ONLY. It will be automatically generated'

            )
        );

        $fieldset->addField(
            'subcon_level',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Level'),
                'name'  => 'subcon_level',

           )
        );

        $fieldset->addField(
            'subcon_hour',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Hour'),
                'name'  => 'subcon_hour',

           )
        );

        $fieldset->addField(
            'subcon_content',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('Content'),
                'name'  => 'subcon_content',
                'note'  => 'If the content has multiple lines. Enter each in one line'
           )
        );

        $fieldset->addField(
            'subcon_order',
            'text',
            array(
                'label' => Mage::helper('bs_subject')->__('Sort Order'),
                'name'  => 'subcon_order',

           )
        );
        $fieldset->addField(
            'subcon_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('Remark'),
                'name'  => 'subcon_note',

            )
        );
        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_subject')->__('OR import from this?'),
                'name'  => 'import',
                'note'  => Mage::helper('bs_subject')->__('Name--level--hour'),

            )
        );

        $formValues = Mage::registry('current_subjectcontent')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getSubjectcontentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getSubjectcontentData());
            Mage::getSingleton('adminhtml/session')->setSubjectcontentData(null);
        } elseif (Mage::registry('current_subjectcontent')) {
            $formValues = array_merge($formValues, Mage::registry('current_subjectcontent')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
