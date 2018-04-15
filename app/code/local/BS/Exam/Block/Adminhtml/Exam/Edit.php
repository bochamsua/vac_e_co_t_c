<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin edit form
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Exam_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'bs_exam';
        $this->_controller = 'adminhtml_exam';

        $isAllowedNine = Mage::getSingleton('admin/session')->isAllowed("bs_exam/generate/nine");
        $isAllowedTen = Mage::getSingleton('admin/session')->isAllowed("bs_exam/generate/ten");
        $isAllowedEleven = Mage::getSingleton('admin/session')->isAllowed("bs_exam/generate/eleven");


        if($this->getExam()->getId()){
            if($isAllowedNine){
                $this->_addButton(
                    'generate_nine',
                    array(
                        'label'   => Mage::helper('bs_exam')->__('8009'),
                        'onclick'   => "setLocation('{$this->getUrl('*/traininglist_curriculum/generateNine', array('_current'=>true))}')",
                        'class'   => 'reset',
                    )
                );
            }
            if($isAllowedTen){
                $this->_addButton(
                    'generate_ten',
                    array(
                        'label'   => Mage::helper('bs_exam')->__('8010'),
                        'onclick'   => "setLocation('{$this->getUrl('*/traininglist_curriculum/generateTen', array('_current'=>true))}')",
                        'class'   => 'reset',
                    )
                );
            }

            if($isAllowedEleven){
                $this->_addButton(
                    'generate_eleven',
                    array(
                        'label'   => Mage::helper('bs_exam')->__('8011'),
                        'onclick'   => "setLocation('{$this->getUrl('*/traininglist_curriculum/generateEleven', array('_current'=>true))}')",
                        'class'   => 'reset',
                    )
                );
            }
        }


        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_exam')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_updateButton('save','onclick','saveOnly()');
        $this->_updateButton('delete','onclick','deleteOnly()');
        $add = '';
        $popup = $this->getRequest()->getParam('popup');
        if($popup){
            $add = 'popup/1/';
            $this->_removeButton('saveandcontinue');
            $this->_removeButton('back');
            $this->_addButton(
                'closewindow',
                array(
                    'label'   => Mage::helper('bs_exam')->__('Close'),
                    'onclick' => 'window.close()',
                    'class'   => 'back',
                ),
                -1
            );
        }
        $this->_formScripts[] = "

            function deleteOnly() {
                deleteConfirm('". Mage::helper('adminhtml')->__('Are you sure you want to do this?')."','".$this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'popup'=>1)) . "');
            }
            function saveOnly() {
                editForm.submit($('edit_form').action+'".$add."');
            }

            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";


        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/exam/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/exam/delete");

        if(!$isAllowedEdit){
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
        }
        if(!$isAllowedDelete){
            $this->_removeButton('delete');
        }
    }



    public function getExam()
    {
        return Mage::registry('current_exam');
    }
    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_exam') && Mage::registry('current_exam')->getId()) {
            return Mage::helper('bs_exam')->__(
                "Edit Exam '%s'",
                $this->escapeHtml(Mage::registry('current_exam')->getExamContent())
            );
        } else {
            return Mage::helper('bs_exam')->__('Add Exam');
        }
    }
}
