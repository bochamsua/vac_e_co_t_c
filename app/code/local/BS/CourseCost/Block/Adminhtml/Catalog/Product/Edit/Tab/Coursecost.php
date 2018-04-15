<?php
class BS_CourseCost_Block_Adminhtml_Catalog_Product_Edit_Tab_Coursecost extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    protected $_defaultLimit = 50;
    public function __construct()
    {
        parent::__construct();
        $this->setId('coursecost_grid');
        $this->setDefaultSort('coursecost_order');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('add_cost_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Cost'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/coursecost_coursecost/new', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true, 'v2'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('clear_cost_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Clear All Costs'),
                    'onclick'   => 'deleteConfirm(\'This action should be taken VERY CAREFULLY. All Costs will be DELETED! Are you sure you want to do this?\',\''.$this->getUrl('*/coursecost_coursecost/clear', array('_current'=>false, 'product_id'=>$this->getProduct()->getId(),'popup'=>true)).'\')'
                ))
        );

        $this->setTemplate('bs_coursecost/grid.phtml');
        parent::_prepareLayout();
    }

    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_cost_button');

            $isAllowed = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/coursecost/delete");
            if($isAllowed){
                $html.= $this->getChildHtml('clear_cost_button');
            }

            //$html.= $this->getResetFilterButtonHtml();
            //$html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_coursecost/coursecost')
            ->getCollection();

        if ($this->getProduct()->getId()) {
            $collection->addFieldToFilter('course_id', $this->getProduct()->getId());
        }

        $this->setCollection($collection);
        parent::_prepareCollection();
        $this->_prepareTotals('coursecost_cost');

        return $this;
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $groupitems = array();
        $itemitems = array();
        $courseId = $this->getProduct()->getId();

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $groupIds = $readConnection->fetchCol("SELECT DISTINCT costgroup_id FROM bs_coursecost_coursecost WHERE course_id = ".$courseId);



        if(count($groupIds)){
            $groups = Mage::getModel('bs_coursecost/costgroup')->getCollection();
            $groups->addFieldToFilter('entity_id', array('in'=>$groupIds));
            $groupitems = $groups->toOptionHash();

            $items = Mage::getModel('bs_coursecost/costitem')->getCollection()->addFieldToFilter('costgroup_id', array('in'=>$groupIds));
            $itemitems = $items->toOptionHash();
        }

        $this->addColumn(
            'costgroup_id',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Cost Group'),
                'index'     => 'costgroup_id',
                'type'      => 'options',
                'options'   => $groupitems,
                'renderer'  => 'bs_coursecost/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getCostgroupId'
                ),
                'base_link' => 'adminhtml/coursecost_costgroup/edit',
                'totals_label'      => Mage::helper('bs_coursecost')->__('Total Cost')
            )
        );
        $this->addColumn(
            'costitem_id',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Group Items'),
                'index'     => 'costitem_id',
                'type'      => 'options',
                'options'   => $itemitems,
                'renderer'  => 'bs_coursecost/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getCostitemId'
                ),
                'base_link' => 'adminhtml/coursecost_costitem/edit'
            )
        );
        $this->addColumn(
            'qty',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Qty'),
                'align'     => 'left',
                'index'     => 'qty',
            )
        );

        $this->addColumn(
            'coursecost_cost',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Total Cost'),
                'index'     => 'coursecost_cost',
                'align'     => 'right'
            )
        );

        $this->addColumn(
            'note',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Note'),
                'index'     => 'note',
            )
        );
        $this->addColumn(
            'coursecost_order',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Position'),
                'align'     => 'left',
                'index'     => 'coursecost_order',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_coursecost/adminhtml_helper_column_renderer_input',
                'totals_label'      => ''
            )
        );





        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '100px',
                'type'      => 'text',
                'edit_link' => '*/coursecost_coursecost/edit/product_id/'.$this->getProduct()->getId(),
                'delete_link' => '*/coursecost_coursecost/delete',
                'allowed_edit'          => 'catalog/bs_coursecost/coursecost/edit',
                'allowed_delete'          => 'catalog/bs_coursecost/coursecost/delete',
                'grid_id'          => $this->getId().'JsObject',
                'renderer'  => 'bs_register/adminhtml_helper_column_renderer_act',
                'totals_label'      => '',
                'filter'    => false,
                'sortable'  => false
            )
        );

//        $this->addColumn(
//            'action',
//            array(
//                'header'  =>  Mage::helper('bs_coursecost')->__('Action'),
//                'width'   => '100',
//                'type'    => 'action',
//                'getter'  => 'getId',
//                'actions' => array(
//                    array(
//                        'caption' => Mage::helper('bs_coursecost')->__('Edit'),
//                        'url'     => array('base'=> '*/coursecost_coursecost/edit', 'popup' => true),
//                        'field'   => 'id'
//                    )
//                ),
//                'filter'    => false,
//                'is_system' => true,
//                'sortable'  => false,
//            )
//        );

        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
//        $this->setMassactionIdField('entity_id');
//        $this->getMassactionBlock()->setFormFieldName('coursecost');
//
//        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_coursecost/coursecost/edit");
//        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_coursecost/coursecost/delete");
//
//        if($isAllowedDelete){
//            $this->getMassactionBlock()->addItem(
//                'delete',
//                array(
//                    'label'=> Mage::helper('bs_coursecost')->__('Delete'),
//                    'url'  => $this->getUrl('*/*/massDelete'),
//                    'confirm'  => Mage::helper('bs_coursecost')->__('Are you sure?')
//                )
//            );
//        }
//
//        if($isAllowedEdit){
//            $this->getMassactionBlock()->addItem(
//                'status',
//                array(
//                    'label'      => Mage::helper('bs_coursecost')->__('Change status'),
//                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//                    'additional' => array(
//                        'status' => array(
//                            'name'   => 'status',
//                            'type'   => 'select',
//                            'class'  => 'required-entry',
//                            'label'  => Mage::helper('bs_coursecost')->__('Status'),
//                            'values' => array(
//                                '1' => Mage::helper('bs_coursecost')->__('Enabled'),
//                                '0' => Mage::helper('bs_coursecost')->__('Disabled'),
//                            )
//                        )
//                    )
//                )
//            );
//
//
//
//
//            $values = Mage::getResourceModel('bs_coursecost/costgroup_collection')->toOptionHash();
//            $values = array_reverse($values, true);
//            $values[''] = '';
//            $values = array_reverse($values, true);
//            $this->getMassactionBlock()->addItem(
//                'costgroup_id',
//                array(
//                    'label'      => Mage::helper('bs_coursecost')->__('Change Manage Cost Group'),
//                    'url'        => $this->getUrl('*/*/massCostgroupId', array('_current'=>true)),
//                    'additional' => array(
//                        'flag_costgroup_id' => array(
//                            'name'   => 'flag_costgroup_id',
//                            'type'   => 'select',
//                            'class'  => 'required-entry',
//                            'label'  => Mage::helper('bs_coursecost')->__('Manage Cost Group'),
//                            'values' => $values
//                        )
//                    )
//                )
//            );
//            $values = Mage::getResourceModel('bs_coursecost/costitem_collection')->toOptionHash();
//            $values = array_reverse($values, true);
//            $values[''] = '';
//            $values = array_reverse($values, true);
//            $this->getMassactionBlock()->addItem(
//                'costitem_id',
//                array(
//                    'label'      => Mage::helper('bs_coursecost')->__('Change Manage Group Items'),
//                    'url'        => $this->getUrl('*/*/massCostitemId', array('_current'=>true)),
//                    'additional' => array(
//                        'flag_costitem_id' => array(
//                            'name'   => 'flag_costitem_id',
//                            'type'   => 'select',
//                            'class'  => 'required-entry',
//                            'label'  => Mage::helper('bs_coursecost')->__('Manage Group Items'),
//                            'values' => $values
//                        )
//                    )
//                )
//            );
//        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_CourseCost_Model_Coursecost
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return false;//$this->getUrl('*/coursecost_coursecost/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/coursecostsGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    protected function _prepareTotals($columns = 'coursecost_cost'){
        $columns=explode(',',$columns);
        if(!$columns){
            return;
        }
        $this->_countTotals = true;
        $totals = new Varien_Object();
        $fields = array();
        foreach($columns as $column){
            $fields[$column]    = 0;
        }

        foreach ($this->getCollection() as $item) {
            foreach($fields as $field=>$value){
                $fields[$field]+=$item->getData($field);
            }
        }

        $totals->setData($fields);
        $this->setTotals($totals);
        return;
    }
}
