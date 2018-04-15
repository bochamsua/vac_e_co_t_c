<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Model_Adminhtml_Search_Formtemplate extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Formtemplate_Model_Adminhtml_Search_Formtemplate
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_formtemplate/formtemplate_collection')
            ->addFieldToFilter('template_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $formtemplate) {
            $arr[] = array(
                'id'          => 'formtemplate/1/'.$formtemplate->getId(),
                'type'        => Mage::helper('bs_formtemplate')->__('Form Template'),
                'name'        => $formtemplate->getTemplateName(),
                'description' => $formtemplate->getTemplateName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/formtemplate_formtemplate/edit',
                    array('id'=>$formtemplate->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
