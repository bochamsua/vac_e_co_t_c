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
class BS_Docwise_Model_Adminhtml_Search_Trainee extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Docwise_Model_Adminhtml_Search_Trainee
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_docwise/trainee_collection')
            ->addFieldToFilter('trainee_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $trainee) {
            $arr[] = array(
                'id'          => 'trainee/1/'.$trainee->getId(),
                'type'        => Mage::helper('bs_docwise')->__('Trainee'),
                'name'        => $trainee->getTraineeName(),
                'description' => $trainee->getTraineeName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/docwise_trainee/edit',
                    array('id'=>$trainee->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
