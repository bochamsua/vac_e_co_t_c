<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum tab on category edit form
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Catalog_Category_Tab_Curriculum extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    protected $_defaultLimit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_curriculum');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_curriculums'=>1));
        }
    }

    /**
     * get current category
     *
     * @access public
     * @return Mage_Catalog_Model_Category|null
     * @author Bui Phong
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }

    /**
     * prepare the collection
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Category_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_traininglist/curriculum_collection')
            ->addAttributeToSelect('c_name')
            ->addAttributeToSelect('c_code')
            ->addAttributeToSelect('c_rev')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('c_history', 0);

        if ($this->getCategory()->getId()) {
            $collection->joinField('position',
                'bs_traininglist/curriculum_category',
                'position',
                'curriculum_id=entity_id',
                'category_id='.$this->getCategory()->getId(),
                'left');
        }
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare the columns
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Category_Tab_Curriculum
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_curriculums',
            array(
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_curriculums',
                'values' => $this->_getSelectedCurriculums(),
                'align'  => 'center',
                'index'  => 'entity_id'
            )
        );
        /*$this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Id'),
                'type'   => 'number',
                'align'  => 'left',
                'index'  => 'entity_id',
            )
        );*/
        $this->addColumn(
            'c_name',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Course Name'),
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
                'header' => Mage::helper('bs_traininglist')->__('Course Code'),
                'align'  => 'left',
                'index'  => 'c_code',

            )
        );
        $this->addColumn(
            'c_rev',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Revision'),
                'index'  => 'c_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_rev')->getSource()->getAllOptions(false)
                )

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
                //'renderer'      => 'bs_traininglist/adminhtml_helper_column_renderer_curriculuminput',
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
        $curriculums = $this->getCategoryCurriculums();
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
        //used helper here in order not to override the category model
        $selected = Mage::helper('bs_traininglist/category')->getSelectedCurriculums(Mage::registry('current_category'));
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
            'adminhtml/traininglist_curriculum_catalog_category/curriculumsgrid',
            array(
                'id'=>$this->getCategory()->getId()
            )
        );
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Traininglist_Block_Adminhtml_Catalog_Category_Tab_Curriculum
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

    protected function _afterToHtml($html)
    {
        return parent::_afterToHtml($html);
    }
}
