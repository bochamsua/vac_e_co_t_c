<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor tab on product edit form
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Catalog_Product_Edit_Tab_Instructor extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('instructor_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_instructors'=>1));
        }
    }

    /**
     * prepare the instructor collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Catalog_Product_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {

        $productId = $this->getProduct()->getId();
        $curriculumId = null;

        if($productId){
            $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
            $curriculums->addProductFilter($productId);

            if($cu = $curriculums->getFirstItem()){
                $curriculumId = $cu->getId();
            }
        }

        $collection = Mage::getResourceModel('bs_instructor/instructor_collection')
            ->addAttributeToSelect('*');

        if($curriculumId){
            $collection->addCurriculumFilter($curriculumId);
        }


        ;
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_instructor/instructor_product')),
            'related.instructor_id=e.entity_id AND '.$constraint,
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
     * @return BS_Instructor_Block_Adminhtml_Catalog_Product_Edit_Tab_Instructor
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
     * @return BS_Instructor_Block_Adminhtml_Catalog_Product_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_instructors',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_instructors',
                'values'=> $this->_getSelectedInstructors(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'iname',
            array(
                'header' => Mage::helper('bs_instructor')->__('Instructor Name'),
                'align'  => 'left',
                'index'  => 'iname',
                'renderer' => 'bs_instructor/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/instructor_instructor/edit',
            )
        );

        $this->addColumn(
            'ivaecoid',
            array(
                'header' => Mage::helper('bs_instructor')->__('VAECO ID'),
                'align'  => 'left',
                'index'  => 'ivaecoid',
                'renderer' => 'bs_instructor/adminhtml_helper_column_renderer_instructor',

            )
        );

        $this->addColumn(
            'iphone',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Phone'),
                'align'     => 'left',
                'index'     => 'iphone',
            )
        );

        $this->addColumn(
            'idivision_department',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Division - Department'),
                'align'     => 'left',
                'index'     => 'idivision_department',
            )
        );




        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_instructor')->__('Position'),
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
     * Retrieve selected instructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedInstructors()
    {
        $instructors = $this->getProductInstructors();
        if (!is_array($instructors)) {
            $instructors = array_keys($this->getSelectedInstructors());
        }
        return $instructors;
    }

    /**
     * Retrieve selected instructors
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedInstructors()
    {
        $instructors = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_instructor/product')->getSelectedInstructors(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $instructor) {
            $instructors[$instructor->getId()] = array('position' => $instructor->getPosition());
        }
        return $instructors;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Instructor_Model_Instructor
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return $this;//->getUrl('*/register_schedule/new',array('product_id'=>$this->getProduct()->getId(),'instructor_id'=>$item->getId()));
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
            '*/*/instructorsGrid',
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
     * @return BS_Instructor_Block_Adminhtml_Catalog_Product_Edit_Tab_Instructor
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_instructors') {
            $instructorIds = $this->_getSelectedInstructors();
            if (empty($instructorIds)) {
                $instructorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$instructorIds));
            } else {
                if ($instructorIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$instructorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
