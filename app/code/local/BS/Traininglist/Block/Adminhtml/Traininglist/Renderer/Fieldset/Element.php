<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * fieldset element renderer
 * @category   BS
 * @package    BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Traininglist_Renderer_Fieldset_Element extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{
    /**
     * Initialize block template
     *
     * @access protected
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->setTemplate('bs_traininglist/form/renderer/fieldset/element.phtml');
    }

    /**
     * Retrieve data object related with form
     *
     * @access public
     * @return mixed
     * @author Bui Phong
     */
    public function getDataObject()
    {
        return $this->getElement()->getForm()->getDataObject();
    }

    /**
     * Retrieve associated with element attribute object
     *
     * @access public
     * @return BS_Traininglist_Model_Resource_Eav_Attribute
     * @author Bui Phong
     */
    public function getAttribute()
    {
        return $this->getElement()->getEntityAttribute();
    }

    /**
     * Retrieve associated attribute code
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getAttributeCode()
    {
        return $this->getAttribute()->getAttributeCode();
    }

    /**
     * Check "Use default" checkbox display availability
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */
    public function canDisplayUseDefault()
    {
        if ($attribute = $this->getAttribute()) {
            if (!$this->isScopeGlobal($attribute)
                && $this->getDataObject()
                && $this->getDataObject()->getId()
                && $this->getDataObject()->getStoreId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check default value usage fact
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */
    public function usedDefault()
    {
        $defaultValue = $this->getDataObject()->getAttributeDefaultValue($this->getAttribute()->getAttributeCode());
        return !$defaultValue;
    }

    /**
     * Disable field in default value using case
     *
     * @access public
     * @return BS_Traininglist_Block_Adminhtml_Traininglist_Renderer_Fieldset_Element
     * @author Bui Phong
     */
    public function checkFieldDisable()
    {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }

    /**
     * Retrieve label of attribute scope
     * GLOBAL | WEBSITE | STORE
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getScopeLabel()
    {
        $html = '';
        $attribute = $this->getElement()->getEntityAttribute();
        if (!$attribute || Mage::app()->isSingleStoreMode()) {
            return $html;
        }
        if ($this->isScopeGlobal($attribute)) {
            $html.= Mage::helper('bs_traininglist')->__('[GLOBAL]');
        } elseif ($this->isScopeWebsite($attribute)) {
            $html.= Mage::helper('bs_traininglist')->__('[WEBSITE]');
        } elseif ($this->isScopeStore($attribute)) {
            $html.= Mage::helper('bs_traininglist')->__('[STORE VIEW]');
        }
        return $html;
    }

    /**
     * Retrieve element label html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getElementLabelHtml()
    {
        return $this->getElement()->getLabelHtml();
    }

    /**
     * Retrieve element html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getElementHtml()
    {
        return $this->getElement()->getElementHtml();
    }

    /**
     * check if an attribute is global
     *
     * @access public
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     * @author Bui Phong
     */
    public function isScopeGlobal($attribute)
    {
        return $attribute->getIsGlobal() == 1;
    }

    /**
     * check if an attribute has website scope
     *
     * @access public
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     * @author Bui Phong
     */
    public function isScopeWebsite($attribute)
    {
        return $attribute->getIsGlobal() == 2;
    }

    /**
     * check if an attribute has store view scope
     *
     * @access public
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     * @author Bui Phong
     */
    public function isScopeStore($attribute)
    {
        return !$this->isScopeGlobal($attribute) && !$this->isScopeWebsite($attribute);
    }
}
