<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress admin edit form
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstprogress_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_kst';
        $this->_controller = 'adminhtml_kstprogress';


        $this->_updateButton('save','onclick','saveOnly()');
        $this->_updateButton('save','label','Update');
        $this->_updateButton('delete','onclick','deleteOnly()');
        $this->_removeButton('back');
        $this->_removeButton('reset');
        $add = '';
        $popup = $this->getRequest()->getParam('popup');
        if($popup){
            $add = 'popup/1/';
            $this->_removeButton('saveandcontinue');

            $this->_addButton(
                'closewindow',
                array(
                    'label'   => Mage::helper('bs_kst')->__('Close'),
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
                 if(typeof kstprogress_tabsJsTabs !== 'undefined'){
                    var url = template.evaluate({tab_id:kstprogress_tabsJsTabs.activeTab.id});
                 }else {
                    var url = template.evaluate({tab_id:kstprogress_info_tabsJsTabs.activeTab.id});
                 }

                 editForm.submit(url + '".$add."');

            }




            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName != '') {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    if(typeof kstprogress_tabsJsTabs !== 'undefined'){
                        kstprogress_tabsJsTabs.setSkipDisplayFirstTab();
                        kstprogress_tabsJsTabs.showTabContent(obj);
                     }else {
                        kstprogress_info_tabsJsTabs.setSkipDisplayFirstTab();
                        kstprogress_info_tabsJsTabs.showTabContent(obj);
                     }

                }

            });
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstprogress/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstprogress/delete");

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
        if (Mage::registry('current_kstprogress') && Mage::registry('current_kstprogress')->getId()) {
            return Mage::helper('bs_kst')->__(
                "Edit Progress '%s'",
                $this->escapeHtml(Mage::registry('current_kstprogress')->getAcReg())
            );
        } else {
            return Mage::helper('bs_kst')->__('Update Progress');
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
