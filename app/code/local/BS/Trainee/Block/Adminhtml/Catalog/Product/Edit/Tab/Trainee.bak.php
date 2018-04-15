<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee tab on product edit form
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Catalog_Product_Edit_Tab_Trainee extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('course_trainee_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_trainees'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_trainee_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Import Trainees'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/importtrainee_importtrainee/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('get_trainee_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Get Usernames'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/trainee_trainee/getusernames', array('_current'=>false, 'product_id'=>$this->getProduct()->getId())).'\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_trainee_button');
            $html.= $this->getChildHtml('get_trainee_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare the trainee collection
     *
     * @access protected
     * @return BS_Trainee_Block_Adminhtml_Catalog_Product_Edit_Tab_Trainee
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_trainee/trainee_collection')->addAttributeToSelect('*');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_trainee/trainee_product')),
            'related.trainee_id=e.entity_id AND '.$constraint,
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
     * @return BS_Trainee_Block_Adminhtml_Catalog_Product_Edit_Tab_Trainee
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
     * @return BS_Trainee_Block_Adminhtml_Catalog_Product_Edit_Tab_Trainee
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_trainees',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_trainees',
                'values'=> $this->_getSelectedTrainees(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'trainee_name',
            array(
                'header' => Mage::helper('bs_trainee')->__('Trainee Name'),
                'align'  => 'left',
                'index'  => 'trainee_name',
                'renderer' => 'bs_trainee/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/trainee_trainee/edit',
            )
        );
        $this->addColumn(
            'trainee_code',
            array(
                'header' => Mage::helper('bs_trainee')->__('Trainee ID'),
                'align'  => 'left',
                'index'  => 'trainee_code',

            )
        );
        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_trainee')->__('VAECO ID'),
                'align'  => 'left',
                'index'  => 'vaeco_id',

            )
        );

        $this->addColumn(
            'trainee_phone',
            array(
                'header' => Mage::helper('bs_trainee')->__('Phone'),
                'index'  => 'trainee_phone',
                'type'=> 'text',

            )
        );


        $this->addColumn(
            'document',
            array(
                'header' => Mage::helper('bs_trainee')->__(''),
                'renderer' => 'bs_trainee/adminhtml_helper_column_renderer_document',
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addColumn(
            'absence',
            array(
                'header' => Mage::helper('bs_trainee')->__(''),
                'renderer' => 'bs_trainee/adminhtml_helper_column_renderer_absence',
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_trainee')->__('Position'),
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
     * Retrieve selected trainees
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedTrainees()
    {
        $trainees = $this->getProductTrainees();
        if (!is_array($trainees)) {
            $trainees = array_keys($this->getSelectedTrainees());
        }
        return $trainees;
    }

    /**
     * Retrieve selected trainees
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedTrainees()
    {
        $trainees = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('bs_trainee/product')->getSelectedTrainees(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $trainee) {
            $trainees[$trainee->getId()] = array('position' => $trainee->getPosition());
        }
        return $trainees;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Trainee_Model_Trainee
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return $this;//->getUrl('*/register_attendance/new',array('product_id'=>$this->getProduct()->getId(),'trainee_id'=>$item->getId()));
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
            '*/*/traineesGrid',
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
     * @return BS_Trainee_Block_Adminhtml_Catalog_Product_Edit_Tab_Trainee
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_trainees') {
            $traineeIds = $this->_getSelectedTrainees();
            if (empty($traineeIds)) {
                $traineeIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$traineeIds));
            } else {
                if ($traineeIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$traineeIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
