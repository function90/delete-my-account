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
	<p><?php echo JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_SUCCESS');?></p>
	
	<form action="index.php" method="post" id="f90dma-login-form" class="form-vertical">
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
<?php 
