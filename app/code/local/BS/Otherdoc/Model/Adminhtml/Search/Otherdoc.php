<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Model_Adminhtml_Search_Otherdoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Otherdoc_Model_Adminhtml_Search_Otherdoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_otherdoc/otherdoc_collection')
            ->addFieldToFilter('otherdoc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $otherdoc) {
            $arr[] = array(
                'id'          => 'otherdoc/1/'.$otherdoc->getId(),
                'type'        => Mage::helper('bs_otherdoc')->__('Other\'s Course Document'),
                'name'        => $otherdoc->getOtherdocName(),
                'description' => $otherdoc->getOtherdocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/otherdoc_otherdoc/edit',
                    array('id'=>$otherdoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
