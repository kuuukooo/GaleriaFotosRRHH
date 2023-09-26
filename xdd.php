<?php
function login($user, $pass){
		$DOMINIO = 'ajvierci.com.py';
		//$DOMINIO = 'AJVOLAP';
	 
		$ldaprdn = trim($user).'@'.$DOMINIO; 
	    $ldappass = trim($pass); 
	    $ds = $DOMINIO; 
	  
	    $puertoldap = 389; 
	    $ldapconn = ldap_connect($ds,$puertoldap);
	    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3); 
	    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0); 
	    $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass); 

	    ldap_close($ldapconn); 
				
		return $ldapbind ;//$array;
	}   
?>