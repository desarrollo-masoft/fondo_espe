<?php
class FirmasController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function index10(){
    	 
    	session_start();
    	$id_rol=$_SESSION["id_rol"];
    	$usuarios = new UsuariosModel();
    	$where_to="";
    	$columnas = " usuarios.id_usuarios,
								  usuarios.cedula_usuarios,
								  usuarios.nombre_usuarios,
								  usuarios.clave_usuarios,
								  usuarios.pass_sistemas_usuarios,
								  usuarios.telefono_usuarios,
								  usuarios.celular_usuarios,
								  usuarios.correo_usuarios,
								  rol.id_rol,
								  rol.nombre_rol,
								  estado.id_estado,
								  estado.nombre_estado,
								  usuarios.fotografia_usuarios,
								  usuarios.creado";
    		
    	$tablas   = "public.usuarios,
								  public.rol,
								  public.estado,
    							  public.firmas";
    		
    	$where    = " rol.id_rol = usuarios.id_rol AND
								  estado.id_estado = usuarios.id_estado
    							  AND firmas.id_usuarios = usuarios.id_usuarios	";
    		
    	$id       = "usuarios.id_usuarios";
    		
    	
    	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    	$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
    	
    	
    	
    	
    	if($action == 'ajax')
    	{
    		
    		if(!empty($search)){
    			 
    			 
    			$where1=" AND (usuarios.cedula_usuarios LIKE '".$search."%' OR usuarios.nombre_usuarios LIKE '".$search."%' OR usuarios.correo_usuarios LIKE '".$search."%' OR rol.nombre_rol LIKE '".$search."%' OR estado.nombre_estado LIKE '".$search."%')";
    			 
    			$where_to=$where.$where1;
    		}else{
    		
    			$where_to=$where;
    			 
    		}
    		
    		$html="";
    		$resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
    		$cantidadResult=(int)$resultSet[0]->total;
    		
    		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    		
    		$per_page = 50; //la cantidad de registros que desea mostrar
    		$adjacents  = 9; //brecha entre páginas después de varios adyacentes
    		$offset = ($page - 1) * $per_page;
    		
    		$limit = " LIMIT   '$per_page' OFFSET '$offset'";
    		
    		$resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
    		$count_query   = $cantidadResult;
    		$total_pages = ceil($cantidadResult/$per_page);
    		
    			
    		
    		
    		
    	if($cantidadResult>0)
    	{
    
    		$html.='<div class="pull-left" style="margin-left:11px;">';
    		$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
    		$html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
    		$html.='</div>';
    		$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
			$html.='<section style="height:425px; overflow-y:scroll;">';
    		$html.= "<table id='tabla_usuarios' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
    		$html.= "<thead>";
    		$html.= "<tr>";
    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Teléfono</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Rol</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
    		
    		if($id_rol==1){
	    		
    			$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    		
    		}else{
    			
    			
    			$html.='<th style="text-align: left;  font-size: 12px;"></th>';
    		}
    		
    		$html.='</tr>';
    		$html.='</thead>';
    		$html.='<tbody>';
    		 
    		$i=0;
    		
    		foreach ($resultSet as $res)
    		{
    			$i++;
    			$html.='<tr>';
    			$html.='<td style="font-size: 11px;"><img src="view/DevuelveImagenView.php?id_valor='.$res->id_usuarios.'&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios" width="80" height="60"></td>';
    			$html.='<td style="font-size: 11px;">'.$i.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->cedula_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->telefono_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->celular_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->correo_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->nombre_rol.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
    			
    			if($id_rol==1){
    				
    				$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_usuarios='.$res->id_usuarios.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
    				$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=borrarId&id_usuarios='.$res->id_usuarios.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
    				$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
    				
    				
    			}else{
    				
    				$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
    				
    			}
    			
    				$html.='</tr>';
    		}
    		
    		
    		$html.='</tbody>';
    		$html.='</table>';
    		$html.='</section></div>';
    		$html.='<div class="table-pagination pull-right">';
    		$html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents).'';
    		$html.='</div>';
    		
    		
    		 
    	}else{
    		$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
    		$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
    		$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    		$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
    		$html.='</div>';
    		$html.='</div>';
    	}
    	
    	
    	echo $html;
    	die();
    	 
    	} 
    	 
    	 
    }
    
    
    
    
    
       public function search(){
       	
       	session_start();
       	$usuarios = new UsuariosModel();
       	
       	
       	if (isset(  $_SESSION['nombre_usuarios']) )
       	{
       	
       		$participe = new ParticipeModel();
       		
       		$resultParticipe="";
       		
       		$sexo= new SexoModel();
       		$resultSexo = $sexo->getAll("nombre_sexo");
       		
       		$estado_civil= new Estado_civilModel();
       		$resultEstado_civil = $estado_civil->getAll("nombre_estado_civil");
       		
       		$tipo_sangre= new Tipo_sangreModel();
       		$resultTipo_sangre = $tipo_sangre->getAll("nombre_tipo_sangre");
       		
       		$estado = new EstadoModel();
       		$resultEstado= $estado->getAll("nombre_estado");
       		
       		$entidades = new EntidadesModel();
       		$resultEntidades= $entidades->getAll("nombre_entidades");
       		
       		$provincias = new ProvinciasModel();
       		$resultProvincias= $provincias->getAll("nombre_provincias");
       		
       		$parroquias = new ParroquiasModel();
       		$resultParroquias= $parroquias->getAll("nombre_parroquias");
       		
       		$cantones = new CantonesModel();
       		$resultCantones= $cantones->getAll("nombre_cantones");
       		
       		
       		
       		$nombre_controladores = "Usuarios";
       		$id_rol= $_SESSION['id_rol'];
       		$resultPer = $usuarios->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
       		
       		if (!empty($resultPer))
       		{
       		
       		
		       	if(isset($_GET["cedula"])){
		       		
		       		$cedula_participe=$_GET["cedula"];
		       		
		       		if(!empty($cedula_participe)){
		       			
		       		
		       			$usuarios->registrarSesionParticipe($cedula_participe);
		       			
		       			$columnas_participe="afiliado_extras.id_afiliado_extras,
									  afiliado_extras.cedula,
									  afiliado_extras.nombre,
									  afiliado_extras.direccion,
									  afiliado_extras.telefono,
									  afiliado_extras.celular,
									  afiliado_extras.correo,
									  afiliado_extras.edad,
									  afiliado_extras.hijos,
									  afiliado_extras.sueldo,
									  afiliado_extras.fecha_ingreso,
									  afiliado_extras.estado,
									  afiliado_extras.labor,
									  afiliado_extras.observacion,
									  afiliado_extras.estado_activacion,
									  afiliado_extras.clave,
									  afiliado_extras.administrador,
									  afiliado_extras.id_afiliado,
									  afiliado_extras.id_provincias_vivienda,
									  afiliado_extras.id_cantones_vivienda,
									  afiliado_extras.id_parroquias_vivienda,
									  afiliado_extras.id_provincias_asignacion,
									  afiliado_extras.id_cantones_asignacion,
									  afiliado_extras.id_parroquias_asignacion,
									  afiliado_extras.id_sexo,
									  afiliado_extras.id_tipo_sangre,
									  afiliado_extras.id_estado_civil,
									  afiliado_extras.id_entidades,
									  afiliado_extras.id_estado";
		       			$tablas_participe="public.afiliado_extras";
		       			$where_participe="afiliado_extras.cedula='$cedula_participe'";
		       			$id_participe="afiliado_extras.cedula";
		       			$resultParticipe=$participe->getCondiciones($columnas_participe, $tablas_participe, $where_participe, $id_participe);
		       			
		       		}
		       		
		       	}
		       	
		       	
		       	
		       	$this->view("ConsultasCuentaIndividualAdmin",array(
		       			 "resultSexo"=>$resultSexo, "resultEstado_civil"=>$resultEstado_civil, "resultTipo_sangre"=>$resultTipo_sangre, "resultEstado"=>$resultEstado, "resultEntidades"=>$resultEntidades,
					    "resultProvincias"=>$resultProvincias,
						"resultParroquias"=>$resultParroquias, "resultCantones"=>$resultCantones, 
						"resultParticipe"=>$resultParticipe
		       	
		       	));
		       	
	       	
       	 }else{
       	 	
       	 	$this->view("Error",array(
       	 			"resultado"=>"No tiene Permisos de Acceso a Consultas"
		
       	 	));
       	 	
       	 }
	       
	       
       }else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
 
       }
    
       
       
       
       
       
       
       
        
    
    
public function index(){
	
		session_start();
		if (isset(  $_SESSION['nombre_usuarios']) )
		{
				//Creamos el objeto usuario
			$rol=new RolesModel();
			$resultRol = $rol->getAll("nombre_rol");
			$resultSet="";
			$estado = new EstadoModel();
			$resultEst = $estado->getAll("nombre_estado");
			
			$usuarios = new UsuariosModel();

			$nombre_controladores = "Usuarios";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $usuarios->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
			
					
					$resultEdit = "";
			
					if (isset ($_GET["id_usuarios"])   )
					{
						
						
						$columnas = " usuarios.id_usuarios,
								  usuarios.cedula_usuarios,
								  usuarios.nombre_usuarios,
								  usuarios.clave_usuarios,
								  usuarios.pass_sistemas_usuarios,
								  usuarios.telefono_usuarios,
								  usuarios.celular_usuarios,
								  usuarios.correo_usuarios,
								  rol.id_rol,
								  rol.nombre_rol,
								  estado.id_estado,
								  estado.nombre_estado,
								  usuarios.fotografia_usuarios,
								  usuarios.creado";
						
						$tablas   = "public.usuarios,
								  public.rol,
								  public.estado";
						
						$id       = "usuarios.id_usuarios";
						
						$_id_usuarios = $_GET["id_usuarios"];
						$where    = "rol.id_rol = usuarios.id_rol AND estado.id_estado = usuarios.id_estado AND usuarios.id_usuarios = '$_id_usuarios' "; 
						$resultEdit = $usuarios->getCondiciones($columnas ,$tablas ,$where, $id); 
					}
			
					
					$this->view("Firmas",array(
							"resultSet"=>$resultSet, "resultRol"=>$resultRol, "resultEdit" =>$resultEdit, "resultEst"=>$resultEst
				
					));
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Usuarios"
			
				));
			
			}
			
		
		}
		else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
		
	}
	
	
	
	

	
	
	
	public function InsertaFirmas(){
			
		session_start();
		$resultado = null;
		$usuarios=new UsuariosModel();
		
		if (isset(  $_SESSION['id_usuarios']) )
		{
	
		if (isset ($_POST["id_usuarios"]))
		{
			
		    $_id_usuarios          = $_POST["id_usuarios"];
		    
		    
		    if($_id_usuarios > 0){
		    	
		    	
		    	if ($_FILES['fotografia_usuarios']['tmp_name']!="")
		    	{
		    			
		    		$directorio = $_SERVER['DOCUMENT_ROOT'].'/webcapremci/fotografias_usuarios/';
		    			
		    		$nombre = $_FILES['fotografia_usuarios']['name'];
		    		$tipo = $_FILES['fotografia_usuarios']['type'];
		    		$tamano = $_FILES['fotografia_usuarios']['size'];
		    			
		    		move_uploaded_file($_FILES['fotografia_usuarios']['tmp_name'],$directorio.$nombre);
		    		$data = file_get_contents($directorio.$nombre);
		    		$_archivo_firmas = pg_escape_bytea($data);
		    			
		    		
		    		
		    		
		    		$funcion = "public.ins_firmas";
		    			
		    		$parametros = " '$_archivo_firmas' , '$_id_usuarios' ";
		    		$usuarios->setFuncion($funcion);
		    		
		    		$usuarios->setParametros($parametros);
		    		
		    		
		    		$resultado=$usuarios->Insert();
		    		
		    			
		    	}
		    	else
		    	{
		    	
		    	
		    	}
		    	
		    	
		    	
		    }else{
		    
		    	
		    	
		    	
		     }
		  
		  
		  
		 
		  	  
		   
		    $this->redirect("Firmas", "index");
		}
		
	   }else{
	   	
	   	$error = TRUE;
	   	$mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	   		
	   	$this->view("Login",array(
	   			"resultSet"=>"$mensaje", "error"=>$error
	   	));
	   		
	   		
	   	die();
	   	
	   }
	}
	
	   
    
    
    public function  sesion_caducada()
    {
    	session_start();
    	session_destroy();
    
    	$error = TRUE;
	    $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	    	
	    $this->view("Login",array(
	    		"resultSet"=>"$mensaje", "error"=>$error
	    ));
	    	
	    die();
	    		
    
    }
    
    
	public function  cerrar_sesion ()
	{
		session_start();
		session_destroy();
		
		$error = TRUE;
		$mensaje = "Te has desconectado de nuestro sistema.";
		 
		 
		$this->view("Login",array(
				"resultSet"=>"$mensaje", "error"=>$error
		));
		 
		 
		die();
		
		
	}
	
	
	
	
	
	
	
	
	public function paginate($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_usuarios(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_usuarios(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_usuarios(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	public function paginate_cuenta_individual($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_cta_individual(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_cta_individual(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_cta_individual(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_cta_individual(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_cta_individual(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_cta_individual($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_cta_individual(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	public function paginate_cuenta_desembolsar($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_cta_desembolsar(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_cta_desembolsar(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_cta_desembolsar(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_cta_desembolsar(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_cta_desembolsar(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_cta_desembolsar($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_cta_desembolsar(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	public function paginate_credito_ordinario($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_ordinario(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_ordinario(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_ordinario(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_ordinario(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_ordinario(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_ordinario($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_ordinario(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	public function paginate_credito_emergente($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_emergente(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_emergente(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_emergente(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_emergente(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_emergente(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_emergente($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_emergente(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	public function paginate_credito_2x1($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_2x1(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_2x1(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_2x1(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_2x1(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_2x1(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_2x1($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_2x1(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	public function paginate_credito_hipotecario($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_hipotecario(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_hipotecario(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_hipotecario(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_hipotecario(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_credito_hipotecario(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_credito_hipotecario($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_credito_hipotecario(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	
	public function paginate_acuerdo_pago($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_acuerdo_pago(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_acuerdo_pago(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_acuerdo_pago(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_acuerdo_pago(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_acuerdo_pago(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_acuerdo_pago($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_acuerdo_pago(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	public function paginate_credito_refinanciamiento($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_refinanciamiento(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_refinanciamiento(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_refinanciamiento(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_refinanciamiento(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_refinanciamiento(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_refinanciamiento($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_refinanciamiento(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	///////////////////////////////////////////////// informacion de participes ///////
	
	
	
	public function alerta_actualizacion(){
	
		session_start();
		$i=0;
		$usuarios = new UsuariosModel();
	
		
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			
			$columnas = "usuarios.id_usuarios, usuarios.cedula_usuarios, usuarios.nombre_usuarios, usuarios.correo_usuarios";
			 
			$tablas   = "public.usuarios";
			 
			$where    = " 1=1 AND usuarios.cedula_usuarios='$cedula_usuarios'";
			 
			$id       = "usuarios.id_usuarios";
			
			
			
			$resultSet = $usuarios->getCondiciones($columnas ,$tablas ,$where, $id);
	
			$i=count($resultSet);
				
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {
					$_id_usuarios=$resultSet[0]->id_usuarios;
					$_cedula_usuarios=$resultSet[0]->cedula_usuarios;
					$_nombre_usuarios=$resultSet[0]->nombre_usuarios;
					$_correo_usuarios=$resultSet[0]->correo_usuarios;
					
						
				}
	

				$html .= "<div class='col-md-4 col-sm-6 col-xs-12'>";
				$html .= "<div class='info-box'>";
				$html .= "<span class='info-box-icon bg-aqua'><img src='view/DevuelveImagenView.php?id_valor=$_id_usuarios&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios' width='80' height='80'></span>";
				$html .= "<div class='info-box-content'>";
				$html .= "<span class='info-box-text'>Hola <strong>$_nombre_usuarios</strong><br> ayudanos actualizando tu información<br> personal.</span>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	

				$html .= "<div class='col-md-4 col-sm-6 col-xs-12'>";
				$html .= "<div class='info-box'>";
				$html .= "<span class='info-box-icon bg-aqua'><i class='ion ion-person-add'></i></span>";
				$html .= "<div class='info-box-content'>";
				$html .= "<span class='info-box-text'>Debes iniciar sesión.</span>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	
	
	
	public function cargar_cta_individual(){
	
		session_start();
		$i=0;
		$afiliado_transacc_cta_ind = new Afiliado_transacc_cta_indModel();
		
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
		
		if(!empty($cedula_usuarios)){
		$columnas_ind_mayor = "sum(valorper+valorpat) as total, max(fecha_conta) as fecha";
		$tablas_ind_mayor="afiliado_transacc_cta_ind";
		$where_ind_mayor="cedula='$cedula_usuarios'";
		$resultSet=$afiliado_transacc_cta_ind->getCondicionesValorMayor($columnas_ind_mayor, $tablas_ind_mayor, $where_ind_mayor);
				
	
		$i=count($resultSet);
		$fecha="";
		$total= 0.00;
		$html="";
		if($i>0)
		{
			if (!empty($resultSet)) {  foreach($resultSet as $res) {
				$fecha=$res->fecha;
				$total= number_format($res->total, 2, '.', ',');
			}}else{
					
				$fecha="";
				$total= 0.00;
			
			}
	
			
	
			
			$html .= "<div class='col-md-4 col-sm-6 col-xs-12'>";
			$html .= "<div class='info-box'>";
			$html .= "<span class='info-box-icon bg-red'><i class='ion ion-pie-graph'></i></span>";
			$html .= "<div class='info-box-content'>";
			$html .= "<span class='info-box-number'>$total</span>";
			$html .= "<span class='info-box-text'>Cuenta Individual Actualizada<br> al $fecha.</span>";
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			
			
			
	
		}else{
			
			
			
			$html .= "<div class='col-md-4 col-sm-6 col-xs-12'>";
			$html .= "<div class='info-box'>";
			$html .= "<span class='info-box-icon bg-red'><i class='ion ion-pie-graph'></i></span>";
			$html .= "<div class='info-box-content'>";
			$html .= "<span class='info-box-number'>$total</span>";
			$html .= "<span class='info-box-text'>Actualmente no dispone cuenta<br> individual.</span>";
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			 
			
		}
	
		echo $html;
		die();
	
		}
		else{
			
			
			
			$this->redirect("Usuarios","sesion_caducada");
			
			die();
			
		}
	
	
	}
	
	
	
	
	
	
	public function cargar_cta_desembolsar(){
	
		session_start();
		$i=0;
		$afiliado_transacc_cta_desemb = new Afiliado_transacc_cta_desembModel();
		
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_desemb_mayor = "sum(valorper+valorpat) as total, max(fecha_conta) as fecha";
			$tablas_desemb_mayor="afiliado_transacc_cta_desemb";
			$where_desemb_mayor="cedula='$cedula_usuarios'";
			$resultSet=$afiliado_transacc_cta_desemb->getCondicionesValorMayor($columnas_desemb_mayor, $tablas_desemb_mayor, $where_desemb_mayor);
				
	
			$i=count($resultSet);
			$fecha="";
			$total= 0.00;
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {  foreach($resultSet as $res) {
					$fecha=$res->fecha;
					$total= number_format($res->total, 2, '.', ',');
				}}else{
						
					$fecha="";
					$total= 0.00;
						
				}
				
				
				$html .= "<div class='col-md-4 col-sm-6 col-xs-12'>";
				$html .= "<div class='info-box'>";
				$html .= "<span class='info-box-icon bg-yellow'><i class='ion ion-stats-bars'></i></span>";
				$html .= "<div class='info-box-content'>";
				$html .= "<span class='info-box-number'>$total</span>";
				$html .= "<span class='info-box-text'>Cuenta Desembolsar Actualizada<br> al $fecha.</span>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
	
				
	
	
			}else{
	
				
				$html .= "<div class='col-md-4 col-sm-6 col-xs-12'>";
				$html .= "<div class='info-box'>";
				$html .= "<span class='info-box-icon bg-yellow'><i class='ion ion-stats-bars'></i></span>";
				$html .= "<div class='info-box-content'>";
				$html .= "<span class='info-box-number'>$total</span>";
				$html .= "<span class='info-box-text'>Actualmente no dispone Cuenta<br> Por Desembolsar.</span>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
				
			
				
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
				
				
				
			$this->redirect("Usuarios","sesion_caducada");
				
			die();
				
		}
	
	
	
	
	
	}
	
	
	
	
	
	
	
	public function cargar_credito_ordinario(){
	
		session_start();
		$i=0;
		$ordinario_solicitud = new Ordinario_SolicitudModel();
		$ordinario_detalle = new Ordinario_DetalleModel();
	
		$_numsol_ordinario="";
		$_cuota_ordinario="";
		$_interes_ordinario="";
		$_tipo_ordinario="";
		$_plazo_ordinario="";
		$_fcred_ordinario="";
		$_ffin_ordinario="";
		$_cuenta_ordinario="";
		$_banco_ordinario="";
		$_valor_ordinario="";
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_ordi_cabec ="*";
			$tablas_ordi_cabec="ordinario_solicitud";
			$where_ordi_cabec="cedula='$cedula_usuarios'";
			$id_ordi_cabec="cedula";
			$resultSet=$ordinario_solicitud->getCondicionesDesc($columnas_ordi_cabec, $tablas_ordi_cabec, $where_ordi_cabec, $id_ordi_cabec);
			
	
			$i=count($resultSet);
			
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) { 
					$_numsol_ordinario=$resultSet[0]->numsol;
					$_cuota_ordinario=$resultSet[0]->cuota;
					$_interes_ordinario=$resultSet[0]->interes;
					$_tipo_ordinario=$resultSet[0]->tipo;
					$_plazo_ordinario=$resultSet[0]->plazo;
					$_fcred_ordinario=$resultSet[0]->fcred;
					$_ffin_ordinario=$resultSet[0]->ffin;
					$_cuenta_ordinario=$resultSet[0]->cuenta;
					$_banco_ordinario=$resultSet[0]->banco;
					$_valor_ordinario=number_format($resultSet[0]->valor, 2, '.', ',');
					
				}
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-red'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>$_valor_ordinario</h3>";
				$html .= "<p>Tienes activo un crédito ordinario<br> desde $_fcred_ordinario hasta $_ffin_ordinario.</p>";
				$html .= "</div>";
	
	
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-red'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>0.00</h3>";
				$html .= "<p>Actualmente no dispone un crédito<br> ordinario.</p>";
				$html .= "</div>";
					
					
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	
	
	
	
	
	
	public function cargar_credito_emergente(){
	
		session_start();
		$i=0;
		$emergente_solicitud = new Emergente_SolicitudModel();
		$emergente_detalle = new Emergente_DetalleModel();
	
		$_numsol_emergente="";
		$_cuota_emergente="";
		$_interes_emergente="";
		$_tipo_emergente="";
		$_plazo_emergente="";
		$_fcred_emergente="";
		$_ffin_emergente="";
		$_cuenta_emergente="";
		$_banco_emergente="";
		$_valor_emergente="";
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_emer_cabec ="*";
				$tablas_emer_cabec="emergente_solicitud";
				$where_emer_cabec="cedula='$cedula_usuarios'";
				$id_emer_cabec="cedula";
				$resultSet=$emergente_solicitud->getCondicionesDesc($columnas_emer_cabec, $tablas_emer_cabec, $where_emer_cabec, $id_emer_cabec);
					
	
			$i=count($resultSet);
				
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {
					
					$_numsol_emergente=$resultSet[0]->numsol;
					$_cuota_emergente=$resultSet[0]->cuota;
					$_interes_emergente=$resultSet[0]->interes;
					$_tipo_emergente=$resultSet[0]->tipo;
					$_plazo_emergente=$resultSet[0]->plazo;
					$_fcred_emergente=$resultSet[0]->fcred;
					$_ffin_emergente=$resultSet[0]->ffin;
					$_cuenta_emergente=$resultSet[0]->cuenta;
					$_banco_emergente=$resultSet[0]->banco;
					$_valor_emergente=number_format($resultSet[0]->valor, 2, '.', ',');
				}
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-yellow'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>$_valor_emergente</h3>";
				$html .= "<p>Tienes activo un crédito emergente<br> desde $_fcred_emergente hasta $_ffin_emergente.</p>";
				$html .= "</div>";
	
	
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-yellow'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>0.00</h3>";
				$html .= "<p>Actualmente no dispone un crédito<br> emergente.</p>";
				$html .= "</div>";
					
					
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	
	
	
	public function cargar_credito_2x1(){
	
		session_start();
		$i=0;
		$c2x1_solicitud = new C2x1_solicitudModel();
		$c2x1_detalle = new C2x1_detalleModel();
	
					$_numsol_2x1="";
					$_cuota_2x1="";
					$_interes_2x1="";
					$_tipo_2x1="";
					$_plazo_2x1="";
					$_fcred_2x1="";
					$_ffin_2x1="";
					$_cuenta_2x1="";
					$_banco_2x1="";
					$_valor_2x1="";
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_2_x_1_cabec ="*";
			$tablas_2_x_1_cabec="c2x1_solicitud";
			$where_2_x_1_cabec="cedula='$cedula_usuarios'";
			$id_2_x_1_cabec="cedula";
			$resultSet=$c2x1_solicitud->getCondicionesDesc($columnas_2_x_1_cabec, $tablas_2_x_1_cabec, $where_2_x_1_cabec, $id_2_x_1_cabec);
			
			
	
			$i=count($resultSet);
	
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {
						
					$_numsol_2x1=$resultSet[0]->numsol;
					$_cuota_2x1=$resultSet[0]->cuota;
					$_interes_2x1=$resultSet[0]->interes;
					$_tipo_2x1=$resultSet[0]->tipo;
					$_plazo_2x1=$resultSet[0]->plazo;
					$_fcred_2x1=$resultSet[0]->fcred;
					$_ffin_2x1=$resultSet[0]->ffin;
					$_cuenta_2x1=$resultSet[0]->cuenta;
					$_banco_2x1=$resultSet[0]->banco;
					$_valor_2x1=number_format($resultSet[0]->valor, 2, '.', ',');
				}
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-aqua'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>$_valor_2x1</h3>";
				$html .= "<p>Tienes activo un crédito 2 X 1<br> desde $_fcred_2x1 hasta $_ffin_2x1.</p>";
				$html .= "</div>";
	
	
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-aqua'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>0.00</h3>";
				$html .= "<p>Actualmente no dispone un crédito<br> 2 X 1.</p>";
				$html .= "</div>";
					
					
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	
	
	
	
	
	
	

	public function cargar_credito_hipotecario(){
	
		session_start();
		$i=0;
		$hipotecario_solicitud = new Hipotecario_SolicitudModel();
		$hipotecario_detalle = new Hipotecario_DetalleModel();
	
		$_numsol_hipotecario="";
		$_cuota_hipotecario="";
		$_interes_hipotecario="";
		$_tipo_hipotecario="";
		$_plazo_hipotecario="";
		$_fcred_hipotecario="";
		$_ffin_hipotecario="";
		$_cuenta_hipotecario="";
		$_banco_hipotecario="";
		$_valor_hipotecario="";
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_hipotecario_cabec ="*";
			$tablas_hipotecario_cabec="hipotecario_solicitud";
			$where_hipotecario_cabec="cedula='$cedula_usuarios'";
			$id_hipotecario_cabec="cedula";
			$resultSet=$hipotecario_solicitud->getCondicionesDesc($columnas_hipotecario_cabec, $tablas_hipotecario_cabec, $where_hipotecario_cabec, $id_hipotecario_cabec);
				
				
	
			$i=count($resultSet);
	
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {
	
					$_numsol_hipotecario=$resultSet[0]->numsol;
					$_cuota_hipotecario=$resultSet[0]->cuota;
					$_interes_hipotecario=$resultSet[0]->interes;
					$_tipo_hipotecario=$resultSet[0]->tipo;
					$_plazo_hipotecario=$resultSet[0]->plazo;
					$_fcred_hipotecario=$resultSet[0]->fcred;
					$_ffin_hipotecario=$resultSet[0]->ffin;
					$_cuenta_hipotecario=$resultSet[0]->cuenta;
					$_banco_hipotecario=$resultSet[0]->banco;
					$_valor_hipotecario=number_format($resultSet[0]->valor, 2, '.', ',');
				}
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-green'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>$_valor_hipotecario</h3>";
				$html .= "<p>Tienes activo un crédito hipotecario<br> desde $_fcred_hipotecario hasta $_ffin_hipotecario.</p>";
				$html .= "</div>";
	
	
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-green'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>0.00</h3>";
				$html .= "<p>Actualmente no dispone un crédito<br> hipotecario.</p>";
				$html .= "</div>";
					
					
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	
	public function cargar_acuerdo_pago(){
	
		session_start();
		$i=0;
		$app_solicitud = new app_solicitudModel();
		$app_detalle = new app_detalleModel();
	
		$_numsol_app="";
		$_cuota_app="";
		$_interes_app="";
		$_tipo_app="";
		$_plazo_app="";
		$_fcred_app="";
		$_ffin_app="";
		$_cuenta_app="";
		$_banco_app="";
		$_valor_app="";
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_app_cabec ="*";
			$tablas_app_cabec="app_solicitud";
			$where_app_cabec="cedula='$cedula_usuarios'";
			$id_app_cabec="cedula";
			$resultSet=$app_solicitud->getCondicionesDesc($columnas_app_cabec, $tablas_app_cabec, $where_app_cabec, $id_app_cabec);
	
	
	
			$i=count($resultSet);
	
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {
	
					$_numsol_app=$resultSet[0]->numsol;
					$_cuota_app=$resultSet[0]->cuota;
					$_interes_app=$resultSet[0]->interes;
					$_tipo_app=$resultSet[0]->tipo;
					$_plazo_app=$resultSet[0]->plazo;
					$_fcred_app=$resultSet[0]->fcred;
					$_ffin_app=$resultSet[0]->ffin;
					$_cuenta_app=$resultSet[0]->cuenta;
					$_banco_app=$resultSet[0]->banco;
					$_valor_app=number_format($resultSet[0]->valor, 2, '.', ',');
	
				}
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-primary'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>$_valor_app</h3>";
				$html .= "<p>Tienes activo un acuerdo de pago<br> desde $_fcred_app hasta $_ffin_app.</p>";
				$html .= "</div>";
	
	
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-primary'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>0.00</h3>";
				$html .= "<p>Actualmente no dispone un acuerdo<br> de pago.</p>";
				$html .= "</div>";
					
					
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	

	public function cargar_credito_refinanciamiento(){
	
		session_start();
		$i=0;
		$refinanciamiento_solicitud = new Refinanciamiento_SolicitudModel();
		$refinanciamiento_detalle = new Refinanciamiento_DetalleModel();
		
	
		$_numsol_refinanciamiento="";
		$_cuota_refinanciamiento="";
		$_interes_refinanciamiento="";
		$_tipo_refinanciamiento="";
		$_plazo_refinanciamiento="";
		$_fcred_refinanciamiento="";
		$_ffin_refinanciamiento="";
		$_cuenta_refinanciamiento="";
		$_banco_refinanciamiento="";
		$_valor_refinanciamiento="";
	
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
	
		if(!empty($cedula_usuarios)){
			$columnas_refi_cabec ="*";
			$tablas_refi_cabec="refinanciamiento_solicitud";
			$where_refi_cabec="cedula='$cedula_usuarios'";
			$id_refi_cabec="cedula";
			$resultSet=$refinanciamiento_solicitud->getCondicionesDesc($columnas_refi_cabec, $tablas_refi_cabec, $where_refi_cabec, $id_refi_cabec);
	
	
	
			$i=count($resultSet);
	
			$html="";
			if($i>0)
			{
				if (!empty($resultSet)) {
	
					$_numsol_refinanciamiento=$resultSet[0]->numsol;
					$_cuota_refinanciamiento=$resultSet[0]->cuota;
					$_interes_refinanciamiento=$resultSet[0]->interes;
					$_tipo_refinanciamiento=$resultSet[0]->tipo;
					$_plazo_refinanciamiento=$resultSet[0]->plazo;
					$_fcred_refinanciamiento=$resultSet[0]->fcred;
					$_ffin_refinanciamiento=$resultSet[0]->ffin;
					$_cuenta_refinanciamiento=$resultSet[0]->cuenta;
					$_banco_refinanciamiento=$resultSet[0]->banco;
					$_valor_refinanciamiento=number_format($resultSet[0]->valor, 2, '.', ',');
				}
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-info'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>$_valor_refinanciamiento</h3>";
				$html .= "<p>Tienes activo un crédito hipotecario<br> desde $_fcred_refinanciamiento hasta $_ffin_refinanciamiento.</p>";
				$html .= "</div>";
	
	
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
	
	
			}else{
	
				$html .= "<div class='col-lg-4 col-xs-12'>";
				$html .= "<div class='small-box bg-info'>";
				$html .= "<div class='inner'>";
				$html .= "<h3>0.00</h3>";
				$html .= "<p>Actualmente no dispone un crédito<br> de refinanciamiento.</p>";
				$html .= "</div>";
					
					
				$html .= "<div class='icon'>";
				$html .= "<i class='ion ion-calendar'></i>";
				$html .= "</div>";
				$html .= "<a href='index.php?controller=SaldosCuentaIndividual&action=index' class='small-box-footer'>Leer Mas<i class='fa fa-arrow-circle-right'></i></a>";
				$html .= "</div>";
				$html .= "</div>";
					
					
			}
	
			echo $html;
			die();
	
		}
		else{
	
	
	
			$this->redirect("Usuarios","sesion_caducada");
	
			die();
	
		}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	////////////////////////////////////////REPORTES /////////////////////////////////////////////////////////////
	
	
	
	
	public function generar_reporte()
	{
	
		session_start();
		$ordinario_detalle = new Ordinario_DetalleModel();
		$ordinario_solicitud = new Ordinario_SolicitudModel();
		$emergente_solicitud = new Emergente_SolicitudModel();
		$emergente_detalle = new Emergente_DetalleModel();
		$c2x1_solicitud = new C2x1_solicitudModel();
		$c2x1_detalle = new C2x1_detalleModel();
		$app_solicitud = new app_solicitudModel();
		$app_detalle = new app_detalleModel();
		$hipotecario_solicitud = new Hipotecario_SolicitudModel();
		$hipotecario_detalle = new Hipotecario_DetalleModel();
		$afiliado_transacc_cta_ind = new Afiliado_transacc_cta_indModel();
		$afiliado_transacc_cta_desemb = new Afiliado_transacc_cta_desembModel();
		$usuarios= new UsuariosModel();
	
		$refinanciamiento_solicitud = new Refinanciamiento_SolicitudModel();
		$refinanciamiento_detalle = new Refinanciamiento_DetalleModel();
	
		$html="";
	
	
	
		$cedula_usuarios = $_SESSION["cedula_participe"];
		$fechaactual = getdate();
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
		 
		$directorio = $_SERVER ['DOCUMENT_ROOT'] . '/webcapremci';
		$dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
		$domLogo=$directorio.'/view/images/lcaprem.png';
		$logo = '<img src="'.$domLogo.'" alt="Responsive image" width="200" height="50">';
		 
	
	
		if(!empty($cedula_usuarios)){
				
	
			if(isset($_GET["credito"])){
	
				$credito=$_GET["credito"];
	
	
				if($credito=="ordinario"){
						
						
					$columnas_ordi_cabec ="*";
					$tablas_ordi_cabec="ordinario_solicitud";
					$where_ordi_cabec="cedula='$cedula_usuarios'";
					$id_ordi_cabec="cedula";
					$resultCredOrdi_Cabec=$ordinario_solicitud->getCondicionesDesc($columnas_ordi_cabec, $tablas_ordi_cabec, $where_ordi_cabec, $id_ordi_cabec);
	
						
	
					if(!empty($resultCredOrdi_Cabec)){
							
						$_numsol_ordinario=$resultCredOrdi_Cabec[0]->numsol;
						$_cuota_ordinario=$resultCredOrdi_Cabec[0]->cuota;
						$_interes_ordinario=$resultCredOrdi_Cabec[0]->interes;
						$_tipo_ordinario=$resultCredOrdi_Cabec[0]->tipo;
						$_plazo_ordinario=$resultCredOrdi_Cabec[0]->plazo;
						$_fcred_ordinario=$resultCredOrdi_Cabec[0]->fcred;
						$_ffin_ordinario=$resultCredOrdi_Cabec[0]->ffin;
						$_cuenta_ordinario=$resultCredOrdi_Cabec[0]->cuenta;
						$_banco_ordinario=$resultCredOrdi_Cabec[0]->banco;
						$_valor_ordinario= number_format($resultCredOrdi_Cabec[0]->valor, 2, '.', ',');
						$_cedula_ordinario=$resultCredOrdi_Cabec[0]->cedula;
						$_nombres_ordinario=$resultCredOrdi_Cabec[0]->nombres;
							
						if($_numsol_ordinario != ""){
	
							$columnas_ordi_detall ="numsol,
										pago,
										mes,
										ano,
										fecpag,ROUND(capital,2) as capital,
										ROUND(interes,2) as interes,
										ROUND(intmor,2) as intmor,
										ROUND(seguros,2) as seguros,
										ROUND(total,2) as total,
										ROUND(saldo,2) as saldo,
										estado";
								
							$tablas_ordi_detall="ordinario_detalle";
							$where_ordi_detall="numsol='$_numsol_ordinario'";
							$id_ordi_detall="pago";
							$resultSet=$ordinario_detalle->getCondiciones($columnas_ordi_detall, $tablas_ordi_detall, $where_ordi_detall, $id_ordi_detall);
	
								
							$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
							$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
							$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CRÉDITO ORDINARIO</b></p>';
	
							$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombres_ordinario.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_ordinario.'</p>';
	
							$html.= "<table style='width: 100%;' border=1 cellspacing=0 >";
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">No de Solicitud:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Monto Concedido:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuota:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Interes:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Tipo:</th>';
							$html.='</tr>';
	
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_numsol_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_valor_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuota_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_interes_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_tipo_ordinario.'</td>';
							$html.='</tr>';
	
	
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">PLazo:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Concedido en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Termina en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuenta No:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Banco:</th>';
							$html.='</tr>';
								
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_plazo_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_fcred_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_ffin_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuenta_ordinario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_banco_ordinario.'</td>';
							$html.='</tr>';
								
							$html.='</table>';
	
	
	
	
							$html.= "<table style='margin-top:20px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
							$html.= "<thead>";
							$html.= "<tr style='background-color: #D5D8DC;'>";
								
							$html.='<th style="text-align: left;  font-size: 12px;">Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">A&ntilde;o</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Capital</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes por Mora</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Seguro Desgr.</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
							$html.='</tr>';
							$html.='</thead>';
							$html.='<tbody>';
	
							$i=0;
							foreach ($resultSet as $res)
							{
								$i++;
								$html.='<tr>';
								$html.='<td style="font-size: 12px;">'.$res->pago.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->mes.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->ano.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->fecpag.'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->capital, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->interes, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->intmor, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->seguros, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->total, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->saldo, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.$res->estado.'</td>';
								$html.='</tr>';
							}
	
							$html.='</tbody>';
							$html.='</table>';
	
						}
							
					}
	
						
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
						
						
						
				}elseif ($credito=="emergente"){
						
						
					$columnas_emer_cabec ="*";
					$tablas_emer_cabec="emergente_solicitud";
					$where_emer_cabec="cedula='$cedula_usuarios'";
					$id_emer_cabec="cedula";
					$resultCredEmer_Cabec=$emergente_solicitud->getCondicionesDesc($columnas_emer_cabec, $tablas_emer_cabec, $where_emer_cabec, $id_emer_cabec);
						
						
						
	
						
					if(!empty($resultCredEmer_Cabec)){
							
						$_numsol_emergente=$resultCredEmer_Cabec[0]->numsol;
						$_cuota_emergente=$resultCredEmer_Cabec[0]->cuota;
						$_interes_emergente=$resultCredEmer_Cabec[0]->interes;
						$_tipo_emergente=$resultCredEmer_Cabec[0]->tipo;
						$_plazo_emergente=$resultCredEmer_Cabec[0]->plazo;
						$_fcred_emergente=$resultCredEmer_Cabec[0]->fcred;
						$_ffin_emergente=$resultCredEmer_Cabec[0]->ffin;
						$_cuenta_emergente=$resultCredEmer_Cabec[0]->cuenta;
						$_banco_emergente=$resultCredEmer_Cabec[0]->banco;
						$_valor_emergente= number_format($resultCredEmer_Cabec[0]->valor, 2, '.', ',');
						$_cedula_emergente=$resultCredEmer_Cabec[0]->cedula;
						$_nombres_emergente=$resultCredEmer_Cabec[0]->nombres;
							
						if($_numsol_emergente != ""){
								
							$columnas_emer_detall ="numsol,
										pago,
										mes,
										ano,
										fecpag,ROUND(capital,2) as capital,
										ROUND(interes,2) as interes,
										ROUND(intmor,2) as intmor,
										ROUND(seguros,2) as seguros,
										ROUND(total,2) as total,
										ROUND(saldo,2) as saldo,
										estado";
	
							$tablas_emer_detall="emergente_detalle";
							$where_emer_detall="numsol='$_numsol_emergente'";
							$id_emer_detall="pago";
								
							$resultSet=$emergente_detalle->getCondiciones($columnas_emer_detall, $tablas_emer_detall, $where_emer_detall, $id_emer_detall);
								
	
							$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
							$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
							$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CRÉDITO EMERGENTE</b></p>';
								
							$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombres_emergente.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_emergente.'</p>';
								
							$html.= "<table style='width: 100%;' border=1 cellspacing=0 >";
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">No de Solicitud:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Monto Concedido:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuota:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Interes:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Tipo:</th>';
							$html.='</tr>';
								
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_numsol_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_valor_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuota_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_interes_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_tipo_emergente.'</td>';
							$html.='</tr>';
								
								
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">PLazo:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Concedido en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Termina en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuenta No:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Banco:</th>';
							$html.='</tr>';
	
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_plazo_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_fcred_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_ffin_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuenta_emergente.'</td>';
							$html.='<td style="font-size: 13px;">'.$_banco_emergente.'</td>';
							$html.='</tr>';
	
							$html.='</table>';
								
								
								
								
							$html.= "<table style='margin-top:20px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
							$html.= "<thead>";
							$html.= "<tr style='background-color: #D5D8DC;'>";
	
							$html.='<th style="text-align: left;  font-size: 12px;">Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">A&ntilde;o</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Capital</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes por Mora</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Seguro Desgr.</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
							$html.='</tr>';
							$html.='</thead>';
							$html.='<tbody>';
								
							$i=0;
							foreach ($resultSet as $res)
							{
								$i++;
								$html.='<tr>';
								$html.='<td style="font-size: 12px;">'.$res->pago.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->mes.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->ano.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->fecpag.'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->capital, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->interes, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->intmor, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->seguros, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->total, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->saldo, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.$res->estado.'</td>';
								$html.='</tr>';
							}
								
							$html.='</tbody>';
							$html.='</table>';
								
						}
							
					}
						
	
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
						
						
				}elseif ($credito=="2_x_1"){
						
	
					$columnas_2_x_1_cabec ="*";
					$tablas_2_x_1_cabec="c2x1_solicitud";
					$where_2_x_1_cabec="cedula='$cedula_usuarios'";
					$id_2_x_1_cabec="cedula";
					$resultCred2_x_1_Cabec=$c2x1_solicitud->getCondicionesDesc($columnas_2_x_1_cabec, $tablas_2_x_1_cabec, $where_2_x_1_cabec, $id_2_x_1_cabec);
	
						
	
					if(!empty($resultCred2_x_1_Cabec)){
							
						$_numsol_2x1=$resultCred2_x_1_Cabec[0]->numsol;
						$_cuota_2x1=$resultCred2_x_1_Cabec[0]->cuota;
						$_interes_2x1=$resultCred2_x_1_Cabec[0]->interes;
						$_tipo_2x1=$resultCred2_x_1_Cabec[0]->tipo;
						$_plazo_2x1=$resultCred2_x_1_Cabec[0]->plazo;
						$_fcred_2x1=$resultCred2_x_1_Cabec[0]->fcred;
						$_ffin_2x1=$resultCred2_x_1_Cabec[0]->ffin;
						$_cuenta_2x1=$resultCred2_x_1_Cabec[0]->cuenta;
						$_banco_2x1=$resultCred2_x_1_Cabec[0]->banco;
						$_valor_2x1= number_format($resultCred2_x_1_Cabec[0]->valor, 2, '.', ',');
						$_cedula_2x1=$resultCred2_x_1_Cabec[0]->cedula;
						$_nombres_2x1=$resultCred2_x_1_Cabec[0]->nombres;
							
						if($_numsol_2x1 != ""){
	
	
							$columnas_2_x_1_detall ="numsol,
										pago,
										mes,
										ano,
										fecpag,ROUND(capital,2) as capital,
										ROUND(interes,2) as interes,
										ROUND(intmor,2) as intmor,
										ROUND(seguros,2) as seguros,
										ROUND(total,2) as total,
										ROUND(saldo,2) as saldo,
										estado";
							$tablas_2_x_1_detall="c2x1_detalle";
							$where_2_x_1_detall="numsol='$_numsol_2x1'";
							$id_2_x_1_detall="pago";
							$resultSet=$c2x1_detalle->getCondiciones($columnas_2_x_1_detall, $tablas_2_x_1_detall, $where_2_x_1_detall, $id_2_x_1_detall);
	
								
							$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
							$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
							$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CRÉDITO 2 X 1</b></p>';
	
							$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombres_2x1.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_2x1.'</p>';
	
							$html.= "<table style='width: 100%;' border=1 cellspacing=0 >";
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">No de Solicitud:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Monto Concedido:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuota:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Interes:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Tipo:</th>';
							$html.='</tr>';
	
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_numsol_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_valor_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuota_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_interes_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_tipo_2x1.'</td>';
							$html.='</tr>';
	
	
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">PLazo:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Concedido en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Termina en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuenta No:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Banco:</th>';
							$html.='</tr>';
								
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_plazo_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_fcred_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_ffin_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuenta_2x1.'</td>';
							$html.='<td style="font-size: 13px;">'.$_banco_2x1.'</td>';
							$html.='</tr>';
								
							$html.='</table>';
	
	
	
	
							$html.= "<table style='margin-top:20px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
							$html.= "<thead>";
							$html.= "<tr style='background-color: #D5D8DC;'>";
								
							$html.='<th style="text-align: left;  font-size: 12px;">Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">A&ntilde;o</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Capital</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes por Mora</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Seguro Desgr.</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
							$html.='</tr>';
							$html.='</thead>';
							$html.='<tbody>';
	
							$i=0;
							foreach ($resultSet as $res)
							{
								$i++;
								$html.='<tr>';
								$html.='<td style="font-size: 12px;">'.$res->pago.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->mes.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->ano.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->fecpag.'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->capital, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->interes, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->intmor, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->seguros, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->total, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->saldo, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.$res->estado.'</td>';
								$html.='</tr>';
							}
	
							$html.='</tbody>';
							$html.='</table>';
	
						}
							
					}
	
						
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
	
	
	
						
						
						
						
				}elseif ($credito=="acuerdo_pago"){
						
						
					$columnas_app_cabec ="*";
					$tablas_app_cabec="app_solicitud";
					$where_app_cabec="cedula='$cedula_usuarios'";
					$id_app_cabec="cedula";
					$resultCredApp_Cabec=$app_solicitud->getCondicionesDesc($columnas_app_cabec, $tablas_app_cabec, $where_app_cabec, $id_app_cabec);
	
	
	
						
					if(!empty($resultCredApp_Cabec)){
							
						$_numsol_app=$resultCredApp_Cabec[0]->numsol;
						$_cuota_app=$resultCredApp_Cabec[0]->cuota;
						$_interes_app=$resultCredApp_Cabec[0]->interes;
						$_tipo_app=$resultCredApp_Cabec[0]->tipo;
						$_plazo_app=$resultCredApp_Cabec[0]->plazo;
						$_fcred_app=$resultCredApp_Cabec[0]->fcred;
						$_ffin_app=$resultCredApp_Cabec[0]->ffin;
						$_cuenta_app=$resultCredApp_Cabec[0]->cuenta;
						$_banco_app=$resultCredApp_Cabec[0]->banco;
						$_valor_app= number_format($resultCredApp_Cabec[0]->valor, 2, '.', ',');
						$_cedula_app=$resultCredApp_Cabec[0]->cedula;
						$_nombres_app=$resultCredApp_Cabec[0]->nombres;
							
						if($_numsol_app != ""){
								
								
							$columnas_app_detall ="numsol,
										pago,
										mes,
										ano,
										fecpag,ROUND(capital,2) as capital,
										ROUND(interes,2) as interes,
										ROUND(intmor,2) as intmor,
										ROUND(seguros,2) as seguros,
										ROUND(total,2) as total,
										ROUND(saldo,2) as saldo,
										estado";
								
							$tablas_app_detall="app_detalle";
							$where_app_detall="numsol='$_numsol_app'";
							$id_app_detall="pago";
							$resultSet=$app_detalle->getCondiciones($columnas_app_detall, $tablas_app_detall, $where_app_detall, $id_app_detall);
								
	
							$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
							$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
							$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE ACUERDO DE PAGO</b></p>';
								
							$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombres_app.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_app.'</p>';
								
							$html.= "<table style='width: 100%;' border=1 cellspacing=0 >";
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">No de Solicitud:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Monto Concedido:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuota:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Interes:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Tipo:</th>';
							$html.='</tr>';
								
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_numsol_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_valor_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuota_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_interes_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_tipo_app.'</td>';
							$html.='</tr>';
								
								
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">PLazo:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Concedido en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Termina en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuenta No:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Banco:</th>';
							$html.='</tr>';
	
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_plazo_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_fcred_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_ffin_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuenta_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_banco_app.'</td>';
							$html.='</tr>';
	
							$html.='</table>';
								
								
								
								
							$html.= "<table style='margin-top:20px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
							$html.= "<thead>";
							$html.= "<tr style='background-color: #D5D8DC;'>";
	
							$html.='<th style="text-align: left;  font-size: 12px;">Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">A&ntilde;o</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Capital</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes por Mora</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Seguro Desgr.</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
							$html.='</tr>';
							$html.='</thead>';
							$html.='<tbody>';
								
							$i=0;
							foreach ($resultSet as $res)
							{
								$i++;
								$html.='<tr>';
								$html.='<td style="font-size: 12px;">'.$res->pago.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->mes.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->ano.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->fecpag.'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->capital, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->interes, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->intmor, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->seguros, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->total, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->saldo, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.$res->estado.'</td>';
								$html.='</tr>';
							}
								
							$html.='</tbody>';
							$html.='</table>';
								
						}
							
					}
						
	
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
						
						
	
						
						
						
						
						
				}elseif ($credito=="hipotecario"){
						
						
	
					$columnas_hipo_cabec ="*";
					$tablas_hipo_cabec="hipotecario_solicitud";
					$where_hipo_cabec="cedula='$cedula_usuarios'";
					$id_hipo_cabec="cedula";
					$resultCredHipo_Cabec=$hipotecario_solicitud->getCondicionesDesc($columnas_hipo_cabec, $tablas_hipo_cabec, $where_hipo_cabec, $id_hipo_cabec);
						
						
						
	
					if(!empty($resultCredHipo_Cabec)){
							
						$_numsol_hipotecario=$resultCredHipo_Cabec[0]->numsol;
						$_cuota_hipotecario=$resultCredHipo_Cabec[0]->cuota;
						$_interes_hipotecario=$resultCredHipo_Cabec[0]->interes;
						$_tipo_hipotecario=$resultCredHipo_Cabec[0]->tipo;
						$_plazo_hipotecario=$resultCredHipo_Cabec[0]->plazo;
						$_fcred_hipotecario=$resultCredHipo_Cabec[0]->fcred;
						$_ffin_hipotecario=$resultCredHipo_Cabec[0]->ffin;
						$_cuenta_hipotecario=$resultCredHipo_Cabec[0]->cuenta;
						$_banco_hipotecario=$resultCredHipo_Cabec[0]->banco;
						$_valor_hipotecario= number_format($resultCredHipo_Cabec[0]->valor, 2, '.', ',');
						$_cedula_hipotecario=$resultCredHipo_Cabec[0]->cedula;
						$_nombres_hipotecario=$resultCredHipo_Cabec[0]->nombres;
							
						if($_numsol_hipotecario != ""){
	
	
							$columnas_hipo_detall ="numsol,
										pago,
										mes,
										ano,
										fecpag,ROUND(capital,2) as capital,
										ROUND(interes,2) as interes,
										ROUND(intmor,2) as intmor,
										ROUND(seguros,2) as seguros,
										ROUND(total,2) as total,
										ROUND(saldo,2) as saldo,
										estado";
								
							$tablas_hipo_detall="hipotecario_detalle";
							$where_hipo_detall="numsol='$_numsol_hipotecario'";
							$id_hipo_detall="pago";
							$resultSet=$hipotecario_detalle->getCondiciones($columnas_hipo_detall, $tablas_hipo_detall, $where_hipo_detall, $id_hipo_detall);
	
								
							$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
							$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
							$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CRÉDITO HIPOTECARIO</b></p>';
	
							$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombres_hipotecario.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_hipotecario.'</p>';
	
							$html.= "<table style='width: 100%;' border=1 cellspacing=0 >";
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">No de Solicitud:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Monto Concedido:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuota:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Interes:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Tipo:</th>';
							$html.='</tr>';
	
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_numsol_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_valor_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuota_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_interes_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_tipo_hipotecario.'</td>';
							$html.='</tr>';
	
	
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">PLazo:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Concedido en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Termina en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuenta No:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Banco:</th>';
							$html.='</tr>';
								
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_plazo_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_fcred_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_ffin_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuenta_hipotecario.'</td>';
							$html.='<td style="font-size: 13px;">'.$_banco_hipotecario.'</td>';
							$html.='</tr>';
								
							$html.='</table>';
	
	
	
	
							$html.= "<table style='margin-top:20px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
							$html.= "<thead>";
							$html.= "<tr style='background-color: #D5D8DC;'>";
								
							$html.='<th style="text-align: left;  font-size: 12px;">Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">A&ntilde;o</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Capital</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes por Mora</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Seguro Desgr.</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
							$html.='</tr>';
							$html.='</thead>';
							$html.='<tbody>';
	
							$i=0;
							foreach ($resultSet as $res)
							{
								$i++;
								$html.='<tr>';
								$html.='<td style="font-size: 12px;">'.$res->pago.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->mes.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->ano.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->fecpag.'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->capital, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->interes, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->intmor, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->seguros, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->total, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->saldo, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.$res->estado.'</td>';
								$html.='</tr>';
							}
	
							$html.='</tbody>';
							$html.='</table>';
	
						}
							
					}
	
						
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
						
						
						
						
				}elseif ($credito=="cta_individual"){
						
						
	
					$columnas_ind="afiliado_transacc_cta_ind.id_afiliado_transacc_cta_ind,
							  afiliado_transacc_cta_ind.ordtran,
							  afiliado_transacc_cta_ind.histo_transacsys,
							  afiliado_transacc_cta_ind.cedula,
							  afiliado_transacc_cta_ind.fecha_conta,
							  afiliado_transacc_cta_ind.descripcion,
							  afiliado_transacc_cta_ind.mes_anio,
							  afiliado_transacc_cta_ind.valorper,
							  afiliado_transacc_cta_ind.valorpat,
							  afiliado_transacc_cta_ind.saldoper,
							  afiliado_transacc_cta_ind.saldopat,
							  afiliado_transacc_cta_ind.id_afiliado";
					$tablas_ind="public.afiliado_transacc_cta_ind";
					$where_ind="1=1 AND afiliado_transacc_cta_ind.cedula='$cedula_usuarios'";
					$id_ind="afiliado_transacc_cta_ind.ordtran";
					$resultSet=$afiliado_transacc_cta_ind->getCondicionesDesc($columnas_ind, $tablas_ind, $where_ind, $id_ind);
						
						
						
	
					if(!empty($resultSet)){
	
	
						$result_par=$usuarios->getBy("cedula_usuarios='$cedula_usuarios'");
	
						if(!empty($result_par)){
							$_cedula_usuarios=$result_par[0]->cedula_usuarios;
							$_nombre_usuarios=$result_par[0]->nombre_usuarios;
								
						}else{
								
							$_cedula_usuarios="";
							$_nombre_usuarios="";
						}
	
	
						$columnas_ind_mayor = "sum(valorper+valorpat) as total, max(fecha_conta) as fecha";
						$tablas_ind_mayor="afiliado_transacc_cta_ind";
						$where_ind_mayor="cedula='$cedula_usuarios'";
						$resultDatosMayor_Cta_individual=$afiliado_transacc_cta_ind->getCondicionesValorMayor($columnas_ind_mayor, $tablas_ind_mayor, $where_ind_mayor);
							
						if (!empty($resultDatosMayor_Cta_individual)) {  foreach($resultDatosMayor_Cta_individual as $res) {
	
							$fecha=$res->fecha;
							$total= number_format($res->total, 2, '.', ',');
						}}else{
	
							$fecha="";
							$total= 0.00;
	
						}
	
	
						$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
						$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
						$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CUENTA INDIVIDUAL</b></p>';
						$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombre_usuarios.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_usuarios.'</p>';
						$html.='<center style="margin-top:5px;"><h4><b>Total Cuenta Individual Actualizada al</b> '.$fecha.' : $'.$total.'</h4></center>';
						$html.= "<table style='margin-top:5px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
						$html.= "<thead>";
						$html.= "<tr style='background-color: #D5D8DC;'>";
							
						$html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Descripción</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Mes/A&ntilde;o</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Valor Personal</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Valor Patronal</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Saldo Personal</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Saldo Patronal</th>';
							
						$html.='</tr>';
						$html.='</thead>';
						$html.='<tbody>';
	
						$i=0;
						foreach ($resultSet as $res)
						{
							$i++;
							$html.='<tr>';
							$html.='<td style="font-size: 11px;">'.$res->fecha_conta.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->descripcion.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->mes_anio.'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->valorper, 2, '.', ',').'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->valorpat, 2, '.', ',').'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->saldoper, 2, '.', ',').'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->saldopat, 2, '.', ',').'</td>';
							$html.='</tr>';
						}
	
						$html.='</tbody>';
						$html.='</table>';
	
	
							
					}
	
						
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
						
						
						
				}elseif ($credito=="cta_desembolsar"){
						
						
	
					$columnas_desemb="afiliado_transacc_cta_desemb.id_afiliado_transacc_cta_desemb,
						  	afiliado_transacc_cta_desemb.ordtran,
						  	afiliado_transacc_cta_desemb.histo_transacsys,
						  	afiliado_transacc_cta_desemb.cedula,
						  	afiliado_transacc_cta_desemb.fecha_conta,
						  	afiliado_transacc_cta_desemb.descripcion,
						  	afiliado_transacc_cta_desemb.mes_anio,
						  	afiliado_transacc_cta_desemb.valorper,
						 	afiliado_transacc_cta_desemb.valorpat,
						  	afiliado_transacc_cta_desemb.saldoper,
						 	afiliado_transacc_cta_desemb.saldopat,
						    afiliado_transacc_cta_desemb.id_afiliado";
					$tablas_desemb="public.afiliado_transacc_cta_desemb";
					$where_desemb="1=1 AND afiliado_transacc_cta_desemb.cedula='$cedula_usuarios'";
					$id_desemb="afiliado_transacc_cta_desemb.ordtran";
					$resultSet=$afiliado_transacc_cta_ind->getCondicionesDesc($columnas_desemb, $tablas_desemb, $where_desemb, $id_desemb);
						
						
						
	
					if(!empty($resultSet)){
	
	
						$result_par=$usuarios->getBy("cedula_usuarios='$cedula_usuarios'");
	
						if(!empty($result_par)){
							$_cedula_usuarios=$result_par[0]->cedula_usuarios;
							$_nombre_usuarios=$result_par[0]->nombre_usuarios;
								
						}else{
								
							$_cedula_usuarios="";
							$_nombre_usuarios="";
						}
	
	
						$columnas_desemb_mayor = "sum(valorper+valorpat) as total, max(fecha_conta) as fecha";
						$tablas_desemb_mayor="afiliado_transacc_cta_desemb";
						$where_desemb_mayor="cedula='$cedula_usuarios'";
						$resultDatosMayor_Cta_desembolsar=$afiliado_transacc_cta_ind->getCondicionesValorMayor($columnas_desemb_mayor, $tablas_desemb_mayor, $where_desemb_mayor);
							
						if (!empty($resultDatosMayor_Cta_desembolsar)) {  foreach($resultDatosMayor_Cta_desembolsar as $res) {
	
							$fecha=$res->fecha;
							$total= number_format($res->total, 2, '.', ',');
						}}else{
	
							$fecha="";
							$total= 0.00;
	
						}
	
	
						$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
						$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
						$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CUENTA DESEMBOLSAR</b></p>';
						$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombre_usuarios.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_usuarios.'</p>';
						$html.='<center style="margin-top:5px;"><h4><b>Total Cuenta Individual Actualizada al</b> '.$fecha.' : $'.$total.'</h4></center>';
						$html.= "<table style='margin-top:5px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
						$html.= "<thead>";
						$html.= "<tr style='background-color: #D5D8DC;'>";
							
						$html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Descripción</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Mes/A&ntilde;o</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Valor Personal</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Valor Patronal</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Saldo Personal</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Saldo Patronal</th>';
							
						$html.='</tr>';
						$html.='</thead>';
						$html.='<tbody>';
	
						$i=0;
						foreach ($resultSet as $res)
						{
							$i++;
							$html.='<tr>';
							$html.='<td style="font-size: 11px;">'.$res->fecha_conta.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->descripcion.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->mes_anio.'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->valorper, 2, '.', ',').'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->valorpat, 2, '.', ',').'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->saldoper, 2, '.', ',').'</td>';
							$html.='<td style="font-size: 11px;">'.number_format($res->saldopat, 2, '.', ',').'</td>';
							$html.='</tr>';
						}
	
						$html.='</tbody>';
						$html.='</table>';
	
	
							
					}
	
						
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
	
	
	
				}elseif ($credito=="refinanciamiento"){
						
						
						
					$columnas_refi_cabec ="*";
					$tablas_refi_cabec="refinanciamiento_solicitud";
					$where_refi_cabec="cedula='$cedula_usuarios'";
					$id_refi_cabec="cedula";
					$resultCredRefi_Cabec=$refinanciamiento_solicitud->getCondicionesDesc($columnas_refi_cabec, $tablas_refi_cabec, $where_refi_cabec, $id_refi_cabec);
						
						
						
	
						
					if(!empty($resultCredRefi_Cabec)){
							
						$_numsol_app=$resultCredRefi_Cabec[0]->numsol;
						$_cuota_app=$resultCredRefi_Cabec[0]->cuota;
						$_interes_app=$resultCredRefi_Cabec[0]->interes;
						$_tipo_app=$resultCredRefi_Cabec[0]->tipo;
						$_plazo_app=$resultCredRefi_Cabec[0]->plazo;
						$_fcred_app=$resultCredRefi_Cabec[0]->fcred;
						$_ffin_app=$resultCredRefi_Cabec[0]->ffin;
						$_cuenta_app=$resultCredRefi_Cabec[0]->cuenta;
						$_banco_app=$resultCredRefi_Cabec[0]->banco;
						$_valor_app= number_format($resultCredRefi_Cabec[0]->valor, 2, '.', ',');
						$_cedula_app=$resultCredRefi_Cabec[0]->cedula;
						$_nombres_app=$resultCredRefi_Cabec[0]->nombres;
							
						if($_numsol_app != ""){
								
								
							$columnas_app_detall ="numsol,
										pago,
										mes,
										ano,
										fecpag,ROUND(capital,2) as capital,
										ROUND(interes,2) as interes,
										ROUND(intmor,2) as intmor,
										ROUND(seguros,2) as seguros,
										ROUND(total,2) as total,
										ROUND(saldo,2) as saldo,
										estado";
								
							$tablas_app_detall="refinanciamiento_detalle";
							$where_app_detall="numsol='$_numsol_app'";
							$id_app_detall="pago";
							$resultSet=$refinanciamiento_detalle->getCondiciones($columnas_app_detall, $tablas_app_detall, $where_app_detall, $id_app_detall);
	
							$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
							$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
							$html.='<p style="text-align: center; font-size: 16px;"><b>DETALLE CRÉDITO DE REFINANCIAMIENTO</b></p>';
								
							$html.= '<p style="margin-top:15px; text-align: justify; font-size: 13px;"><b>NOMBRES:</b> '.$_nombres_app.'  <b style="margin-left: 20%; font-size: 13px;">IDENTIFICACIÓN:</b> '.$_cedula_app.'</p>';
								
							$html.= "<table style='width: 100%;' border=1 cellspacing=0 >";
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">No de Solicitud:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Monto Concedido:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuota:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Interes:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Tipo:</th>';
							$html.='</tr>';
								
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_numsol_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_valor_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuota_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_interes_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_tipo_app.'</td>';
							$html.='</tr>';
								
								
							$html.= '<tr style="background-color: #D5D8DC;">';
							$html.='<th style="text-align: left;  font-size: 13px;">PLazo:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Concedido en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Termina en:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Cuenta No:</th>';
							$html.='<th style="text-align: left;  font-size: 13px;">Banco:</th>';
							$html.='</tr>';
	
							$html.= "<tr>";
							$html.='<td style="font-size: 13px;">'.$_plazo_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_fcred_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_ffin_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_cuenta_app.'</td>';
							$html.='<td style="font-size: 13px;">'.$_banco_app.'</td>';
							$html.='</tr>';
	
							$html.='</table>';
								
								
								
								
							$html.= "<table style='margin-top:20px; width: 100%;' border=1 cellspacing=0 cellpadding=2>";
							$html.= "<thead>";
							$html.= "<tr style='background-color: #D5D8DC;'>";
	
							$html.='<th style="text-align: left;  font-size: 12px;">Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">A&ntilde;o</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Capital</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Interes por Mora</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Seguro Desgr.</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
							$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
							$html.='</tr>';
							$html.='</thead>';
							$html.='<tbody>';
								
							$i=0;
							foreach ($resultSet as $res)
							{
								$i++;
								$html.='<tr>';
								$html.='<td style="font-size: 12px;">'.$res->pago.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->mes.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->ano.'</td>';
								$html.='<td style="font-size: 12px;">'.$res->fecpag.'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->capital, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->interes, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->intmor, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->seguros, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->total, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.number_format($res->saldo, 2, '.', ',').'</td>';
								$html.='<td style="font-size: 12px;">'.$res->estado.'</td>';
								$html.='</tr>';
							}
								
							$html.='</tbody>';
							$html.='</table>';
								
						}
							
					}
						
	
					$this->report("Creditos",array( "resultSet"=>$html));
					die();
						
						
						
	
						
						
						
						
						
				}
	
	
	
			}else{
	
				$this->redirect("Usuarios","sesion_caducada");
	
			}
				
	
		}else{
	
			$this->redirect("Usuarios","sesion_caducada");
	
		}
	
	}
	
	
	
	
	
	///////////////////////////////////////////////////// DESCARGA DE DOCUMENTOS/////////////////////////////
	
	
	
	
	
	public function inicializar(){
		
		session_start();				
		$this->view("Documentos",array(
					"resultSet"=>""
		));
	
	}
	
	
	
	public function home(){
	
		session_start();
		$this->view("Home",array(
				"resultSet"=>""
		));
	
	}
	
	
	
	
}
?>