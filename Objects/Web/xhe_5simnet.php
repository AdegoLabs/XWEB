<?php
//////////////////////////////////////////////////////// simnet ///////////////////////////////////////////////////////
namespace XWEB;
class XHE5Simnet extends XHEBaseSMS
{

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // constructor
        function __construct($api='',$servis='http://sms-activate.api.5sim.net',$ref='ze7luo')
        {
            $this->api_key = $api;
            $this->servis = $servis;
            $this->ref = $ref;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

};
?>