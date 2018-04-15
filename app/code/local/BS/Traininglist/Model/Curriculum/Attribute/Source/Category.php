<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Course Type
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Curriculum_Attribute_Source_Category extends Mage_Eav_Model_Entity_Attribute_Source_Table
{

    public function toOptionArray($addEmpty = true)
    {
        $options = array();
        foreach ($this->load_tree() as $category) {
            $options[$category['value']] =  $category['label'];
        }

        return $options;
    }



    public function buildCategoriesMultiselectValues(Varien_Data_Tree_Node $node, $values, $level = 0)
    {
        $level++;

        $values[$node->getId()]['value'] =  $node->getId();
        $values[$node->getId()]['label'] = str_repeat("--", $level) . $node->getName();

        foreach ($node->getChildren() as $child)
        {
            $values = $this->buildCategoriesMultiselectValues($child, $values, $level);
        }

        return $values;
    }

    public function load_tree()
    {

        $parentId = 2;  // Current store root category

        $tree = Mage::getResourceSingleton('catalog/category_tree')->load();

        $root = $tree->getNodeById($parentId);



        $collection = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('is_active');

        $tree->addCollectionData($collection, true);

        return $this->buildCategoriesMultiselectValues($root, array());
    }


}
