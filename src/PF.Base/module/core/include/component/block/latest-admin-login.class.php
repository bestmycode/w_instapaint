<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: latest-admin-login.class.php 1629 2010-06-06 07:28:54Z Raymond_Benc $
 */
class Core_Component_Block_Latest_Admin_Login extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		$aLastAdmins = Phpfox::getService('core.admincp')->getLastAdminLogins();
		
		if (!Phpfox::getParam('core.admincp_do_timeout'))
		{
			return false;
		}
		
		if (!count($aLastAdmins))
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => _p('latest_admin_logins'),
				'aLastAdmins' => $aLastAdmins,
				'aFooter' => array(
					_p('view_more') => [
					    'link' => $this->url()->makeUrl('admincp.core.latest-admin-login'),
                        'class' => 'no_ajax'
                    ]
				)
			)
		);
		
		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('core.component_block_latest_admin_login_clean')) ? eval($sPlugin) : false);
	}
}