<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin edit form
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_traininglist';
        $this->_controller = 'adminhtml_curriculum';
        $add = '';
        $params = array($this->_objectId => $this->getRequest()->getParam($this->_objectId));
        $backto = Mage::registry('current_curriculum')->getStatus();
        if(!$backto){
            $add = 'backto/new/';
            $back = array('backto'=>'new');
            $params = array_merge($params, $back);

            $this->_removeButton('back');

            $this->_addButton('backto', array(
                'label'     => Mage::helper('adminhtml')->__('Back'),
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/traininglist_curriculum_new/') . '\')',
                'class'     => 'back',
            ), -1);
        }

        $this->_addButton(
            'generate',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Generate Files'),
                'onclick'   => "generateConfirm()",
                'class'   => 'reset',
            )
        );



        $this->_addButton(
            'duplicate',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Duplicate'),
                'onclick'   => "duplicateConfirm()",
                'class'   => 'reset',
            )
        );
        $this->_addButton(
            'updateposition',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Update Subject Position'),
                'onclick'   => "updateConfirm()",
                'class'   => 'reset',
            )
        );
        $this->_addButton(
            'updatepositiondoc',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Doc Pos 99'),
                'onclick'   => "updateDocConfirm()",
                'class'   => 'reset',
            )
        );
//        $this->_addButton(
//            'preview',
//            array(
//                'label'   => Mage::helper('bs_traininglist')->__('Preview'),
//                'onclick'   => 'setLocation(\''. $this->getUrl('*/*/generateFiles', array_merge($params, array('preview'=>1))).'\')',
//                'class'   => 'save',
//            )
//        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                'class'   => 'save',
            ),
            -100
        );

        $this->_updateButton('save','onclick','saveOnly()');
        $this->_updateButton('delete','onclick','deleteOnly()');

        $this->_formScripts[] = "

            function deleteOnly() {
                deleteConfirm('". Mage::helper('adminhtml')->__('Are you sure you want to do this?')."','".$this->getUrl('*/*/delete', $params) . "');
            }
            function saveOnly() {
                editForm.submit($('edit_form').action+'".$add."');
            }

            function generateConfirm() {
                deleteConfirm('". Mage::helper('adminhtml')->__('Are you sure you want to do this?')."','".$this->getUrl('*/*/generateFiles', $params) . "');
            }

            function duplicateConfirm() {
                deleteConfirm('". Mage::helper('adminhtml')->__('This action should be taken VERY CAREFULLY. Are you sure you want to do this?')."','".$this->getUrl('*/*/duplicate', $params) . "');
            }

            function updateConfirm() {
                deleteConfirm('". Mage::helper('adminhtml')->__('This action should be taken VERY CAREFULLY. Are you sure you want to do this?')."','".$this->getUrl('*/*/updatePosition', $params) . "');
            }
            function updateDocConfirm() {
                deleteConfirm('". Mage::helper('adminhtml')->__('This action should be taken VERY CAREFULLY. Are you sure you want to do this?')."','".$this->getUrl('*/*/updateDocPosition', $params) . "');
            }
            function alertHistory(){
                alert('This is a historical version and you CAN NOT edit.');
            }


            var templateSyntax = /(^|.|\\r|\\n)({{(\w+)}})/;
            function saveAndContinueEdit(urlTemplate) {
                 var template = new Template(urlTemplate, templateSyntax);
                 var url = template.evaluate({tab_id:curriculum_info_tabsJsTabs.activeTab.id});
                 editForm.submit(url + '".$add."');
            }

            Event.observe(window, 'load', function() {
                var objName = '".$this->getSelectedTabId()."';
                if (objName) {
                    obj = $(objName);
                    //IE fix (bubbling event model)
                    curriculum_info_tabsJsTabs.setSkipDisplayFirstTab();
                    curriculum_info_tabsJsTabs.showTabContent(obj);
                }

            });
        ";




        //$history = false;
        $curriculum = Mage::registry('current_curriculum');
        if($curriculum->getCHistory()){
            //$history = true;
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
            //$this->_removeButton('delete');
            $this->_removeButton('reset');

            $this->_addButton(
                'notice',
                array(
                    'label'   => Mage::helper('bs_traininglist')->__('History version'),
                    'onclick' => 'alertHistory()',
                    'class'   => 'delete',
                ),
                -100
            );
        }



        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/delete");

        if(!$isAllowedEdit){
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
            $this->_removeButton('duplicate');
            $this->_removeButton('updateposition');
            $this->_removeButton('updatepositiondoc');
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
        if (Mage::registry('current_curriculum') && Mage::registry('current_curriculum')->getId()) {
            return Mage::helper('bs_traininglist')->__(
                "Edit Training Curriculum '%s'",
                $this->escapeHtml(Mage::registry('current_curriculum')->getCName())
            );
        } else {
            return Mage::helper('bs_traininglist')->__('Add Training Curriculum');
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
