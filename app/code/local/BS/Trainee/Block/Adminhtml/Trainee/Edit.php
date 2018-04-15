<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee admin edit form
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_trainee';
        $this->_controller = 'adminhtml_trainee';
        /*$this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_trainee')->__('Save Trainee')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bs_trainee')->__('Delete Trainee')
        );*/
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_trainee')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            var templateSyntax = /(^|.|\\r|\\n)({{(\w+)}})/;
            function saveAndContinueEdit(urlTemplate) {
                 var template = new Template(urlTemplate, templateSyntax);
                 var url = template.evaluate({tab_id:trainee_info_tabsJsTabs.activeTab.id});
                 editForm.submit(url);
            }

            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName) {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    trainee_info_tabsJsTabs.setSkipDisplayFirstTab();
                    trainee_info_tabsJsTabs.showTabContent(obj);
                }

            });
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/trainee/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/trainee/delete");

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
        if (Mage::registry('current_trainee') && Mage::registry('current_trainee')->getId()) {
            return Mage::helper('bs_trainee')->__(
                "Edit Trainee '%s'",
                $this->escapeHtml(Mage::registry('current_trainee')->getTraineeName())
            );
        } else {
            return Mage::helper('bs_trainee')->__('Add Trainee');
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
