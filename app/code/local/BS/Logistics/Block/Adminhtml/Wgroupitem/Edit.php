<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group Item admin edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wgroupitem_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_logistics';
        $this->_controller = 'adminhtml_wgroupitem';

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                'class'   => 'save',
            ),
            -100
        );

        $this->_addButton(
            '2063',
            array(
                'label'   => Mage::helper('bs_worksheet')->__('Tool List'),
                'onclick'   => "setLocation('{$this->getUrl('*/*/generateToolList', array('_current'=>true))}')",
                'class'   => 'reset',
            )
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
                    'label'   => Mage::helper('bs_logistics')->__('Close'),
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

            var templateSyntax = /(^|.|\\r|\\n)({{(\w+)}})/;

            function saveAndContinueEdit(urlTemplate) {
                 var template = new Template(urlTemplate, templateSyntax);
                 if(typeof wgroupitem_tabsJsTabs !== 'undefined'){
                    var url = template.evaluate({tab_id:wgroupitem_tabsJsTabs.activeTab.id});
                 }else {
                    var url = template.evaluate({tab_id:wgroupitem_info_tabsJsTabs.activeTab.id});
                 }

                 editForm.submit(url + '".$add."');

            }




            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName != '') {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    if(typeof wgroupitem_tabsJsTabs !== 'undefined'){
                        wgroupitem_tabsJsTabs.setSkipDisplayFirstTab();
                        wgroupitem_tabsJsTabs.showTabContent(obj);
                     }else {
                        wgroupitem_info_tabsJsTabs.setSkipDisplayFirstTab();
                        wgroupitem_info_tabsJsTabs.showTabContent(obj);
                     }

                }

            });
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/wgroupitem/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/wgroupitem/delete");

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
        if (Mage::registry('current_wgroupitem') && Mage::registry('current_wgroupitem')->getId()) {
            return Mage::helper('bs_logistics')->__(
                "Edit Container '%s'",
                $this->escapeHtml(Mage::registry('current_wgroupitem')->getName())
            );
        } else {
            return Mage::helper('bs_logistics')->__('Add Equipment');
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
