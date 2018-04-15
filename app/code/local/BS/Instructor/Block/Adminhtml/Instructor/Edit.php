<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin edit form
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_instructor';
        $this->_controller = 'adminhtml_instructor';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_instructor')->__('Save')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bs_instructor')->__('Delete')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_instructor')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                'class'   => 'save',
            ),
            -100
        );
        $this->_addButton(
            '8042',
            array(
                'label'   => Mage::helper('bs_instructor')->__('8042'),
                'onclick'   => "setLocation('{$this->getUrl('*/*/generateFortytwo', array('_current'=>true))}')",
                'class'   => 'reset',
            )
        );
        $this->_formScripts[] = "
            var templateSyntax = /(^|.|\\r|\\n)({{(\w+)}})/;
            function saveAndContinueEdit(urlTemplate) {
                 var template = new Template(urlTemplate, templateSyntax);
                 var url = template.evaluate({tab_id:instructor_info_tabsJsTabs.activeTab.id});
                 editForm.submit(url);
            }

            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName) {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    instructor_info_tabsJsTabs.setSkipDisplayFirstTab();
                    instructor_info_tabsJsTabs.showTabContent(obj);
                }

            });
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructor/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructor/delete");

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
        if (Mage::registry('current_instructor') && Mage::registry('current_instructor')->getId()) {
            return Mage::helper('bs_instructor')->__(
                "Edit Instructor '%s'",
                $this->escapeHtml(Mage::registry('current_instructor')->getIname())
            );
        } else {
            return Mage::helper('bs_instructor')->__('Add Instructor');
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
