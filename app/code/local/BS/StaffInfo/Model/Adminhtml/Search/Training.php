<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Model_Adminhtml_Search_Training extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_StaffInfo_Model_Adminhtml_Search_Training
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_staffinfo/training_collection')
            ->addFieldToFilter('course', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $training) {
            $arr[] = array(
                'id'          => 'training/1/'.$training->getId(),
                'type'        => Mage::helper('bs_staffinfo')->__('Related Training'),
                'name'        => $training->getCourse(),
                'description' => $training->getCourse(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/staffinfo_training/edit',
                    array('id'=>$training->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
