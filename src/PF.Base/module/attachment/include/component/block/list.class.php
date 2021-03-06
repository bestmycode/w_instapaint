<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Attachment_Component_Block_List extends Phpfox_Component
{
    /**
     * Controller
     */
    public function process()
    {
        $iId = (int)$this->getParam('iItemId');
        $sType = $this->getParam('sType');
        $aRows = $this->getParam('attachments', null);
        $bIsAttachmentEdit = (bool)$this->getParam('attachment_edit', false);
        $bIsAttachmentNoHeader = (bool)$this->getParam('attachment_no_header', false);

        if ($bIsAttachmentEdit) {
            list(, $aRows) = Phpfox::getService('attachment')->get('attachment.attachment_id IN(' . rtrim($this->getParam('sIds'),
                    ',') . ')', 'attachment.time_stamp ASC', false);
        } else {
            if (!is_array($aRows)) {
                list(, $aRows) = Phpfox::getService('attachment')->get("attachment.item_id = {$iId} AND attachment.view_id = 0 AND attachment.category_id = '" . Phpfox_Database::instance()->escape($sType) . "' " . ($bIsAttachmentNoHeader ? '' : 'AND attachment.is_inline = 0'), 'attachment.attachment_id DESC', false);
            }
        }

        $this->template()->assign([
            'aAttachments' => $aRows,
            'sUrlPath' => Phpfox::getParam('core.url_attachment'),
            'sUsage' => Phpfox::getUserBy('space_attachment'),
            'bIsAttachmentNoHeader' => $bIsAttachmentNoHeader,
            'bIsAttachmentEdit' => $bIsAttachmentEdit,
            'bIsGetAttachmentList' => $this->getParam('bGetAttachmentList', false)
        ]);

        (($sPlugin = Phpfox_Plugin::get('attachment.component_block_list_process')) ? eval($sPlugin) : false);
    }
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		$this->template()->clean(array(
			'aAttachments',
			'sUrlPath',
			'sUsage'
		));		
		
		(($sPlugin = Phpfox_Plugin::get('attachment.component_block_list_clean')) ? eval($sPlugin) : false);
	}
}