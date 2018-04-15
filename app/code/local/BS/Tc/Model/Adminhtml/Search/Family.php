<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Model_Adminhtml_Search_Family extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Tc_Model_Adminhtml_Search_Family
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_tc/family_collection')
            ->addAttributeToFilter('fname', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $family) {
            $arr[] = array(
                'id'          => 'family/1/'.$family->getId(),
                'type'        => Mage::helper('bs_tc')->__('Family Member'),
                'name'        => $family->getFname(),
                'description' => $family->getFname(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/tc_family/edit',
                    array('id'=>$family->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
