<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Model_Adminhtml_Search_Administrativedocument extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_AdministrativeDoc_Model_Adminhtml_Search_Administrativedocument
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_administrativedoc/administrativedocument_collection')
            ->addFieldToFilter('doc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $administrativedocument) {
            $arr[] = array(
                'id'          => 'administrativedocument/1/'.$administrativedocument->getId(),
                'type'        => Mage::helper('bs_administrativedoc')->__('Administrative Document'),
                'name'        => $administrativedocument->getDocName(),
                'description' => $administrativedocument->getDocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/administrativedoc_administrativedocument/edit',
                    array('id'=>$administrativedocument->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
