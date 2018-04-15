<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Model_Adminhtml_Search_Questionnaire extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Questionnaire_Model_Adminhtml_Search_Questionnaire
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_questionnaire/questionnaire_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $questionnaire) {
            $arr[] = array(
                'id'          => 'questionnaire/1/'.$questionnaire->getId(),
                'type'        => Mage::helper('bs_questionnaire')->__('Questionnaire'),
                'name'        => $questionnaire->getName(),
                'description' => $questionnaire->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/questionnaire_questionnaire/edit',
                    array('id'=>$questionnaire->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
