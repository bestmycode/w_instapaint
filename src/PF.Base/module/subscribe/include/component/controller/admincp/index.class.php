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
 * @version 		$Id: controller.class.php 103 2009-01-27 11:32:36Z Raymond_Benc $
 */
class Subscribe_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		if (($iDeleteId = $this->request()->getInt('delete')))
		{
			if (Phpfox::getService('subscribe.process')->delete($iDeleteId))
			{
				$this->url()->send('admincp.subscribe', null, _p('package_successfully_deleted'));
			}
		}				
		
		$this->template()->setTitle(_p('subscription_packages'))
            ->setBreadCrumb(_p('Members'),'#')
			->setBreadCrumb(_p('subscription_packages'), $this->url()->makeUrl('admincp.subscribe'))
            ->setActiveMenu('admincp.member.subscribe')
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'subscribe.ordering\'}); }</script>'
				)
			)
			->assign(array(
					'aPackages' => Phpfox::getService('subscribe')->getForAdmin()
				)
			);		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('subscribe.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}