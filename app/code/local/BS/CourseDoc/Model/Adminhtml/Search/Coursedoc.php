<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Model_Adminhtml_Search_Coursedoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_CourseDoc_Model_Adminhtml_Search_Coursedoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_coursedoc/coursedoc_collection')
            ->addFieldToFilter('course_doc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $coursedoc) {
            $arr[] = array(
                'id'          => 'coursedoc/1/'.$coursedoc->getId(),
                'type'        => Mage::helper('bs_coursedoc')->__('Course Document'),
                'name'        => $coursedoc->getCourseDocName(),
                'description' => $coursedoc->getCourseDocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/coursedoc_coursedoc/edit',
                    array('id'=>$coursedoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
