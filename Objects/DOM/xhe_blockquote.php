<?php
////////////////////////////////////////////////////// Blockquote ///////////////////////////////////////////
namespace XWEB;
class XHEBlockquote  extends XHEBaseDOMVisual
{
	/////////////////////////////////////// ��������� ������� //////////////////////////////////////////
	// server initialization
	function __construct($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Blockquote";
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////
};		
?>