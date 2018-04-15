<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Adminhtml_Search_Docwisement extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Docwise_Model_Adminhtml_Search_Docwisement
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_docwise/docwisement_collection')
            ->addFieldToFilter('doc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $docwisement) {
            $arr[] = array(
                'id'          => 'docwisement/1/'.$docwisement->getId(),
                'type'        => Mage::helper('bs_docwise')->__('Docwise Document'),
                'name'        => $docwisement->getDocName(),
                'description' => $docwisement->getDocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/docwise_docwisement/edit',
                    array('id'=>$docwisement->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
