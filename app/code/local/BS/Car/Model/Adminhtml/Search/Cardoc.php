<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Model_Adminhtml_Search_Cardoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Car_Model_Adminhtml_Search_Cardoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_car/cardoc_collection')
            ->addFieldToFilter('doc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $cardoc) {
            $arr[] = array(
                'id'          => 'cardoc/1/'.$cardoc->getId(),
                'type'        => Mage::helper('bs_car')->__('CAR Document'),
                'name'        => $cardoc->getDocName(),
                'description' => $cardoc->getDocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/car_cardoc/edit',
                    array('id'=>$cardoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
