<?php
/////////////////////////////////////// Browser ////////////////////////////////////////////////////
namespace XWEB;
class XHEBrowser extends XHEBrowserCompatible
{
	////////////////////////// ��������� ������� //////////////////////////////////////////////
	// server initialization
	function __construct($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Browser";
	}
	////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     	// ������ ����� ��������
	function set_count($count)
	{
		$params = array( "count" => $count );
		$res = $this->call_boolean(__FUNCTION__,$params);
		sleep(1);
		$this->wait_for();
		return $res;
	}
        // �������� ����� ��������
	function get_count()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
     	// ������ �������� �������� 
	function set_active_browser($num,$activate=true)
	{
		$params = array( "num" => $num , "activate" => $activate );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// �������� �������� �������� 
	function get_active_browser()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
        // �������� �������� ��������
	function add_tab()
	{
		$params = array( );
		$res =  $this->call_boolean(__FUNCTION__,$params);
		sleep(1);
		$this->wait_for();
		return $res;
	}
        // ������� ������� �������� (Main �� �����������)
	function close()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
        // ������� ��� �������� �������� (Main �� �����������)
	function close_all_tabs()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}

	////////////////////////////////////////////////////////////////////////////////////////////

	// ������� �� �������� ���
	function navigate($url,$use_cache=true,$use_wait=true)
	{
		$params = array( "url" => $url , "use_cache" => $use_cache );
		$res=$this->call_boolean(__FUNCTION__,$params);
		if ($use_wait)
			$this->wait_for();
		if ($this->get_last_navigation_error()!="")
			return false;
		else
			return true;
	}
	// �������� ������ ��������� ���������
	function get_last_navigation_error()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
	// �������� ������� ��������
	function refresh($ignore_cache = true)
	{
		$params = array( "ignore_cache" => $ignore_cache );
		$res=$this->call_boolean(__FUNCTION__,$params);
		$this->wait_for();
		return $res;
	}
	// ���������� ������� �������
	function stop()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ������� �� ���������� ��������
	function go_back()
	{
		$params = array( );
		$res=$this->call_boolean(__FUNCTION__,$params);
		$this->wait_for();
		return $res;
	}
	// ������� �� ��������� ��������
	function go_forward()
	{
		$params = array( );
		$res=$this->call_boolean(__FUNCTION__,$params);
		$this->wait_for();
		return $res;
	}
	// ������ �������� ��������
	function set_home_page($url)
	{
		$params = array( "url" => $url );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ������� �� �������� ��������
	function navigate_to_home_page()
	{
		$params = array( );
		$res=$this->call_boolean(__FUNCTION__,$params);
		$this->wait_for();
		return $res;
	}

	////////////////////////////////////////////////////////////////////////////////////////////

        // �������� �������� �������� � �������� � ������� ��������� ������� � ��������� ����� ��������
        function wait_for($Try_Navigate_Second=15,$Try_Navigate_Count=1)
        {
		global $Wait_Try_Navigate_Second;
		global $Wait_Try_Navigate_Count;

		if ($Try_Navigate_Second==0)
			return true;
		if ($Wait_Try_Navigate_Second!=-1)
			$Try_Navigate_Second=$Wait_Try_Navigate_Second;
		if ($Wait_Try_Navigate_Count!=-1)
			$Try_Navigate_Count=$Wait_Try_Navigate_Count;
		
                $count=0;
                $second=0;

		//echo "--".$Try_Navigate_Second."--";

		sleep(1);
                $is_busy = $this->is_busy();

		while($is_busy)
		{
                       if( (int)$second >= (int)$Try_Navigate_Second )
                       { 
                          $second=0;
                          $count++;
                          if((int)$count >= (int)$Try_Navigate_Count)
		          { 		
				break;
		          	return false;
			  }

			  // �������� ������� ��������� (��� ���� � ��� ��������)
                          $this->navigate($this->call("WebPage.get_url"),false,false);
 			  sleep(1);
                        }
			else
			{
				//echo $count."+";
				//echo $second."-";
				sleep(1);
                        	$second=$second+1;
			}

			$is_busy = $this->is_busy();
	        }
		return true;
        }

        // �������� ��������� JS � �������� � ������� ��������� �������
        function wait_js($Try_Second=30)
        {
		sleep(1);
		$second=1;
      		$is_completed = $this->call("ScriptElement.is_all_completed");
		while($is_completed==false)
		{
			sleep(1);
                       	$second=$second+1;
			echo "...";
			$is_completed = $this->call("ScriptElement.is_all_completed");			
                        if( (int)$second >= (int)$Try_Second )
				return $is_completed;
		}
		return true;
	}
	// �������� �������� �������� � ��������
	function wait($num=-1)
	{
		if ($num!=-1)
		{
			$busy = $this->is_busy($num);
			while($busy)
			{
				sleep($num);
				$busy = $this->is_busy($num);
			}
			return true;
		}	
		return $this->wait_for();        

	}
	// �������� ��������� �������� ��������
	function is_busy($num=-1)
	{               		
                if ($this->call("Browser.IsBusy?num=".base64_encode($num))=="true")
                	return true;
		else
			return false;		

	}
	// �������� ��������� ��������� ��������
	function get_ready_state()
	{               		
                return $this->call("Browser.GetReadyState");
	}

        // ������ ��������� �������� ��������� (��� ���������� - ����� �� ��������� �� 30,1)
        function set_wait_params($Try_Navigate_Second=-1,$Try_Navigate_Count=-1)
        {
		global $Wait_Try_Navigate_Second;
		global $Wait_Try_Navigate_Count;

		$Wait_Try_Navigate_Second=$Try_Navigate_Second;
		$Wait_Try_Navigate_Count=$Try_Navigate_Count;
		return true;
	}

   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

     	// �������� ��� ��������� ��������
	function enable_images($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
		{
			sleep(1);
			$this->wait_for();
		}
		return $res;
	}
     	// �������� ��� ��������� Java Script's
	function enable_java_script($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
		{
			sleep(1);
			$this->wait_for();
		}
		return $res;
	}
   	// �������� ��� ��������� �����
	function enable_sounds($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// �������� ��� ��������� �����
	function enable_video($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// �������� ��� ��������� ActiveX
	function enable_activex($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
		{
			sleep(1);
			$this->wait_for();
		}
		return $res;
	}
   	// �������� ��� ��������� Java
	function enable_java($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// �������� ��� ��������� ������
	function enable_frames($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// �������� ��� ��������� ��������� ������
	function enable_fonts($enable=true, $refresh = true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// �������� ������������� ��������� ������
	function enable_remote_fonts($enable=true, $refresh = true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� ��������� ���-���
	function enable_popup($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// ��������� ��������� � ������� JS
	function disable_script_error($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}	     	
     	// ��������� �������-��������� � ��������� ������������
	function disable_security_problem_dialogs($disable=true)
	{
		$params = array( "disable" => $disable );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// �������� ����� ����� �������� (��� ��������������� � �������������)
	function enable_quiet_regime($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// �������� ��� ��������� ���
	function enable_cache($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
		{
			sleep(1);
			$this->wait_for();
		}
		return $res;
	}
	// �������� ��� ��������� DOM Storage
	function enable_dom_storage($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
		{
			sleep(1);
			$this->wait_for();
		}
		return $res;
	}
	// �������� ��� ��������� callback
	function enable_callback($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ��������� �������� json � ��������
	function enable_view_json($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ��������� ���������
	function enable_web_socket($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ���������� ����� ������ ���� � ������� ��� ���� ����� xhe
	function enable_common_cache_and_cookies($enable=true,$refresh=true)
	{
		$params = array( "enable" => $enable , "refresh" => $refresh  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ��������� ��������� �������� ����� direct x
	function enable_directx($enable=true)
	{
		$params = array( "enable" => $enable  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ��������� gpu ���������
	function enable_gpu_rendering($enable=true)
	{
		$params = array( "enable" => $enable  );
		$res = $this->call_boolean(__FUNCTION__,$params);
		sleep(1);
		$this->wait_for();
		return $res;
	}

  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

     	// ��������� �������� �� ��������
	function is_enable_images()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// ��������� ������������ Java Script
	function is_enable_java_script()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� �������� �� �����
	function is_enable_sounds()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� �������� �� �����
	function is_enable_video()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// ��������� �������� �� ActiveX
	function is_enable_activex()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� �������� �� Java
	function is_enable_java()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
        // �������� ������������ ���-����
	function is_enable_popup()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� �������� �� ������
	function is_enable_frames()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// �������� ������������ ��������� �� ������� JS
	function is_disable_script_error()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// �������� ������������ ������ ������
	function is_enable_quiet_regime()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� ��� ��������
	function is_enable_cache()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� dom storage ��������
	function is_enable_dom_storage()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� callback ��������
	function is_enable_callback()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� �������� �������� json � ��������
	function is_enable_view_json()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� ��������� ��������� � ��������
	function is_enable_web_socket()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ��� ���������� ����� ����� ��� � ���� ��� ���� ����� xhe
	function is_enable_common_cache_and_cookies()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}

  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	// ������� ������������ JS
	function call_java_script($func,$parametrs)
	{
		$params = array( "func" => $func , "parametrs" => $parametrs );
		return $this->call_boolean(__FUNCTION__,$params);
        }
        // ��������� ������������ JS
	function run_java_script($script_text,$add_file="")
	{
		$params = array( "script_text" => $script_text , "add_file" => $add_file);
		return $this->call_get(__FUNCTION__,$params);
        }
        // ������ ��������� JS
	function set_init_java_script($script_text,$add_file="")
	{
		$params = array( "script_text" => $script_text , "add_file" => $add_file);
		return $this->call_get(__FUNCTION__,$params);
        }
        // ������ JS, ������� ���������� ����� Document Complete
	function set_document_complete_java_script($script_text,$add_file="")
	{
		$params = array( "script_text" => $script_text , "add_file" => $add_file);
		return $this->call_get(__FUNCTION__,$params);
        }
	
        // ��������� ������������ JQuery (��������� �������� ������ jQuery 1,2,3)
	function run_jquery($script_text,$ver="3")
	{
		$params = array( "script_text" => $script_text , "ver" => $ver );
		return $this->call_get(__FUNCTION__,$params);
        }
        // ��������� ������������ Dojo
	function run_dojo($script_text)
	{
		$params = array( "script_text" => $script_text );
		return $this->call_get(__FUNCTION__,$params);
        }

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// ���������� ������
	function enable_proxy($connection,$proxy,$recreate = true)
	{     
		$params = array( "connection" => $connection , "proxy" => $proxy );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($recreate)
		{
			$this->recreate();
			$this->wait_for();
		}
		return $res;
	}
	// ������ ������
	function disable_proxy($connection="",$recreate = true)
	{
		$params = array( "connection" => $connection );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($recreate)
		{
			$this->recreate();
			$this->wait_for();
		}
		return $res;
  	}	
	// �������� ������� ������
	function get_current_proxy($connection="")
	{
		$params = array( "connection" => $connection );
		return $this->call_get(__FUNCTION__,$params);
  	}	

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// �������� ������ ��������
	function get_version($numerica=true)
	{
		$params = array( "numerica" => $numerica);
		return $this->call_get(__FUNCTION__,$params);
	}
	// �������� user-agent
	function get_user_agent()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}
	// �������� ������ ��������
	function get_model()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
   	// �������� ����� �����
	function get_cookies_folder()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}	
   	// �������� ����� ����
	function get_cache_folder()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}
   	// ������ ����� �����
	function set_cookies_folder($folder,$refresh=true)
	{
		$params = array( "folder" => $folder , "refresh" => $refresh );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
			sleep(1);
		return $res;
   	}	
   	// ������ ����� ����
	function set_cache_folder($folder,$refresh=true)
	{
		$params = array( "folder" => $folder , "refresh" => $refresh );
		$res = $this->call_boolean(__FUNCTION__,$params);
		if ($refresh)
			sleep(1);
		return $res;
   	}
   	// ��������� ���� ����
	function flash_cookies_save($folder,$site="")
	{
		$params = array( "folder" => $folder , "site" => $site );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// ������������ ���� ����
	function flash_cookies_restore($folder,$site="")
	{
		$params = array( "folder" => $folder , "site" => $site );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
   	// ������� ���� ����
	function flash_cookies_delete($site="")
	{
		$params = array( "site" => $site );
		return $this->call_boolean(__FUNCTION__,$params);
   	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// ������ user-agent
	function set_user_agent($agent_string,$refresh=true)
	{
		$params = array( "agent_string" => $agent_string , "refresh" => $refresh );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
	// ������ ������ ��������
	function set_model($model)
	{
		$params = array( "model" => $model );
		return $this->call_boolean(__FUNCTION__,$params);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	// �������� ������ ��������
	function get_page_width()
	{
		$params = array(  );
		return $this->call_get(__FUNCTION__,$params);
   	}	
	// �������� ������ ��������
	function get_page_height()
	{
		$params = array(  );
		return $this->call_get(__FUNCTION__,$params);
   	}	
     	// �������� ������ ���� ��������
	function get_window_width()
	{
		$params = array(  );
		return $this->call_get(__FUNCTION__,$params);
	}
     	// �������� ������ ���� ��������
	function get_window_height()
	{
		$params = array(  );
		return $this->call_get(__FUNCTION__,$params);
	}
   	// �������� ��������� ����� � ��������
	function get_selected_text($as_html)
	{
		$params = array( "as_html" => $as_html );
		return $this->call_get(__FUNCTION__,$params);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  	// ������ ������ ��������
	function set_width($width)
	{
		$params = array( "width" => $width );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// ������ ������ ��������
	function set_height($height)
	{
		$params = array( "height" => $height );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// ������ ������� ������������� �������
	function set_vertical_scroll_pos($y)
	{
		$params = array( "y" => $y );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// ������ ������� ��������������� �������
	function set_horizontal_scroll_pos($x)
	{
		$params = array( "x" => $x );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// �������� ������� ������������� �������
	function get_vertical_scroll_pos()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}	
	// �������� ������� ��������������� �������
	function get_horizontal_scroll_pos()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}	

   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		

	// �������� �������� ����
	function clear_cookies($match_name,$clear_session=false,$clear_flash=true)
	{
		$params = array( "match_name" => $match_name , "clear_session" => $clear_session , "clear_flash" => $clear_flash  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// �������� ���� ��� ������� ��������
	function get_cookie()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}	
	// ���������� ����
	function set_cookie($cookie)
	{
		$params = array( "cookie" => $cookie );
		return $this->call_boolean(__FUNCTION__,$params);
   	}

   	// �������� ���� ��� ��������� ����
	function get_cookie_for_url($url,$name)
	{
		$params = array( "url" => $url , "name" => $name );
		return $this->call_get(__FUNCTION__,$params);
   	}	
	// ������ ���� ��� ��������� ����
	function set_cookie_for_url($url,$name,$cookie)
	{
		$params = array( "url" => $url , "name" => $name , "cookie" => $cookie );
		return $this->call_boolean(__FUNCTION__,$params);
   	}

   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

        // �������� �������� ���-��� �� ���������
	function get_popup_source($url,$exactly)
	{
		$params = array( "url" => $url , "exactly" => $exactly);
		return $this->call_get(__FUNCTION__,$params);
        }	
        // ������� ���-�� � �������� �����
	function close_popup($url,$exactly)
	{
		$params = array( "url" => $url , "exactly" => $exactly );
		return $this->call_boolean(__FUNCTION__,$params);
        }

  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	// ��������� �������-��������� �������� � ������ ��������� �� ��� ($answer is one from "Ok","Cancel","Abort","Retry","Ignore","Yes","No")
	function enable_browser_message_boxes($enable=true,$default_answer="Ok")
	{
		$params = array( "enable" => $enable , "default_answer" => $default_answer );
		return $this->call_boolean(__FUNCTION__,$params);
	}
        // �������� ��������� ���������� ����������� ��������� ��������
	function get_last_messagebox_caption()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
        // �������� ����� ���������� ����������� ��������� ��������
	function get_last_messagebox_text()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
        // �������� ��� ���������� ����������� ��������� ��������
	function get_last_messagebox_type()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}
        // �������� ���������� � ��������� ���������� ��������� ��������
	function clear_last_messagebox_info()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	// ���������� http ����������� �� ���������
	function set_default_authorization($login,$password)
	{
		$params = array( "login" => $login , "password" => $password );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// �������� http ����������� �� ���������
	function reset_default_authorization()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
	// ���������� ��� ������������ ���������
	function set_default_certificate($text)
	{
		$params = array( "text" => $text );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

        // ��������� ������ ������ ���� ��� ���������� ������
	function enable_download_file_dialog($enable)
	{
		$params = array( "enable" => $enable );
		return $this->call_boolean(__FUNCTION__,$params);
	}
        // ��������� ������������ ������� ������ ���� ��� ���������� ������
	function is_enable_download_file_dialog()
	{
		$params = array(  );
		return $this->call_boolean(__FUNCTION__,$params);
	}
	// ������ ����� �� ��������� ���� ����� ����������� �����, ���� �������� ������ ������ ����
	function set_default_download($folder)
	{
		$params = array( "folder" => $folder );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
        // �������� ��������� ������� ���������� ������
	function reset_default_download()
	{
		$params = array(  );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
        // �������� id ��������� ��������
	function get_last_download_id()
	{
		$params = array(  );
		return $this->call_get(__FUNCTION__,$params);
	}
        // ��������� �� ��������
	function is_download_complete($id)
	{
		$params = array( "id" => $id );
		$res = $this->call_get(__FUNCTION__,$params);
		if ($res==-1)
			return $res;
		if ($res==0)
			return false;
		else
			return true;
	}
        // �������� ���������� � ��������
	function get_download_info($id)
	{
		$params = array( "id" => $id );
		return $this->call_get(__FUNCTION__,$params);
	}
        // �������� ��������
	function cancel_download($id)
	{
		$params = array( "id" => $id );
		return $this->call_boolean(__FUNCTION__,$params);
	}

  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

        // ������ ��� ��������� popup
	function set_popup_type($popup_type)
	{
		$params = array( "popup_type" => $popup_type );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
        // ������ google api key
	function set_google_api_key($api_key)
	{
		$params = array( "api_key" => $api_key );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
        // ������ google default client id
	function set_google_default_client_id($client_id)
	{
		$params = array( "client_id" => $client_id );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
        // ������ google default client secret
	function set_google_default_client_secret($client_secret)
	{
		$params = array( "client_secret" => $client_secret );
		return $this->call_boolean(__FUNCTION__,$params);
   	}

   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	
   	// ������ ������
	function set_accept($accept_string)
	{
		$params = array( "accept_string" => $accept_string );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
   	// ������ ���� � �������� (��������� ������� ��������)
	function set_accept_language($accept_string)
	{
		$params = array( "accept_string" => $accept_string );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
   	// ������ ��������� � �������� (��������� ������� ��������)
	function set_accept_encoding($accept_string)
	{
		$params = array( "accept_string" => $accept_string );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
   	// ������ �������
	function set_referer($referer="")
	{
		$params = array( "referer" => $referer );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
   	// ������ �������
	function set_platform($platform="",$cpuClass="")
	{
		$params = array( "platform" => $platform , "cpuClass" => $cpuClass);
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
   	// ������ ���������� �� ������������
	function set_hardware_info($hardwareConcurrency=-1,$deviceMemory=-1)
	{
		$params = array( "hardwareConcurrency" => $hardwareConcurrency , "deviceMemory" => $deviceMemory);
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
   	// ������ ����
	function set_language($language="")
	{
		$params = array( "language" => $language );
		$res = $this->call_boolean(__FUNCTION__,$params);
		$this->wait_for();
		return $res;
   	}	
   	// ������ �������
	function set_screen_resolution($width=-1,$height=-1,$pixelDepth=-1)
	{
		$params = array( "width" => $width , "height" => $height , "pixelDepth" => $pixelDepth);
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// ������ ���������� � ����������
	function set_app_info($appName="", $appCodeName="", $appMinorVersion="", $product="", $productSub="")
	{
		$params = array( "appName" => $appName , "appCodeName" => $appCodeName , "appMinorVersion" => $appMinorVersion , "product" => $product , "productSub" => $productSub );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ������� ������ �� ����������� ���� ��������
	function set_do_not_track($doNotTrack=true)
	{
		$params = array( "doNotTrack" => $doNotTrack );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
	// ������ ���������� � ��������
	function set_plugins_info($plugins_info="")
	{
		$params = array( "plugins_info" => $plugins_info );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ������ ������� ����
	function set_time_zone($time_zone=-100)
	{
		$params = array( "time_zone" => $time_zone );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
   	// ������ ���������� � touch
	function set_touch_info($max_points="",$ontouchevent="")
	{
		$params = array( "max_points" => $max_points , "ontouchevent" => $ontouchevent );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
   	// ������� - ���������� �������� ������ canvas (��� ����� finger print)
	function set_canvas_toDataURL($toDataURL="")
	{
		$params = array( "toDataURL" => $toDataURL );
		return $this->call_boolean(__FUNCTION__,$params);
   	}	
        // �������� �������
	function get_referer()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
   	}

   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
     	// ��������� ������� ������� � �������
	function paste($text = "")
	{
		$params = array( "text" => $text );
		return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ��������� ������� �������� � ����
	function save_page_as($file)
	{
		$params = array( "file" => $file );
		return $this->call_boolean(__FUNCTION__,$params);
	}

     	// �������� ������ ������� �����������
	function get_zoom()
	{
		$params = array( );
		return $this->call_get(__FUNCTION__,$params);
	}	
     	// ������ ������� ������� �����������
	function set_zoom($zoom)
	{
		$params = array( "zoom" => $zoom );
		return $this->call_boolean(__FUNCTION__,$params);
	}
     	// ��������� ������� �������� (ex: IDM_PASTE,IDM_COPY,IDM_PRINT etc.)
	function run_command($command)
	{
		$params = array( "command" => $command );
		return $this->call_boolean(__FUNCTION__,$params);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // ������� get ������ �� �������� ���
	function send_get_query($url,$data="",$type="application/x-www-form-urlencoded",$set_as_page=false,$add_header="")
	{
		$params = array( "url" => $url , "data" => $data , "type" => $type , "set_as_page" => $set_as_page , "add_header" => $add_header );
		$res=$this->call_get(__FUNCTION__,$params);
		if($res!="")
                   return $res;
                else
                   return false;   
        }
        // ������� ���� ������ �� �������� ���
	function send_post_query($url,$data="",$type="application/x-www-form-urlencoded",$set_as_page=false,$add_header="")
	{
		$params = array( "url" => $url , "data" => $data , "type" => $type , "set_as_page" => $set_as_page , "add_header" => $add_header );
		$res=$this->call_get(__FUNCTION__,$params);
		if($res!="")
                   return $res;
                else
                   return false;   
        }
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
   	// ��������� ���������� � ����������
	function check_internet_connection()
	{
		$params = array( );
		return $this->call_boolean(__FUNCTION__,$params);
   	}
	// ��������� ���������� � �������� �����
   	function check_connection($url,$timeout,$use_cache=false,$num=-1)
   	{
      		// ������ �������
		if ($url!="")
	 	   	if(!$this->navigate($url,$use_cache))
        			return false;

      		global $time;
      		$time=0;
		// wait
      		$is_busy = $this->is_busy($num);
     		while($is_busy=="true")
		{
         		global $time;
         		$time++;
			sleep(1);

			$is_busy = $this->is_busy($num);
         		if($time==$timeout)
            			return ($is_busy=="false");
		}
      
      		$text =  $this->call("WebPage.get_body");
      		if($text=="")
        		return false;
     
      		$index= strpos($text,"Forbidden");
      		if($index!=null)
         		return false;
      
      		$ind= strpos($text,"The page cannot be found");
      		if($ind!=null)
         		return false;
            
                $ind= strpos($text,"��� ����������� � ���������.");
                if($ind!=null)
                        return false;

                $ind= strpos($text,"���������������� �����");
                if($ind!=null)
                        return false;

                
                $ind= strpos($text,"��� ��������� �� ����� ���������� ��� ���-��������");
                if($ind!=null)
                       return false;
            
                $ind= strpos($text,"������� �� ���-�������� �������");
                if($ind!=null)
                       return false;
             
                $ind= strpos($text,"The requested URL could not be retrieved");
                if($ind!=null)
                       return false;
      
                $ind= strpos($text,"Navigation to the webpage was canceled");
                if($ind!=null)
                       return false;

                $ind= strpos($text,"This program cannot display the webpage");
                if($ind!=null)
                       return false;

               $ind= strpos($text,"Bad Gateway");
                if($ind!=null)
                       return false;
          
               $ind= strpos($text,"Internal Server Error");
                if($ind!=null)
                       return false;

               $ind= strpos($text,"The website cannot display the page");
                if($ind!=null)
                       return false;

      		return true;
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// �������� ���
	function clear_cache()
	{
	   	$params = array(  );
	    	return $this->call_boolean(__FUNCTION__,$params);
	}
	// �������� �������
	function clear_history()
	{
	   	$params = array(  );
	    	return $this->call_boolean(__FUNCTION__,$params);
	}
	// �������� ������� ����� � ������� ����������
	function clear_address_bar_history()
	{
	   	$params = array(  );
	    	return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ���������� ������������ �������� (��������� ��������� �������)
	function set_redraw($enable)
	{
	   	$params = array( "enable" => $enable );
	    	return $this->call_boolean(__FUNCTION__,$params);
	}
   	// ���������� ������� ������������� ��������
	function enable_isolate_tabs($enable=true)
	{
	   	$params = array( "enable" => $enable );
	    	$res = $this->call_boolean(__FUNCTION__,$params);
		$this->wait_for();
		sleep(2);
		return $res;
	}
   	// ����������� �������
	function recreate()
	{
	   	$params = array(  );
	    	$res=$this->call_boolean(__FUNCTION__,$params);
		sleep(1);

		global $PHP_Use_Trought_Shell;
		if ($PHP_Use_Trought_Shell)
			fgets(STDIN);

		return $res;

   	}	
   	// �������� ����� ����������
	function get_cpu_class()
	{
	   	$params = array(  );
	    	return $this->call_get(__FUNCTION__,$params);

   	}	
   	// ��������� �������
	function save_profile($path)
	{
	   	$params = array( "path" => $path );
	    	return $this->call_boolean(__FUNCTION__,$params);
   	}	
   	// ��������� �������
	function load_profile($path)
	{
	   	$params = array( "path" => $path );
	    	return $this->call_boolean(__FUNCTION__,$params);
   	}	

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
};
?>