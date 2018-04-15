<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin edit form
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_docwise';
        $this->_controller = 'adminhtml_exam';

        if(Mage::registry('current_exam')->getId()){
            $this->_addButton(
                '8006',
                array(
                    'label'   => Mage::helper('bs_docwise')->__('8006'),
                    'onclick'   => "setLocation('{$this->getUrl('*/*/generateSix', array('_current'=>true))}')",
                    'class'   => 'reset',
                )
            );
            $this->_addButton(
                '8009',
                array(
                    'label'   => Mage::helper('bs_docwise')->__('8009'),
                    'onclick'   => "setLocation('{$this->getUrl('*/*/generateNine', array('_current'=>true))}')",
                    'class'   => 'reset',
                )
            );

            $this->_addButton(
                '8011',
                array(
                    'label'   => Mage::helper('bs_docwise')->__('8011'),
                    'onclick'   => "setLocation('{$this->getUrl('*/*/generateEleven', array('_current'=>true))}')",
                    'class'   => 'reset',
                )
            );
        }



        $this->_updateButton('save','onclick','saveOnly(\''.$this->getSaveAndContinueUrl().'\')');
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
                    'label'   => Mage::helper('bs_docwise')->__('Close'),
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

            var templateSyntax = /(^|.|\\r|\\n)({{(\w+)}})/;
            function saveAndContinueEdit(urlTemplate) {
                 var template = new Template(urlTemplate, templateSyntax);
                 var url = template.evaluate({tab_id:exam_tabsJsTabs.activeTab.id});
                 editForm.submit(url);
            }

            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName) {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    exam_tabsJsTabs.setSkipDisplayFirstTab();
                    exam_tabsJsTabs.showTabContent(obj);
                }

            });
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/exam/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/exam/delete");


        if(!$isAllowedEdit){
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
        }
        if(!$isAllowedDelete){
            $this->_removeButton('delete');
        }
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
            return Mage::helper('bs_docwise')->__(
                "Edit Exam '%s'",
                $this->escapeHtml(Mage::registry('current_exam')->getExamCode())
            );
        } else {
            return Mage::helper('bs_docwise')->__('Add Exam');
        }
    }

    public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'tab'        => '{{tab_id}}',
            'active_tab' => null
        ));
    }
    public function getSelectedTabId()
    {
        return addslashes(htmlspecialchars($this->getRequest()->getParam('tab')));
    }
}
