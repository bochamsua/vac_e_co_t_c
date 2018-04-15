<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family model
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Model_Family extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_tc_family';
    const CACHE_TAG = 'bs_tc_family';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_tc_family';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'family';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_tc/family');
    }

    /**
     * before save family
     *
     * @access protected
     * @return BS_Tc_Model_Family
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save family relation
     *
     * @access public
     * @return BS_Tc_Model_Family
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Tc_Model_Employee
     * @author Bui Phong
     */
    public function getParentEmployee()
    {
        if (!$this->hasData('_parent_employee')) {
            if (!$this->getEmployeeId()) {
                return null;
            } else {
                $employee = Mage::getModel('bs_tc/employee')->setStoreId(Mage::app()->getStore()->getId())
                    ->load($this->getEmployeeId());
                if ($employee->getId()) {
                    $this->setData('_parent_employee', $employee);
                } else {
                    $this->setData('_parent_employee', null);
                }
            }
        }
        return $this->getData('_parent_employee');
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Bui Phong
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Bui Phong
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
