<?php
class UserIdentity extends CUserIdentity
{
	private $_id;
	public function authenticate()
	{
		$dominio="minpublico.cl";$host="172.18.1.7";$puerto="389";
        $usuario=$this->username."@".$dominio;    		
		$conex = ldap_connect($host,$puerto);
		
		if($this->username===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif ($conex) 
		{
		    ldap_set_option($conex, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($conex, LDAP_OPT_REFERRALS, 0);	
			$bind = @ldap_bind($conex, $this->username."@".$dominio, $this->password);
			//$r=ldap_bind($conex, $usuario, $this->password);
			if ($bind)
		 	{
		 		$i = 0;
		 		$user = Funcionario::model()->findByAttributes(array('fun_login'=>$this->username));

		 		$sqlf=FuncionarioFiscalia::model()->findAllByAttributes(array('fun_rut'=>$user->fun_rut,'funfis_ind_vigencia'=>1));
		 		
		 		if($sqlf){
		 			$this->setState('fiscalia', $sqlf[0]->fis_codigo);
		 			$this->setState('unidad', $sqlf[0]->uni_codigo);

		 			$valor=count($sqlf);

		 			$this->setState('fun_fis', $valor);
		 		}//FIN SQLF

		 		///PERFILES///
		 		$perfil=PerfilFuncionario::model()->findAllByAttributes(array('fun_rut'=>$user->fun_rut,'ind_vigencia'=>1));
		 		if($perfil){
		 			$this->setState('perfil', $perfil[0]->cod_perfil);
		 		}//FIN SQLF  
	 		
                $this->_id=$user->fun_rut;
				$this->setState('nombre', $user->fun_nombre.' '.$user->fun_ap_paterno);
				$this->setState('foto', $user->fun_foto);
	
				$this->errorCode=self::ERROR_NONE;							
			}
			else $this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		return !$this->errorCode;
	}

	public function getId()
    {
        return $this->_id;
    }
}
