<?php
////////////////////////////////////////////////////// Th ///////////////////////////////////////////////////
namespace XWEB;
class XHETH  extends XHEBaseDOMVisual
{
	/////////////////////////////////////// ��������� ������� //////////////////////////////////////////
	// server initialization
	function __construct($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Th";
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////
};		
?>