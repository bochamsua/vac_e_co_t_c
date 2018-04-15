<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Adminhtml_Search_Kstsubject extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_KST_Model_Adminhtml_Search_Kstsubject
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_kst/kstsubject_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $kstsubject) {
            $arr[] = array(
                'id'          => 'kstsubject/1/'.$kstsubject->getId(),
                'type'        => Mage::helper('bs_kst')->__('Subject'),
                'name'        => $kstsubject->getName(),
                'description' => $kstsubject->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/kst_kstsubject/edit',
                    array('id'=>$kstsubject->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
