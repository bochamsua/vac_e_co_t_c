<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Adminhtml_Search_Filecabinet extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Filecabinet
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/filecabinet_collection')
            ->addFieldToFilter('filecabinet_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $filecabinet) {
            $arr[] = array(
                'id'          => 'filecabinet/1/'.$filecabinet->getId(),
                'type'        => Mage::helper('bs_logistics')->__('File Cabinet'),
                'name'        => $filecabinet->getFilecabinetName(),
                'description' => $filecabinet->getFilecabinetName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_filecabinet/edit',
                    array('id'=>$filecabinet->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
