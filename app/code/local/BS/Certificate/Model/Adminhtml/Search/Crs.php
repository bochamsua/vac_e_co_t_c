<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Model_Adminhtml_Search_Crs extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Certificate_Model_Adminhtml_Search_Crs
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_certificate/crs_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $crs) {
            $arr[] = array(
                'id'          => 'crs/1/'.$crs->getId(),
                'type'        => Mage::helper('bs_certificate')->__('CRS'),
                'name'        => $crs->getName(),
                'description' => $crs->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/certificate_crs/edit',
                    array('id'=>$crs->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
