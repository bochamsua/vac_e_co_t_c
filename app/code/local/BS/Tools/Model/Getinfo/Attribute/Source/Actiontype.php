<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Action
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Model_Getinfo_Attribute_Source_Actiontype extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * get possible values
     *
     * @access public
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     * @author Bui Phong
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        $options =  array(
            array(
                'label' => Mage::helper('bs_tools')->__('Get Username'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Get Basic Info'),
                'value' => 2
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Get Instructor Info'),
                'value' => 3
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Check Instructor Approved Courses'),
                'value' => 4
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('List only'),
                'value' => 5
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('List in Table'),
                'value' => 6
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Search by names'),
                'value' => 7
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Get Date (GE Courses)'),
                'value' => 8
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Get Attended Courses'),
                'value' => 9
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Get Place of Birth'),
                'value' => 10
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Get Phones from Usernames'),
                'value' => 11
            ),
            array(
                'label' => Mage::helper('bs_tools')->__('Check Train the Trainer'),
                'value' => 12
            ),array(
                'label' => Mage::helper('bs_tools')->__('Check CRS'),
                'value' => 13
            ),array(
                'label' => Mage::helper('bs_tools')->__('Check Conducted Courses'),
                'value' => 14
            ),array(
                'label' => Mage::helper('bs_tools')->__('Get Curriculum Hours'),
                'value' => 15
            ),

        );
        if ($withEmpty) {
            //array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;

    }

    /**
     * get options as array
     *
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Bui Phong
     */
    public function getOptionsArray($withEmpty = true)
    {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * get option text
     *
     * @access public
     * @param mixed $value
     * @return string
     * @author Bui Phong
     */
    public function getOptionText($value)
    {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }
}
