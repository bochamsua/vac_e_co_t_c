<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * module base admin controller
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Controller_Adminhtml_WorksheetDoc extends Mage_Adminhtml_Controller_Action
{
    /**
     * upload file and get the uploaded name
     *
     * @access public
     * @param string $input
     * @param string $destinationFolder
     * @param array $data
     * @return string
     * @author Bui Phong
     */
    protected function _uploadAndGetName($input, $destinationFolder, $data)
    {
        try {
            if (isset($data[$input]['delete'])) {
                return '';
            } else {
                $docType = Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdoctype')->getOptionFormatted($data['wsdoc_type']);

                $uploader = new Varien_File_Uploader($input);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder.'/'.$docType);
                return $result['file'];
            }
        } catch (Exception $e) {
            if ($e->getCode() != Varien_File_Uploader::TMP_NAME_EMPTY) {
                throw $e;
            } else {
                if (isset($data[$input]['value'])) {
                    return $data[$input]['value'];
                }
            }
        }
        return '';
    }
}
