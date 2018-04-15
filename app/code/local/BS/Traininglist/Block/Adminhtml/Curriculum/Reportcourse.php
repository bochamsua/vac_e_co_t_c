<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin attribute block
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Reportcourse extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_curriculum_reportcourse';
        $this->_blockGroup = 'bs_traininglist';

        $add = '<table class="icons-header">
                                <tr>
                                    <td><img width="30" src="'.Mage::getBaseUrl('media').'images/finished.png" style="float: left; margin-right: 5px;"></td>
                                    <td>Finish today</td>
                                    <td>&nbsp;</td>
                                    <td><img width="30" src="'.Mage::getBaseUrl('media').'images/question.png" style="float: left; margin-right: 5px;"></td>
                                    <td>Documents!</td>
                                    <td>&nbsp;</td>
                                    <td><img width="30" src="'.Mage::getBaseUrl('media').'images/caution.png" style="float: left; margin-right: 5px;"></td>
                                    <td>Invalid Dispatch</td>
                                    <td>&nbsp;</td>
                                    <td><img width="30" src="'.Mage::getBaseUrl('media').'images/warning.png" style="float: left; margin-right: 5px;"></td>
                                    <td>Overdue!</td>
                                </tr>
                            </table>';

        $this->_headerText = Mage::helper('bs_traininglist')->__('Awaiting Report Course'.$add);
        parent::__construct();
        $this->_removeButton('add');
        $isAllowedAdd = Mage::getSingleton('admin/session')->isAllowed('catalog/products/new');
        if($isAllowedAdd){
            $this->_addButton('add_new', array(
                'label'   => Mage::helper('catalog')->__('Add Course'),
                'onclick' => "setLocation('{$this->getUrl('*/catalog_product/new', array('set'=>'4', 'type'=>'simple', 'from'=>'reportcourse'))}')",//set/4/type/simple/
                'class'   => 'add'
            ));
        }

    }
}
