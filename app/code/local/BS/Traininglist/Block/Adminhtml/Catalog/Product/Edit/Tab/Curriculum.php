<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum tab on product edit form
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Catalog_Product_Edit_Tab_Curriculum extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('curriculum_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_curriculums'=>1));
        }
    }

    /**
     * prepare the curriculum collection
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Product_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_traininglist/curriculum_collection')->addAttributeToSelect('c_name')->addAttributeToSelect('c_code')->addAttributeToFilter('c_history', 0)->addAttributeToFilter('status', 1);
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_traininglist/curriculum_product')),
            'related.curriculum_id=e.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Product_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Product_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_curriculums',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_curriculums',
                'values'=> $this->_getSelectedCurriculums(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'c_name',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Curriculum Name'),
                'align'  => 'left',
                'index'  => 'c_name',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/traininglist_curriculum/edit',
            )
        );
        $this->addColumn(
            'c_code',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Curriculum Code'),
                'align'  => 'left',
                'index'  => 'c_code',

            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_traininglist')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected curriculums
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedCurriculums()
    {
        $curriculums = $this->getProductCurriculums();
        if (!is_array($curriculums)) {
            $curriculums = array_keys($this->getSelectedCurriculums());
        }
        return $curriculums;
    }

    /**
     * Retrieve selected curriculums
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedCurriculums()
    {
        $curriculums = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_traininglist/product')->getSelectedCurriculums(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $curriculum) {
            $curriculums[$curriculum->getId()] = array('position' => $curriculum->getPosition());
        }
        return $curriculums;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/curriculumsGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Bui Phong
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Product_Edit_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_curriculums') {
            $curriculumIds = $this->_getSelectedCurriculums();
            if (empty($curriculumIds)) {
                $curriculumIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$curriculumIds));
            } else {
                if ($curriculumIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$curriculumIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
