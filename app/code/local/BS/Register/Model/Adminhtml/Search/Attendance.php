<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Model_Adminhtml_Search_Attendance extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Register_Model_Adminhtml_Search_Attendance
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_register/attendance_collection')
            ->addFieldToFilter('att_note', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $attendance) {
            $arr[] = array(
                'id'          => 'attendance/1/'.$attendance->getId(),
                'type'        => Mage::helper('bs_register')->__('Absence Record'),
                'name'        => $attendance->getAttNote(),
                'description' => $attendance->getAttNote(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/register_attendance/edit',
                    array('id'=>$attendance->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
