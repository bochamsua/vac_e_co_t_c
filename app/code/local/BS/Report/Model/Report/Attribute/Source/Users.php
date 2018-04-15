<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Complete (%)
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Model_Report_Attribute_Source_Users extends Mage_Eav_Model_Entity_Attribute_Source_Table
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
        $customers = Mage::getModel('admin/user')->getCollection()
            ->addFieldToFilter('user_id', array('nin'=>array(13,16,29)))
            ->addFieldToFilter('user_id', array('lt'=>38))

            //->addAttributeToFilter('position', array(array('like'=>'%đốc%'), array('like'=>'%phòng%'), array('like'=>'%tổ%')))
        ;
        $options = array();
        if(count($customers)){
            foreach ($customers as $cus) {
                $customer = Mage::getModel('admin/user')->load($cus->getId());
                $options[] = array(
                    'label' => $customer->getName(),
                    'value' => $customer->getId()
                );
            }


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
