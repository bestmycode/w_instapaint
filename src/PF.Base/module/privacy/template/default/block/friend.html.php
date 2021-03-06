<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: friend.html.php 6997 2013-12-18 15:14:49Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !$bNoCustomDiv}
<div id="js_custom_friend_list">
{/if}
	{if count($aLists)}
	<div class="error_message" style="display:none;" id="js_temp_privacy_error_message">
		{_p var='select_a_custom_friends_list_if_you_want_to_add_privacy_to_your_item'}
	</div>	
	{if $iNewListId > 0}
	<div class="message">
		{_p var='custom_friends_list_successfully_created'}
	</div>
	{/if}
	<div id="js_custom_list_actual_holder">
		<form class="form" method="post" action="#" onsubmit="return $Core.updateCustomList(this);">
			{_p var='select_from_your_custom_friends_list'}
			<div class="p_top_4">
				<select id="custom_list" name="custom_list[]" multiple="multiple" size="6" style="width:300px;" class="js_custom_list_option form-control" autofocus>
				{foreach from=$aLists item=aList}
					<option id="pcl_{$aList.list_id}" value="{$aList.list_id}"{if $iNewListId > 0 && $iNewListId == $aList.list_id} selected="selected"{/if}>{$aList.name|clean}</option>
				{/foreach}
				</select>
			</div>
			<div class="p_top_4">
				<input type="submit" class="btn btn-primary" value="{_p var='save'}" /> <a href="#" onclick="$('#js_create_custom_friend_list_holder').show(); $('#js_custom_list_actual_holder').hide(); return false;">{_p var='or_create_a_new_list'}</a>
			</div>
		</form>
	</div>
	
	<script type="text/javascript">
		var sCustomPrivacyId = '#js_custom_privacy_input_holder';			
		var sPrivacyArray = '{$sPrivacyArray}';		
		{if !empty($sCustomPrivacyId)}
		sCustomPrivacyId = '#{$sCustomPrivacyId}';
		{/if}			
	
	{literal}
	
	$(sCustomPrivacyId + ' .privacy_list_array').each(function()
	{
		if ($('.js_custom_list_option #pcl_' + this.value).length > 0)
		{
			$('.js_custom_list_option #pcl_' + this.value).attr('selected', 'selected');
		}
	});
	
	$Core.updateCustomList = function($oObj)
	{	
		$('#js_temp_privacy_error_message').hide();
		var $iCnt = 0;
		$('.js_custom_list_option option').each(function()
		{			
			if (this.selected)
			{
				$iCnt++;
			}
		});
		
		if (!$iCnt)
		{
			$('#js_temp_privacy_error_message').show();
		}
		else
		{
			$(sCustomPrivacyId).html('');
			var aList = [];
			$($oObj).find('.js_custom_list_option option').each(function(){				
				if (this.selected){
					$(sCustomPrivacyId).append('<div><input type="hidden" name="val' + (empty(sPrivacyArray) ? '' : '[' + sPrivacyArray + ']') + '[privacy_list][]" value="' + this.value + '" class="privacy_list_array" /></div>');
					aList.push(this.value);
				}
			});
			if ($('#photo_id').length > 0)
			{
				$.ajaxCall('privacy.addItemToFriendsLists', $.param({lists: aList, item_id: $('#photo_id').val(), module: 'photo'}));
			}
			tb_remove();
		}
		
		return false;
	}
	</script>
	{/literal}
	{/if}
	
	<div id="js_create_custom_friend_list_holder"{if count($aLists)} style="display:none;"{/if}>
		<div id="js_create_custom_friend_list">
			{if !count($aLists)}
			{_p var='you_have_not_created_a_custom_friends_list_yet'}
			{else}
			{_p var='create_a_new_friends_list_to_fully_control_your_contents_privacy'}
			{/if}
			<div style="margin-top:10px;">
				<div id="js_custom_search_friend_holder"></div>
				<form class="form" method="post" action="#" onsubmit="return false;">
					<div class="form-group">
						<input type="text" name="name" class="form-control" placeholder="{_p var='create_new_list'}" maxlength="255" size="15" id="js_add_new_list" style="vertical-align:middle;" required/> </div>
					<div class="form-group">
						<input type="submit" value="{_p var='add'}" class="btn btn-primary" onclick="if ($('#js_add_new_list').val() != '') $('#js_add_new_list').ajaxCall('friend.addList', 'custom=true'); " />
					</div>
				</form>		
			</div>
		</div>
		
		<div id="js_add_friends_to_list" style="display:none;">
			{_p var='add_friends_to_your_custom_list_below'}
			<div style="margin-top:10px;">
				<form class="form" method="post" action="#" onsubmit="return false;">
					<div><input type="hidden" name="list_id" value="" id="js_custom_friend_list_id" /></div>
					{if !empty($sCustomPrivacyId)}
					<div><input type="hidden" name="custom-id" value="{$sCustomPrivacyId}" /></div>
					{/if}
					<div>
                        {module name='friend.search-small' input_name='friends'}
					</div>
					<div>
						<div id="js_custom_search_friend_placement"></div>
						<div id="js_custom_search_submit_button" class="p_top_4 t_right">
							<input type="button" class="btn btn-primary" value="{_p var='save'}" onclick="$(this).parents('form').ajaxCall('friend.addFriendsToList');" />
						</div>
					</div>
					<div class="clear"></div>
				</form>
			</div>
		</div>
	</div>

{if !$bNoCustomDiv}
</div>
{/if}