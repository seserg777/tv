<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Seasons list controller class.
 *
 * @package     Tvshows
 * @subpackage  Controllers
 */
class TvshowsControllerSeasons extends JControllerAdmin
{
	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'seasons';
	
	/**
	 * Get the admin model and set it to default
	 *
	 * @param   string           $name    Name of the model.
	 * @param   string           $prefix  Prefix of the model.
	 * @param   array			 $config  The model configuration.
	 */
	public function getModel($name = 'Season', $prefix='TvshowsModel', $config = array()) {
		$config['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function search(){
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$search = $jinput->get('search', null, 'STIRING');
		
		$return = false;
		
		if($search){
			$model = $this->getModel('Seasons', 'TvshowsModel');
			$model->setState('filter.search', 'title:'.$search);
			$list = $model->search();
			
			$return['list'] = $list;
		}
		
		echo json_encode( array('data' => $return) );
		$app->close();
	}
	
	function sendEmail($recipient, $subject, $body, $is_html = false){
		try
		{
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			if($recipient == 'admin'){
				$recipient = $config->get( 'mailfrom' );
			}
			$sender = array($config->get( 'mailfrom' ), $config->get( 'fromname' ));
			$mailer->setSender($sender);
			$mailer->addRecipient($recipient);
			$mailer->setSubject($subject);
			if($is_html){
				$mailer->isHtml(true);
				$mailer->Encoding = 'base64';
			}
			$mailer->setBody($body);
			$send = $mailer->Send();
		}
		catch (Exception $e) 
		{
			return $e->getMessage();
		} 
		
		return true;
	}
	
	public function reportbroken(){
		JSession::checkToken() or die( 'Invalid Token' );
		
		require_once JPATH_ROOT.'/components/com_tvshows/helpers/season.php';
		
		$app = jFactory::getApplication();
		$jinput = $app->input;
		
		$ajax = $jinput->get('ajax', null, 'INT');
		$g_recaptcha_response = $jinput->get('g-recaptcha-response', null, 'STRING');
		$url = $jinput->get('url', null, 'STRING');
		$name = $jinput->get('name', null, 'STRING');
		$email = $jinput->get('email', null, 'STRING');
		$comment = $jinput->get('comment', null, 'STRING');
		$season_id = $jinput->get('season_id', null, 'INT');
		$btns = $jinput->get('btns', null, 'STRING');
		
		if(TvshowsHelperSeason::checkCaptcha($g_recaptcha_response)){
			$recipient = 'admin';
			$subject = 'Report broken link';
			$body = '';
			$is_html = true;
		
			if(isset($name) && !empty($name)){
				$body .= '<p><strong>User name: </strong>'.$name.'</p>';
			}
			if(isset($email) && !empty($email)){
				$body .= '<p><strong>User e-mail: </strong>'.$email.'</p>';
			}
			if(isset($comment) && !empty($comment)){
				$body .= '<p><strong>User comment: </strong>'.$comment.'</p>';
			}
		
			$body .= '<p>'.$url.'</p>';
			$body .= '<p>You can <a href="'.jUri::base().'/administrator/index.php?option=com_tvshows&view=season&layout=edit&id='.$season_id.'">edit links here</a></p>';
		
			if(isset($btns) && !empty($btns)){
				$btns = json_decode($btns);
				if(count($btns)){
					$body .= '<h3>List broken links:</h3>';
					foreach($btns as $btn){
						$body .= '<p><strong>'.$btn->anchor.'</strong></p>';
						$body .= '<p>'.base64_decode($btn->url).'</p>';
					}
				}
			}
			
			$send = $this->sendEmail($recipient, $subject, $body, $is_html);
			if($send == true){
				if($ajax){
					echo new JResponseJson($send, 'Thank you for your message');
					$app->close();
				}
			} else {
				if($ajax){
					echo new JResponseJson(false, $send);
					$app->close();
				}
			}
		} else {
			echo new JResponseJson(false, 'Fill in captcha!');
			$app->close();
		}
	}
	
	public function checkCaptcha(){
		require_once JPATH_ROOT.'/components/com_tvshows/helpers/season.php';
		$app = jFactory::getApplication();
		$jinput = $app->input;
		$token = $jinput->get('token', null, 'STRING');
		$validate = TvshowsHelperSeason::checkCaptcha($token);
		if($validate){
			echo new JResponseJson($validate);
		} else {
			echo new JResponseJson($validate, 'Fill in captcha!', true);
		}
		$app->close();
	}
	
	public function verify(){
		require_once JPATH_ROOT.'/components/com_tvshows/helpers/season.php';
		$app = jFactory::getApplication();
		$jinput = $app->input;
		$token = $jinput->get('token', null, 'STRING');
		$validate = TvshowsHelperSeason::checkCaptchaV3($token);
		if($validate){
			echo new JResponseJson($validate);
		} else {
			echo new JResponseJson($validate, 'Fill in captcha!', true);
		}
		$app->close();
	}
} ?>