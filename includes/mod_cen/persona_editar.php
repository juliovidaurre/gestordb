<?php
		include_once('clases/persona.php');
		include_once('clases/localidades.php');
		$personaId=$_GET['personaId'];
		$persona= new Persona($personaId);
		$persona = $persona->getContacto();
		
		$nuevalocalidad = new Localidad($persona->getLocalidadId());
		$localidad = $nuevalocalidad->getLocalidad();
		
		$location=new Localidad();
		$resultado=$location->buscar();	
		?>
		<div class="container">
		<form class="form-horizontal" action="index.php?men=personas&id=4" method="POST" >
			<input type="hidden" name="personaId" value="<?php echo $personaId ?>"/>		
			<div class="form-group">	
				<label class="col-md-3 col-md-offset-2"><h3>Editar Perfil</h3></label>
			</div>
			
			
		
		<?php include_once('formularios/personaf.php'); ?>
			<div class="form-group">
				<div class="col-md-2 col-md-offset-2">	
					<input type='submit' class="btn btn-primary" value='Aplicar Cambios'>
				</div>
			</div>
		</div>
