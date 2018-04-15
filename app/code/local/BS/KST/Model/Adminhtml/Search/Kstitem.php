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
class BS_KST_Model_Adminhtml_Search_Kstitem extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_KST_Model_Adminhtml_Search_Kstitem
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_kst/kstitem_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $kstitem) {
            $arr[] = array(
                'id'          => 'kstitem/1/'.$kstitem->getId(),
                'type'        => Mage::helper('bs_kst')->__('Item'),
                'name'        => $kstitem->getName(),
                'description' => $kstitem->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/kst_kstitem/edit',
                    array('id'=>$kstitem->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
