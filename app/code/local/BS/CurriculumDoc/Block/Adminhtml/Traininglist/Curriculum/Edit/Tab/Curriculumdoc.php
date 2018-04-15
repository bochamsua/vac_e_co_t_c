<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum Document tab on curriculum edit form
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Curriculumdoc extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('curriculumdoc_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getCurriculum()->getId()) {
            $this->setDefaultFilter(array('in_curriculumdocs'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_curriculumdoc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add New Document'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/curriculumdoc_curriculumdoc/new', array('_current'=>false, 'curriculum_id'=>$this->getCurriculum()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('showpage',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Show Pages'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/traininglist_curriculum/edit', array( 'id'=>$this->getCurriculum()->getId(),'back'=>'edit/tab/curriculum_info_tabs_curriculumdocs','showpage'=>true)).'\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_curriculumdoc_button');
            $html.= $this->getChildHtml('showpage');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();

        }
        return $html;

    }
    /**
     * prepare the curriculumdoc collection
     *
     * @access protected
     * @return BS_CurriculumDoc_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Curriculumdoc
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_curriculumdoc/curriculumdoc_collection');
        if ($this->getCurriculum()->getId()) {
            $constraint = 'related.curriculum_id='.$this->getCurriculum()->getId();
        } else {
            $constraint = 'related.curriculum_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_curriculumdoc/curriculumdoc_curriculum')),
            'related.curriculumdoc_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $collection->setOrder('position', 'ASC');
        $this->setCollection($collection);
        parent::_prepareCollection();

        $this->_prepareTotals('totals');
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_CurriculumDoc_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Curriculumdoc
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
     * @return BS_CurriculumDoc_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Curriculumdoc
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_curriculumdocs',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_curriculumdocs',
                'values'=> $this->_getSelectedCurriculumdocs(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        /*$this->addColumn(
            'cdoc_name',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'cdoc_name',
                'renderer' => 'bs_curriculumdoc/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/curriculumdoc_curriculumdoc/edit',
            )
        );*/

        $this->addColumn(
            'cdoc_name',
            array(
                'header'         => Mage::helper('bs_curriculumdoc')->__('Document Name'),
                'name'           => 'cdoc_name',
                'index'  => 'cdoc_name',
                //'editable'       => true,
                'type'          => 'text',
                //'renderer'      => 'bs_curriculumdoc/adminhtml_helper_column_renderer_input',
                //'filter'    => false,
                'totals_label'      => Mage::helper('bs_curriculumdoc')->__('Total pages')
            )
        );

        $this->addColumn(
            'cdoc_type',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Document Type'),
                'index'  => 'cdoc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_curriculumdoc')->convertOptions(
                    Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'cdoc_date',
            array(
                'header' => Mage::helper('bs_material')->__('Approved/Revised Date'),
                'index'  => 'cdoc_date',
                'type'  => 'date',
            )
        );

        $this->addColumn(
            'cdoc_page',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Number of Pages'),
                'name'     => 'cdoc_page',
                'index'     => 'cdoc_page',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_curriculumdoc/adminhtml_helper_column_renderer_page',
                'filter'    => false,
                'totals_label'      => ''


            )
        );

        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_curriculumdoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_curriculumdoc/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
                'totals_label'      => ''
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_curriculumdoc')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
                'totals_label'      => ''
            )
        );
        $this->addColumn(
            'totals',
            array(
                'header' => Mage::helper('bs_curriculumdoc')->__('Total Pages'),
                'index'     => 'totals',

            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_curriculumdoc')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_curriculumdoc')->__('Edit'),
                        'url'     => array('base'=> '*/curriculumdoc_curriculumdoc/edit','params' => array('popup'=> 1, 'curriculum_id' => $this->getCurriculum()->getId())),
                        'field'   => 'id',
                        'onclick'   => 'window.open(this.href,\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'totals_label'      => ''
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected curriculumdocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedCurriculumdocs()
    {
        $curriculumdocs = $this->getCurriculumCurriculumdocs();
        if (!is_array($curriculumdocs)) {
            $curriculumdocs = array_keys($this->getSelectedCurriculumdocs());
        }
        return $curriculumdocs;
    }

    /**
     * Retrieve selected curriculumdocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedCurriculumdocs()
    {
        $curriculumdocs = array();
        //used helper here in order not to override the curriculum model
        $selected = Mage::helper('bs_curriculumdoc/curriculum')->getSelectedCurriculumdocs(Mage::registry('current_curriculum'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $curriculumdoc) {
            $curriculumdocs[$curriculumdoc->getId()] = array('position' => $curriculumdoc->getPosition());
        }
        return $curriculumdocs;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_CurriculumDoc_Model_Curriculumdoc
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
            '*/*/curriculumdocsGrid',
            array(
                'id'=>$this->getCurriculum()->getId()
            )
        );
    }

    /**
     * get the current curriculum
     *
     * @access public
     * @return Mage_Catalog_Model_Curriculum
     * @author Bui Phong
     */
    public function getCurriculum()
    {
        return Mage::registry('current_curriculum');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_CurriculumDoc_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Curriculumdoc
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_curriculumdocs') {
            $curriculumdocIds = $this->_getSelectedCurriculumdocs();
            if (empty($curriculumdocIds)) {
                $curriculumdocIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$curriculumdocIds));
            } else {
                if ($curriculumdocIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$curriculumdocIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareTotals($columns = 'cdoc_page'){
        $columns=explode(',','totals');
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
            $fields['totals']+=$item->getData('cdoc_page');
        }

        $totals->setData($fields);
        $this->setTotals($totals);
        return;
    }
}
