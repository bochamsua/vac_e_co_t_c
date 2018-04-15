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
class BS_Car_Model_Adminhtml_Search_Qacar extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Car_Model_Adminhtml_Search_Qacar
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_car/qacar_collection')
            ->addFieldToFilter('car_no', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $qacar) {
            $arr[] = array(
                'id'          => 'qacar/1/'.$qacar->getId(),
                'type'        => Mage::helper('bs_car')->__('QA Car'),
                'name'        => $qacar->getCarNo(),
                'description' => $qacar->getCarNo(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/car_qacar/edit',
                    array('id'=>$qacar->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
