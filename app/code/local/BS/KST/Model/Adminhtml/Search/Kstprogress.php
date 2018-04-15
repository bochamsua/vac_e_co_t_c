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
class BS_KST_Model_Adminhtml_Search_Kstprogress extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_KST_Model_Adminhtml_Search_Kstprogress
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_kst/kstprogress_collection')
            ->addFieldToFilter('ac_reg', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $kstprogress) {
            $arr[] = array(
                'id'          => 'kstprogress/1/'.$kstprogress->getId(),
                'type'        => Mage::helper('bs_kst')->__('Progress'),
                'name'        => $kstprogress->getAcReg(),
                'description' => $kstprogress->getAcReg(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/kst_kstprogress/edit',
                    array('id'=>$kstprogress->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
