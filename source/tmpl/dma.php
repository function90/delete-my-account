<?php
/**
 * @package 	Plugin Delete My Account for Joomla! 3.X
 * @version 	0.0.1
 * @author 		Function90.com
 * @copyright 	C) 2013- Function90.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="f90-delete-my-account-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_ARE_YOU_SURE');?></h3>
	</div>

	<div class="modal-body">
		<p><?php echo JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_ARE_YOU_SURE_MSG');?></p>
	</div>

	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_CLOSE');?></button>
		<button id="dma-send-request" class="btn btn-primary" data-loading-text="Sending request..." onclick="dma.delete_request(); return false;"><?php echo JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_BUTTON');?></button>
		
	</div>
	</div>
	</div>
</div>
<?php 
