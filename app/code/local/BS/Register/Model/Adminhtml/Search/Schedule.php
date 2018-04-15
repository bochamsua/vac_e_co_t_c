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
class BS_Register_Model_Adminhtml_Search_Schedule extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Register_Model_Adminhtml_Search_Schedule
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_register/schedule_collection')
            ->addFieldToFilter('schedule_note', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $schedule) {
            $arr[] = array(
                'id'          => 'schedule/1/'.$schedule->getId(),
                'type'        => Mage::helper('bs_register')->__('Course Schedule'),
                'name'        => $schedule->getScheduleNote(),
                'description' => $schedule->getScheduleNote(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/register_schedule/edit',
                    array('id'=>$schedule->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
