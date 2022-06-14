LOAD DATA INFILE 'C:/xampp/htdocs/intra/FUN_ACTUALIZADO.csv'  
     INTO TABLE funcionario
     FIELDS TERMINATED BY ';'
     LINES TERMINATED BY '\n'
       IGNORE 1 LINES
       (fun_rut, fun_nombre, fun_nombre2, fun_ap_paterno, fun_ap_materno, fun_login, fun_clave, fun_email, fun_grado,  fun_ind_vigente, fun_fecha_nac, fun_foto,crg_codigo, est_codigo, fun_sigla )

CREATE TABLE `separar_causas` (
  `cod_serpararcausa` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,  
  `idf_rolunico_original` varchar(15) COLLATE latin1_spanish_ci NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL, 
  `ind_vigencia` int NOT NULL,  
  PRIMARY KEY (`cod_serpararcausa`),
  KEY `idf_rolunico` (`idf_rolunico`),
  KEY `idf_rolunico_original` (`idf_rolunico_original`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `permiso_reserva` (
  `cod_permisoreserva` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,  
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL, 
  `ind_vigencia` int NOT NULL,  
  PRIMARY KEY (`cod_permisoreserva`),
  KEY `idf_rolunico` (`idf_rolunico`),
  KEY `fis_codigo` (`fis_codigo`),
  KEY `fun_rut` (`fun_rut`),
  KEY `fun_responsable` (`fun_responsable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `reserva_documento` (
  `cod_reservadoc` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `cod_carpdig` int NOT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL, 
  PRIMARY KEY (`cod_reservadoc`),
  KEY `idf_rolunico` (`idf_rolunico`),
  KEY `cod_carpdig` (`cod_carpdig`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `permiso_causa` (
  `cod_permisocausa` int NOT NULL AUTO_INCREMENT,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `cuenta_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `ind_fiscalasig` int NOT NULL,  
  PRIMARY KEY (`cod_permisocausa`),
  KEY `fis_codigo` (`fis_codigo`),
  KEY `cuenta_rut` (`cuenta_rut`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `fun_tmp` (
  `cod_funtmp` int NOT NULL AUTO_INCREMENT,
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_funtmp`),
    KEY `fun_rut` (`fun_rut`),
    KEY `fun_responsable` (`fun_responsable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_estadocarpdig` (
  `cod_estadocarpdig` int NOT NULL AUTO_INCREMENT,
  `gls_estadocarpdig` varchar(999) COLLATE latin1_spanish_ci NOT NULL,   
  PRIMARY KEY (`cod_estadocarpdig`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `carpeta_digital` (
  `cod_carpdig` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `fec_actividad` date NOT NULL, 
  `crr_idactividad` int DEFAULT NULL,  
  `cod_clasedoc` int NOT NULL, 
  `cod_estadoparte` int NOT NULL, 
  `gls_nomdoc` varchar(999) COLLATE latin1_spanish_ci NOT NULL,
  `gls_ruta` varchar(999) COLLATE latin1_spanish_ci NOT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` datetime NOT NULL, 
  PRIMARY KEY (`cod_carpdig`),
   KEY `idf_rolunico` (`idf_rolunico`),
   KEY `crr_idactividad` (`crr_idactividad`),
   KEY `cod_clasedoc` (`cod_clasedoc`),
   KEY `cod_estadoparte` (`cod_estadoparte`),
   KEY `fis_codigo` (`fis_codigo`),
   KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `estado_carpdigital` (
  `cod_estadoc` int NOT NULL AUTO_INCREMENT,
  `cod_carpdig` int NOT NULL,  
  `cod_estadocarpdig` int NOT NULL,   
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,  
  `fec_registro` datetime NOT NULL,  
  PRIMARY KEY (`cod_estadoc`),
  KEY `cod_carpdig` (`cod_carpdig`),
  KEY `cod_estadocarpdig` (`cod_estadocarpdig`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `temp_digital` (
  `cod_tempdig` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `fec_actividad` date NOT NULL, 
  `crr_idactividad` int DEFAULT NULL,  
  `cod_clasedoc` int NOT NULL, 
  `cod_estadoparte` int NOT NULL, 
  `gls_nomdoc` varchar(999) COLLATE latin1_spanish_ci NOT NULL,
  `gls_ruta` varchar(999) COLLATE latin1_spanish_ci NOT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` datetime NOT NULL, 
  PRIMARY KEY (`cod_tempdig`),
   KEY `idf_rolunico` (`idf_rolunico`),
   KEY `crr_idactividad` (`crr_idactividad`),
   KEY `cod_clasedoc` (`cod_clasedoc`),
   KEY `cod_estadoparte` (`cod_estadoparte`),
   KEY `fis_codigo` (`fis_codigo`),
   KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_clasedoc` (
  `cod_clasedoc` int NOT NULL AUTO_INCREMENT,
  `gls_clasedoc` varchar(999) COLLATE latin1_spanish_ci NOT NULL,
  `gls_clasecodigo` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `gls_aplica` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `ind_vigencia` int NOT NULL, 
  PRIMARY KEY (`cod_clasedoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tmp_eliminar` (
  `cod_tmpeliminar` int NOT NULL AUTO_INCREMENT,
  `cod_bntarea` int NOT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` datetime NOT NULL, 
  PRIMARY KEY (`cod_tmpeliminar`),
   KEY `cod_bntarea` (`cod_bntarea`),
   KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tmp_ejecutar` (
  `cod_tmpejecutar` int NOT NULL AUTO_INCREMENT,
  `cod_bntarea` int NOT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` datetime NOT NULL, 
  PRIMARY KEY (`cod_tmpejecutar`),
   KEY `cod_bntarea` (`cod_bntarea`),
   KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tmp_asignar` (
  `cod_tmpasignar` int NOT NULL AUTO_INCREMENT,
  `cod_bntarea` int NOT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` datetime NOT NULL, 
  PRIMARY KEY (`cod_tmpasignar`),
   KEY `cod_bntarea` (`cod_bntarea`),
   KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `elim_tarea` (
  `cod_elimtarea` int NOT NULL AUTO_INCREMENT,
  `cod_bntarea` int NOT NULL,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,   
  `cod_instruccion` int NOT NULL,   
  `gls_comentario` varchar(999) COLLATE latin1_spanish_ci DEFAULT NULL,    
  `fec_tarea` date DEFAULT NULL,  
  `fec_asignacion` datetime DEFAULT NULL,  
  `cod_estinstruccion` int NOT NULL,    
  `fec_cambioest` datetime DEFAULT NULL,  
  `fun_asignado` varchar(12) COLLATE latin1_spanish_ci DEFAULT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `ind_dias` int DEFAULT NULL, 
  `cod_prioridad` int NOT NULL, 
  `cod_plantrabajo` int DEFAULT NULL,    
  `fec_registro` datetime NOT NULL,
  PRIMARY KEY (`cod_elimtarea`),
   KEY `cod_bntarea` (`cod_bntarea`),
   KEY `idf_rolunico` (`idf_rolunico`),
   KEY `fis_codigo` (`fis_codigo`),
   KEY `cod_instruccion` (`cod_instruccion`),
   KEY `cod_estinstruccion` (`cod_estinstruccion`),
   KEY `fun_asignado` (`fun_asignado`),
   KEY `fun_rut` (`fun_rut`),
   KEY `cod_prioridad` (`cod_prioridad`),
   KEY `cod_plantrabajo` (`cod_plantrabajo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `banco_tarea_temp` (
  `cod_bntemp` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,   
  `cod_instruccion` int NOT NULL,   
  `gls_comentario` varchar(999) COLLATE latin1_spanish_ci DEFAULT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` datetime NOT NULL,
  PRIMARY KEY (`cod_bntemp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `banco_tarea` (
  `cod_bntarea` int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,   
  `cod_instruccion` int NOT NULL,   
  `gls_comentario` varchar(999) COLLATE latin1_spanish_ci DEFAULT NULL,    
  `fec_tarea` date DEFAULT NULL,  
  `fec_asignacion` datetime DEFAULT NULL,  
  `cod_estinstruccion` int NOT NULL,    
  `fec_cambioest` datetime DEFAULT NULL,  
  `fun_asignado` varchar(12) COLLATE latin1_spanish_ci DEFAULT NULL, 
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `ind_dias` int DEFAULT NULL, 
  `cod_prioridad` int NOT NULL, 
  `cod_plantrabajo` int DEFAULT NULL,    
  `fec_registro` datetime NOT NULL,
  PRIMARY KEY (`cod_bntarea`),
   KEY `idf_rolunico` (`idf_rolunico`),
   KEY `fis_codigo` (`fis_codigo`),
   KEY `cod_instruccion` (`cod_instruccion`),
   KEY `cod_estinstruccion` (`cod_estinstruccion`),
   KEY `fun_asignado` (`fun_asignado`),
   KEY `fun_rut` (`fun_rut`),
   KEY `cod_prioridad` (`cod_prioridad`),
   KEY `cod_plantrabajo` (`cod_plantrabajo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `estado_tarea` (
  `cod_esttarea` int NOT NULL AUTO_INCREMENT,
  `cod_bntarea` int NOT NULL,  
  `cod_estinstruccion` int NOT NULL, 
  `fec_registro` datetime NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,  
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,  
  PRIMARY KEY (`cod_esttarea`),
  KEY `cod_bntarea` (`cod_bntarea`),
  KEY `cod_estinstruccion` (`cod_estinstruccion`),
  KEY `fun_rut` (`fun_rut`),
  KEY `fun_responsable` (`fun_responsable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_perfil` (
  `cod_perfil` int NOT NULL AUTO_INCREMENT,
  `gls_perfil` varchar(200) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `perfil_funcionario` (
  `cod_perfun` int NOT NULL AUTO_INCREMENT,  
  `cod_perfil` int NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `ind_vigencia` int NOT NULL,
  `fec_registro` date NOT NULL,
  PRIMARY KEY (`cod_perfun`),
  KEY `cod_perfil` (`cod_perfil`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_estinstruccion` (
  `cod_estinstruccion` int NOT NULL AUTO_INCREMENT,
  `gls_estinstruccion` varchar(100) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_estinstruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_instruccion` (
  `cod_instruccion` int NOT NULL AUTO_INCREMENT,
  `gls_instruccion` varchar(100) COLLATE latin1_spanish_ci NOT NULL, 
  `tiempo_instruccion` int NOT NULL, 
  `cod_faminstruccion` int NOT NULL,   
  PRIMARY KEY (`cod_instruccion`),
   KEY `cod_faminstruccion` (`cod_faminstruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_familiainstruccion` (
  `cod_faminstruccion` int NOT NULL AUTO_INCREMENT,
  `gls_faminstruccion` varchar(100) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_faminstruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `dil_tmp` (
  `cod_diltmp` int NOT NULL AUTO_INCREMENT,
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `cod_instruccion` int(11) NOT NULL,
  PRIMARY KEY (`cod_diltmp`),
    KEY `fun_rut` (`fun_rut`),
    KEY `cod_instruccion` (`cod_instruccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `carpeta` (
  `cod_carpeta`  int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `cod_estcarpeta` int NOT NULL,  
  `cod_casillero` int DEFAULT NULL,    
  `fec_registro` datetime NOT NULL,  
  `cod_tipubicacion` int NOT NULL,   
  `cod_ubicacion` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `gls_observacion` varchar(900) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ind_ultmov` int DEFAULT NULL,  
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_carpeta`),
  KEY `idf_rolunico` (`idf_rolunico`),
  KEY `fis_codigo` (`fis_codigo`),
  KEY `cod_estcarpeta` (`cod_estcarpeta`),
  KEY `cod_casillero` (`cod_casillero`),
  KEY `cod_tipubicacion` (`cod_tipubicacion`),
  KEY `cod_ubicacion` (`cod_ubicacion`),
  KEY `fun_responsable` (`fun_responsable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;



CREATE TABLE `carpeta_temp` (
  `cod_carpetatemp`  int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `cod_estcarpeta` int NOT NULL,  
  `cod_casillero` int DEFAULT NULL,    
  `fec_registro` datetime NOT NULL,  
  `cod_tipubicacion` int NOT NULL,   
  `cod_ubicacion` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `gls_observacion` varchar(900) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ind_ultmov` int DEFAULT NULL,  
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_carpetatemp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;





CREATE TABLE `tmp_carpeta` (
  `cod_tmpcarpeta`  int NOT NULL AUTO_INCREMENT,
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,  
  `cod_estcarpeta` int NOT NULL,  
  `cod_casillero` int DEFAULT NULL,    
  `fec_registro` datetime NOT NULL,  
  `cod_tipubicacion` int NOT NULL,   
  `cod_ubicacion` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `gls_observacion` varchar(900) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ind_ultmov` int DEFAULT NULL,  
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_tmpcarpeta`),
  KEY `idf_rolunico` (`idf_rolunico`),
  KEY `fis_codigo` (`fis_codigo`),
  KEY `cod_estcarpeta` (`cod_estcarpeta`),
  KEY `cod_casillero` (`cod_casillero`),
  KEY `cod_tipubicacion` (`cod_tipubicacion`),
  KEY `cod_ubicacion` (`cod_ubicacion`),
  KEY `fun_responsable` (`fun_responsable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_estadocarpeta` (
  `cod_estcarpeta` int NOT NULL AUTO_INCREMENT,
  `gls_estcarpeta` varchar(100) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_estcarpeta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_ubicacioncarp` (
  `cod_ubicacion` int NOT NULL AUTO_INCREMENT,
  `gls_ubicacion` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,    
  PRIMARY KEY (`cod_ubicacion`),
  KEY `fis_codigo` (`fis_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_tipubicacion` (
  `cod_tipubicacion` int NOT NULL AUTO_INCREMENT,
  `gls_tipubicacion` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_tipubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_tipausencia` (
  `cod_tipausencia` int NOT NULL AUTO_INCREMENT,
  `gls_tipausencia` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  `tiempo_ausencia` int NOT NULL,  
  `ind_vigencia` int NOT NULL,  
  PRIMARY KEY (`cod_tipausencia`),
  KEY `ind_vigencia` (`ind_vigencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `ausencia` (
  `cod_ausencia` int NOT NULL AUTO_INCREMENT,  
  `cod_tipausencia` int(11) NOT NULL,
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fec_ausencia` date NOT NULL,
  `fec_registro` datetime NOT NULL,
  `Observaciones` text COLLATE latin1_spanish_ci
  PRIMARY KEY (`cod_ausencia`),
  KEY `cod_tipausencia` (`cod_tipausencia`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_prioridad` (
  `cod_prioridad` int NOT NULL AUTO_INCREMENT,
  `gls_prioridad` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_prioridad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `denuncia` (
  `cod_denuncia` int(11) NOT NULL AUTO_INCREMENT,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci DEFAULT NULL,
  `cod_tipdenuncia` int(11) NOT NULL,
  `num_denuncia` varchar(15) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fec_ingreso` datetime NOT NULL,
  `cod_origencaso` int(11) NOT NULL,
  `cod_destaca` int(11) DEFAULT NULL,
  `id_comisaria` int(11) DEFAULT NULL,
  `gls_procedencia` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
  `funcionario_entrega` varchar(500) COLLATE latin1_spanish_ci DEFAULT NULL,
  `obs_denuncia` varchar(900) COLLATE latin1_spanish_ci DEFAULT NULL,
  `cod_estcarpeta` int(11) NOT NULL,  
  `fec_cambioest` datetime NOT NULL,  
  `fun_asignado` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fec_asignacion` datetime NOT NULL,
  `ind_pendiente` int(11) DEFAULT NULL,
  `ind_control` int(11) DEFAULT NULL,
  `ind_recepcion` int(11) NOT NULL,
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `fec_registro` datetime NOT NULL,
  PRIMARY KEY (`cod_denuncia`),
  KEY `cod_tipdenuncia` (`cod_tipdenuncia`),
  KEY `cod_origencaso` (`cod_origencaso`),
  KEY `cod_destaca` (`cod_destaca`),
  KEY `id_comisaria` (`id_comisaria`),
  KEY `cod_procedencia` (`gls_procedencia`),
  KEY `cod_estcarpeta` (`cod_estcarpeta`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;


CREATE TABLE `estado_denuncia` (
  `cod_estadoden` int NOT NULL AUTO_INCREMENT,
  `cod_denuncia` int NOT NULL,  
  `cod_estcarpeta` int NOT NULL, 
  `fec_registro` datetime NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL,  
  `fun_responsable` varchar(12) COLLATE latin1_spanish_ci NOT NULL,  
  PRIMARY KEY (`cod_estadoden`),
  KEY `cod_denuncia` (`cod_denuncia`),
  KEY `cod_estcarpeta` (`cod_estcarpeta`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


CREATE TABLE `tg_tipdenuncia` (
  `cod_tipdenuncia` int NOT NULL AUTO_INCREMENT,
  `gls_tipdenuncia` varchar(100) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_tipdenuncia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_origencaso` (
  `cod_origencaso` int NOT NULL AUTO_INCREMENT,
  `gls_origencaso` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_origencaso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;











































CREATE TABLE `acceso_modulo` (
  `cod_accesomod` int NOT NULL AUTO_INCREMENT,
  `cod_modulo` int NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` date NOT NULL,  
  `ind_vigencia` int NOT NULL,  
  PRIMARY KEY (`cod_accesomod`),
  KEY `cod_modulo` (`cod_modulo`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_modulo` (
  `cod_modulo` int NOT NULL AUTO_INCREMENT,
  `gls_modulo` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_ubicacioncarp` (
  `cod_ubicacion` int NOT NULL AUTO_INCREMENT,
  `gls_ubicacion` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `fis_codigo` varchar(3) COLLATE latin1_spanish_ci NOT NULL,    
  PRIMARY KEY (`cod_ubicacion`),
  KEY `fis_codigo` (`fis_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_tipubicacion` (
  `cod_tipubicacion` int NOT NULL AUTO_INCREMENT,
  `gls_tipubicacion` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  PRIMARY KEY (`cod_tipubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_tipausencia` (
  `cod_tipausencia` int NOT NULL AUTO_INCREMENT,
  `gls_tipausencia` varchar(500) COLLATE latin1_spanish_ci NOT NULL, 
  `tiempo_ausencia` int NOT NULL,  
  `ind_vigencia` int NOT NULL,  
  PRIMARY KEY (`cod_tipausencia`),
  KEY `ind_vigencia` (`ind_vigencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `ausencia` (
  `cod_ausencia` int NOT NULL AUTO_INCREMENT,  
  `cod_tipausencia` int NOT NULL,  
  `fun_rut` varchar(12) COLLATE latin1_spanish_ci NOT NULL, 
  `fec_registro` date NOT NULL,  
  PRIMARY KEY (`cod_ausencia`),
  KEY `cod_tipausencia` (`cod_tipausencia`),
  KEY `fun_rut` (`fun_rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `doc` (
  `cod_doc` int NOT NULL AUTO_INCREMENT,  
  `gls_doc` int NOT NULL,  
  `idf_rolunico` varchar(15) COLLATE latin1_spanish_ci NOT NULL,  
  PRIMARY KEY (`cod_doc`),
  KEY `idf_rolunico` (`idf_rolunico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;

CREATE TABLE `tg_casillero` (
  `cod_casillero` int NOT NULL AUTO_INCREMENT,
  `gls_casillero` varchar(100) COLLATE latin1_spanish_ci NOT NULL, 
  `ind_vigencia` int NOT NULL,
  `fec_registro` date NOT NULL,
  PRIMARY KEY (`cod_casillero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1;


