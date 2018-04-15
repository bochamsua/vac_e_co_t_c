<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Model_Adminhtml_Search_Handovertwo extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Handover_Model_Adminhtml_Search_Handovertwo
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_handover/handovertwo_collection')
            ->addFieldToFilter('course_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $handovertwo) {
            $arr[] = array(
                'id'          => 'handovertwo/1/'.$handovertwo->getId(),
                'type'        => Mage::helper('bs_handover')->__('Minutes of Handover V2'),
                'name'        => $handovertwo->getCourseId(),
                'description' => $handovertwo->getCourseId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/handover_handovertwo/edit',
                    array('id'=>$handovertwo->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
