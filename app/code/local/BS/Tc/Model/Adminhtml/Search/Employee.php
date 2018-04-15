<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Model_Adminhtml_Search_Employee extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Tc_Model_Adminhtml_Search_Employee
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_tc/employee_collection')
            ->addAttributeToFilter('ename', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $employee) {
            $arr[] = array(
                'id'          => 'employee/1/'.$employee->getId(),
                'type'        => Mage::helper('bs_tc')->__('Employee'),
                'name'        => $employee->getEname(),
                'description' => $employee->getEname(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/tc_employee/edit',
                    array('id'=>$employee->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
