<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * QA Car admin edit form
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Qacar_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_car';
        $this->_controller = 'adminhtml_qacar';

        $this->_addButton(
            'generate',
            array(
                'label'   => Mage::helper('bs_car')->__('Generate'),
                'onclick'   => 'setLocation(\''.$this->getUrl('*/*/generate', array('id' => $this->getRequest()->getParam($this->_objectId))).'\')',
                'class'   => 'reset',
            )
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_car')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
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
                    'label'   => Mage::helper('bs_car')->__('Close'),
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
                 if(typeof qacar_tabsJsTabs !== 'undefined'){
                    var url = template.evaluate({tab_id:qacar_tabsJsTabs.activeTab.id});
                 }else {
                    var url = template.evaluate({tab_id:qacar_info_tabsJsTabs.activeTab.id});
                 }

                 editForm.submit(url + '".$add."');

            }




            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName != '') {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    if(typeof qacar_tabsJsTabs !== 'undefined'){
                        qacar_tabsJsTabs.setSkipDisplayFirstTab();
                        qacar_tabsJsTabs.showTabContent(obj);
                     }else {
                        qacar_info_tabsJsTabs.setSkipDisplayFirstTab();
                        qacar_info_tabsJsTabs.showTabContent(obj);
                     }

                }

            });
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_car/qacar/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_car/qacar/delete");

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
        if (Mage::registry('current_qacar') && Mage::registry('current_qacar')->getId()) {
            return Mage::helper('bs_car')->__(
                "Edit QA Car '%s'",
                $this->escapeHtml(Mage::registry('current_qacar')->getCarNo())
            );
        } else {
            return Mage::helper('bs_car')->__('Add QA Car');
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
