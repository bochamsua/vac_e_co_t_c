<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Model_Adminhtml_Search_Traineecert extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_TraineeCert_Model_Adminhtml_Search_Traineecert
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_traineecert/traineecert_collection')
            ->addFieldToFilter('cert_no', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $traineecert) {
            $arr[] = array(
                'id'          => 'traineecert/1/'.$traineecert->getId(),
                'type'        => Mage::helper('bs_traineecert')->__('Trainee Certificate'),
                'name'        => $traineecert->getCertNo(),
                'description' => $traineecert->getCertNo(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/traineecert_traineecert/edit',
                    array('id'=>$traineecert->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
