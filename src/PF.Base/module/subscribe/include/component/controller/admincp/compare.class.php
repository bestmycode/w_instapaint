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
class Subscribe_Component_Controller_Admincp_Compare extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{	

		if ( ($aVals = $this->request()->getArray('compare') ) )
		{
			unset($aVals['99999999']);
			if (Phpfox::getService('subscribe.process')->updateCompare($aVals) )
			{
				Phpfox::addMessage(_p('comparison_page_updated_successfully'));
			}
		}
	
	
		$aForCompare = Phpfox::getService('subscribe')->getPackagesForCompare(true);
		$aCompare = Phpfox::getService('subscribe')->getCompareArray();
		
		if (!empty($aCompare))
		{
			$this->template()->setHeader(array(
				'<script type="text/javascript">$Behavior.loadCompare = function(){$Core.subscribe.loadCompare(\''. json_encode($aCompare).'\', false);}</script>'
			));
		}
				
		$this->template()
			->setPhrase(array(
					'no_subscription_package_has_been_created_you_need_at_least_one_subscription_package',
					'add_a_feature'
				)
			)
		->setHeader(array(
			'compare.js' => 'module_subscribe',
			'compare.css' => 'module_subscribe'
		))
		->assign(array(
			'aPackages' => $aForCompare ,
			'bIsDisplay' => false,
			'iTotalColumns' => (count($aForCompare['packages'])+1)
		))
        ->setBreadCrumb(_p('Members'),'#')
        ->setBreadCrumb(_p('subscriptions'),$this->url()->makeUrl('admincp.subscribe'))
		->setBreadCrumb(_p('compare_subscription_packages'), $this->url()->makeUrl('admincp.subscribe.compare'))
        ->setActiveMenu('admincp.member.subscribe')
		->setTitle(_p('compare_subscription_packages'));
		
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