<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Model_Adminhtml_Search_Subjectcontent extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Subject_Model_Adminhtml_Search_Subjectcontent
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_subject/subjectcontent_collection')
            ->addFieldToFilter('subcon_title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $subjectcontent) {
            $arr[] = array(
                'id'          => 'subjectcontent/1/'.$subjectcontent->getId(),
                'type'        => Mage::helper('bs_subject')->__('Subject Content'),
                'name'        => $subjectcontent->getSubconTitle(),
                'description' => $subjectcontent->getSubconTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/subject_subjectcontent/edit',
                    array('id'=>$subjectcontent->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
