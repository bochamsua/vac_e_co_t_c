<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document file field renderer helper
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Helper_File extends Varien_Data_Form_Element_Abstract
{
    /**
     * constructor
     *
     * @access public
     * @param array $data
     * @author Bui Phong
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    /**
     * get element html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getElementHtml()
    {
        $doc = Mage::registry('current_worksheetdoc');
        $sub = Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdoctype')->getOptionFormatted($doc['wsdoc_type']);


        $html = '';
        $this->addClass('input-file');
        $html .= parent::getElementHtml();
        if ($this->getValue()) {
            $url = $this->_getUrl();
            if (!preg_match("/^http\:\/\/|https\:\/\//", $url)) {
                $url = Mage::helper('bs_worksheetdoc/worksheetdoc')->getFileBaseUrl() .'/'.$sub.'/'. $url;
            }
            $html .= '<br /><a href="'.$url.'">'.$this->_getUrl().'</a> ';
        }
        $html .= $this->_getDeleteCheckbox();
        return $html;
    }

    /**
     * get the delete checkbox HTML
     *
     * @access protected
     * @return string
     * @author Bui Phong
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $label = Mage::helper('bs_worksheetdoc')->__('Delete File');
            $html .= '<span class="delete-image">';
            $html .= '<input type="checkbox" name="'.
                parent::getName().'[delete]" value="1" class="checkbox" id="'.
                $this->getHtmlId().'_delete"'.($this->getDisabled() ? ' disabled="disabled"': '').'/>';
            $html .= '<label for="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' class="disabled"' : '').'>';
            $html .= $label.'</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }
        return $html;
    }

    /**
     * get the hidden input
     *
     * @access protected
     * @return string
     * @author Bui Phong
     */
    protected function _getHiddenInput()
    {
        return '<input type="hidden" name="'.parent::getName().'[value]" value="'.$this->getValue().'" />';
    }

    /**
     * get the file url
     *
     * @access protected
     * @return string
     * @author Bui Phong
     */
    protected function _getUrl()
    {
        return $this->getValue();
    }

    /**
     * get the name
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getName()
    {
        return $this->getData('name');
    }
}
