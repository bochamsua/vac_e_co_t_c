<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Model_Adminhtml_Search_Subjectcopy extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_SubjectCopy_Model_Adminhtml_Search_Subjectcopy
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_subjectcopy/subjectcopy_collection')
            ->addFieldToFilter('c_from', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $subjectcopy) {
            $arr[] = array(
                'id'          => 'subjectcopy/1/'.$subjectcopy->getId(),
                'type'        => Mage::helper('bs_subjectcopy')->__('Subject Copy'),
                'name'        => $subjectcopy->getCFrom(),
                'description' => $subjectcopy->getCFrom(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/subjectcopy_subjectcopy/edit',
                    array('id'=>$subjectcopy->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
