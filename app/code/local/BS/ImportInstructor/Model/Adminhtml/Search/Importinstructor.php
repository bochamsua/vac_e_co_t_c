<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Model_Adminhtml_Search_Importinstructor extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_ImportInstructor_Model_Adminhtml_Search_Importinstructor
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_importinstructor/importinstructor_collection')
            ->addFieldToFilter('curriculum_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $importinstructor) {
            $arr[] = array(
                'id'          => 'importinstructor/1/'.$importinstructor->getId(),
                'type'        => Mage::helper('bs_importinstructor')->__('Import Instructor'),
                'name'        => $importinstructor->getCurriculumId(),
                'description' => $importinstructor->getCurriculumId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/importinstructor_importinstructor/edit',
                    array('id'=>$importinstructor->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
