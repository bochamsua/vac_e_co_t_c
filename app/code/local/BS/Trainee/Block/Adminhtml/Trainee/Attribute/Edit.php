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
 * Trainee attribute edit block
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee_Attribute_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_objectId = 'attribute_id';
        $this->_controller = 'adminhtml_trainee_attribute';
        $this->_blockGroup = 'bs_trainee';

        parent::__construct();
        $this->_addButton(
            'save_and_edit_button',
            array(
                'label'     => Mage::helper('bs_trainee')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save'
            ),
            100
        );
        /*$this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_trainee')->__('Save Trainee Attribute')
        );*/
        $this->_updateButton('save', 'onclick', 'saveAttribute()');

        if (!Mage::registry('entity_attribute')->getIsUserDefined()) {
            $this->_removeButton('delete');
        } /*else {
            $this->_updateButton(
                'delete',
                'label',
                Mage::helper('bs_trainee')->__('Delete Trainee Attribute')
            );
        }*/
    }

    /**
     * get the header text for the form
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getHeaderText()
    {
        if (Mage::registry('entity_attribute')->getId()) {
            $frontendLabel = Mage::registry('entity_attribute')->getFrontendLabel();
            if (is_array($frontendLabel)) {
                $frontendLabel = $frontendLabel[0];
            }
            return Mage::helper('bs_trainee')->__('Edit Trainee Attribute "%s"', $this->escapeHtml($frontendLabel));
        } else {
            return Mage::helper('bs_trainee')->__('New Trainee Attribute');
        }
    }

    /**
     * get validation url for form
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    /**
     * get save url for form
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/'.$this->_controller.'/save', array('_current'=>true, 'back'=>null));
    }
}
