<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
$session = JFactory::getSession();
$validate = $session->get('keycaptcha');
if(isset($validate) && $validate == 'validate'){
	header('Location: '.base64_decode($this->pad).'?site='.JFactory::getURI()->getHost());
}?>
<style>
.btn {display: inline-block;margin-bottom: 0;font-weight: normal;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;}
.btn:hover, .btn:focus, .btn.focus {color: #333;text-decoration: none;}
.main-btn {cursor:pointer;display: block;width: 100%;background: #0cc568;background: -moz-linear-gradient(top,rgba(12,197,104,1) 0,rgba(1,174,87,1) 100%);background: -webkit-linear-gradient(top,rgba(12,197,104,1) 0,rgba(1,174,87,1) 100%);background: linear-gradient(to bottom,rgba(12,197,104,1) 0,rgba(1,174,87,1) 100%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0cc568',endColorstr='#01ae57',GradientType=0);color: #fff !important;border-radius: 50px;font-size: 14px;font-family: "GothaPro Black","GothaPro",Arial,sans-serif;padding: 9px 10px;position: relative;text-align: center;-webkit-box-shadow: 0 10px 25px 0 rgba(0,0,0,.25);-moz-box-shadow: 0 10px 25px 0 rgba(0,0,0,.25);box-shadow: 0 10px 25px 0 rgba(0,0,0,.25);border: 0;outline: 0;}
.main-btn:hover {color: #fff; -webkit-box-shadow: 0 10px 25px 0 rgba(0,0,0,.3); -moz-box-shadow: 0 10px 25px 0 rgba(0,0,0,.3);box-shadow: 0 10px 25px 0 rgba(0,0,0,.3);}
.ps-season.pad {display:flex;align-items:center;justify-content:center;height:100%;}
.ps-season.pad .film-info .main-title .btn {     display: inline-block;     width: auto;     padding-left: 60px;     padding-right: 60px; }
.ps-season.pad .film-info .main-title .btn:hover {background: #0cc568;     background: -moz-linear-gradient(top,rgba(12,197,104,1) 0,rgba(1,174,87,1) 100%);     background: -webkit-linear-gradient(top,rgba(12,197,104,1) 0,rgba(1,174,87,1) 100%);     background: linear-gradient(to bottom,rgba(12,197,104,1) 0,rgba(1,174,87,1) 100%);     filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0cc568',endColorstr='#01ae57',GradientType=0);     color: #fff !important;}
</style>
<div class="ps-season pad">
	<div class="film-info">
		<div class="main-title">
			<form method="POST" action="">
				<?php if (!class_exists('KeyCAPTCHA_CLASS')) {
					include_once (JPATH_ROOT.'/components/com_tvshows/lib/keycaptcha.php'); 
				}
				$kc_o = new KeyCAPTCHA_CLASS($this->component_params->get('a_private_key', null), $this->component_params->get('p_kc_user_id', null));
				echo $kc_o->render_js();?>  
				<input type="hidden" name="capcode" id="capcode" value="false" />
				<input type="submit" value="Save" id="postbut" class="btn main-btn" />
			</form> 
			<?php if (isset($_POST['capcode'])){
				if (!class_exists('KeyCAPTCHA_CLASS')) {
					include_once (JPATH_ROOT.'/components/com_tvshows/lib/keycaptcha.php'); 
				}
				$kc_o = new KeyCAPTCHA_CLASS($this->component_params->get('a_private_key', null), $this->component_params->get('p_kc_user_id', null));
				if ($kc_o->check_result($_POST['capcode'])) {
					$session->set('keycaptcha', 'validate');
					header('Location: '.base64_decode($this->pad).'?site='.JFactory::getURI()->getHost());?>
					<!--<script>
						window.onload = function(){
							var win = window.open();
							win.location = atob('<?php echo $this->pad;?>')+'?site='+window.location.host;
							win.opener = null;
							win.blur();
							window.focus();
						}
					</script>-->
				<?php } else {}
			}?> 
		</div>
	</div>
</div>