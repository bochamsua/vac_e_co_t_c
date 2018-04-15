<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News edit form tab
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Block_Adminhtml_News_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_News_Block_Adminhtml_News_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('news_');
        $form->setFieldNameSuffix('news');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'news_form',
            array('legend' => Mage::helper('bs_news')->__('News'))
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('bs_news')->__('Title'),
                'name'  => 'title',

           )
        );

        $fieldset->addField(
            'short_description',
            'editor',
            array(
                'label' => Mage::helper('bs_news')->__('Short Decription'),
                'name'  => 'short_description',
                'config' => $wysiwygConfig,

            )
        );

        $fieldset->addField(
            'content',
            'editor',
            array(
                'label' => Mage::helper('bs_news')->__('Content'),
                'name'  => 'content',
            'config' => $wysiwygConfig,

           )
        );



        $fieldset->addField(
            'apply_for',
            'multiselect',
            array(
                'label' => Mage::helper('bs_news')->__('Receivers'),
                'name'  => 'apply_for',

                'values'=> Mage::getModel('bs_news/news_attribute_source_applyfor')->getAllOptions(false),
            )
        );


        $fieldset->addField(
            'close_text',
            'text',
            array(
                'label' => Mage::helper('bs_news')->__('Close Text'),
                'name'  => 'close_text',

           )
        );
        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_news')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_news')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_news')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_news')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getNewsData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getNewsData());
            Mage::getSingleton('adminhtml/session')->setNewsData(null);
        } elseif (Mage::registry('current_news')) {
            $formValues = array_merge($formValues, Mage::registry('current_news')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
