<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Adminhtml_Search_Classroom extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Classroom
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/classroom_collection')
            ->addFieldToFilter('classroom_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $classroom) {
            $arr[] = array(
                'id'          => 'classroom/1/'.$classroom->getId(),
                'type'        => Mage::helper('bs_logistics')->__('Classroom/Examroom'),
                'name'        => $classroom->getClassroomName(),
                'description' => $classroom->getClassroomName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_classroom/edit',
                    array('id'=>$classroom->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
