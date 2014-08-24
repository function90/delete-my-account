<?php
/**
 * @package 	Plugin Delete My Account for Joomla! 3.X
 * @version 	0.0.1
 * @author 		Function90.com
 * @copyright 	C) 2013- Function90.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemF90deletemyaccount extends JPlugin
{
	protected $autoloadLanguage = true;
	public function __construct($subject, $config = array())
	{
		parent::__construct($subject, $config);
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		$user = JFactory::getUser();
		if(!$user->id || ($app->get('offline') && !$user->authorise('core.login.offline'))){
			return true;
		}

		$this->loadLanguage();
		JText::script('PLG_SYSTEM_F90DELETEMYACCOUNT_ARE_YOU_SURE_MSG');
		JText::script('PLG_SYSTEM_F90DELETEMYACCOUNT_SUCCESS');
	}
	
	public function onBeforeRender()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		$user = JFactory::getUser();
		if(!$user->id || ($app->get('offline') && !$user->authorise('core.login.offline'))){
			return true;
		}
		
		$doc = JFactory::getDocument();
		
		$version = new JVersion();
		$major  = str_replace('.', '', $version->RELEASE);
		
		if($this->params->get('load_jquery', false)){
			$doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
		}
		
		if($major == '25' || $this->params->get('using_bootstrap', true) == false){ 
			$doc->addScript('plugins/'.$this->_type.'/'.$this->_name.'/js/dma25.js');
		}else{
			$doc->addScript('plugins/'.$this->_type.'/'.$this->_name.'/js/dma.js');
		}     
		
		return true;
	}
	
	public function onAfterRender()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		$user = JFactory::getUser();
		if(!$user->id || ($app->get('offline') && !$user->authorise('core.login.offline'))){
			return true;
		}

		$version = new JVersion();
		$major  = str_replace('.', '', $version->RELEASE);
		
		if($major == '25' || $this->params->get('using_bootstrap', true) == false){
			return true;	
		}
		
		ob_start();
		require_once dirname(__FILE__).'/tmpl/dma.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		$body = JResponse::getBody();
		$body = str_ireplace('</body>', $contents.'</body>', $body);
		JResponse::setBody($body);
		return true;
	}
	
	public function onAfterRoute()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		$user = JFactory::getUser();
		if(!$user->id || ($app->get('offline') && !$user->authorise('core.login.offline'))){
			return true;
		}
		
		$input = $app->input;
		if(!($input->get('option') === 'com_f90dma' && $input->get('task') === 'sendDeleteRequest')){
			return true;
		}
		
		$user = JFactory::getUser();
		if(!$user->id){
			echo '';
			exit();
		}
					
		ob_start();
		require_once dirname(__FILE__).'/tmpl/logout.php';
		$html = ob_get_contents();
		ob_end_clean();
		
		if($this->params->get('action', 0)){
			// delete users account	
			if($user->delete()){
				$session = JFactory::getSession();
				$session->set('user', null);
				$this->_sendEmail($user->name);
				echo json_encode(array('error' => false, 'html' => $html));
			}
			else{
				echo json_encode(array('error' => true, 'html' => JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_ERROR_IN_DELETING_USER')));
			}
			exit();
		}
		
		//else block user account
		$user->set('block', 1);
		if($user->save()){
			$this->_sendEmail($user->name);
			echo json_encode(array('error' => false, 'html' => $html));
		}
		else{
			echo json_encode(array('error' => true, 'html' => JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_ERROR_IN_DELETING_USER')));
		}
		exit();		
	}
	
	public function _sendEmail($username)
	{
		$config = JFactory::getConfig();
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		
		$emailSubject = JText::_('PLG_SYSTEM_F90DELETEMYACCOUNT_EMAIL_SUBJECT');
		$emailBodyAdmin = JText::sprintf(
				'PLG_SYSTEM_F90DELETEMYACCOUNT_EMAIL_BODY',
				$username
			);

		$recipient = $this->params->get('email', '');
		$recipient = explode(',', $recipient);
		
		if($this->params->get('system_email', true)){
			$db = JFactory::getDbo();
			// Get all admin users
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('name', 'email', 'sendEmail')))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('sendEmail') . ' = ' . 1);
	
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			
			// Send mail to all superadministrators id
			foreach ($rows as $row){
				$recipient[] = $row->email;
			}
		}
		
		$mail = JFactory::getMailer()
							->setSender(
										array(
												$data['mailfrom'],
												$data['fromname']
											)
										)
							->addRecipient($recipient)
							->setSubject($emailSubject)
							->setBody($emailBodyAdmin);
							
		if (!$mail->Send()) {
			
		}
		
		return true;
	}
}
