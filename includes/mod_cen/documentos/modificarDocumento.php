<script src="includes/mod_cen/js/permisos.js"></script>
<script src="includes/mod_cen/js/s_ajax_documento.js"></script>
<?php
//include_once("includes/mod_cen/documentos/listarDocumentos.php");
include_once("includes/mod_cen/clases/PermisoCategoriaDoc.php");
include_once("includes/mod_cen/clases/PermisoDoc.php");
include_once("includes/mod_cen/clases/CategoriaDoc.php");
include_once("includes/mod_cen/clases/Documento.php");

if(isset($_POST["modif_doc"]) ){
	$fecha=date("Y-m-d H:i:s");
  $nombreArchivo=$_POST["nombreArchivo"];
  $guardar=$_POST["documentoId"];
	//echo "<br><br><br>".$_FILES['input-img']['name'][0]."<br><br><br>";
	//var_dump($_FILES['input-img']);
	if($_FILES['input-img']['name'][0]<>'')
	{
  foreach ($_FILES['input-img'] as $key) {

    $cantidadElmentos=count($_FILES['input-img']['name']);

    for ($i=0; $i < $cantidadElmentos ; $i++) {
      # code...
      $img1 = $_FILES['input-img']['tmp_name'][$i];
      $img1 = $_FILES['input-img']['name'][$i];

      $dir_subida = './documentacion/';
			switch ($_FILES['input-img']['type'][$i]) {
				case 'application/pdf':
					$nombreArchivo=$_POST["tituloDoc"].'.pdf';
					break;
				case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
						$nombreArchivo=$_POST["tituloDoc"].'.xlsx';
						break;
				case 'application/vnd.ms-excel':
								$nombreArchivo=$_POST["tituloDoc"].'.xls';
								break;
			 case 'application/msword':
								$nombreArchivo=$_POST["tituloDoc"].'.doc';
								break;
			 case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
							 $nombreArchivo=$_POST["tituloDoc"].'.docx';
							 break;
			 case 'image/jpeg':
								$nombreArchivo=$_POST["tituloDoc"].'.jpg';
								break;
			 case 'image/png':
							 $nombreArchivo=$_POST["tituloDoc"].'.png';
							 break;
			 case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
							 $nombreArchivo=$_POST["tituloDoc"].'.pptx';
							 break;


				default:
					# code...
					break;
			}
      $fichero_subido = $dir_subida . $nombreArchivo;
//      echo $fichero_subido;


//echo '<pre>';
      if (move_uploaded_file($_FILES['input-img']['tmp_name'][$i], $fichero_subido)) {
        if($_FILES['input-img']['type'][$i]=='image/jpeg'){
          $nuevoArchivo = $dir_subida.$nombreArchivoMediano;
          copy($fichero_subido,$nuevoArchivo);
        }
        //$imagen = new Img(null,$guardar_informe,$nombreArchivo,$tipoArchivo);
        //$agregarImg = $imagen->agregar();
    //    echo "El fichero es válido y se subió con éxito.\n";
      }	 else {
// echo "¡Posible ataque de subida de ficheros!\n";
      }

    }
    break;
  }
  }else{
		$anterior="./documentacion/".$_POST['nombreArchivo'];
		//$_POST['nombreArchivo']
		//echo '<br><br>';
		//echo strpos($_POST['nombreArchivo'], '|');
		$finalArchivo=substr($_POST['nombreArchivo'], strpos($_POST['nombreArchivo'], '.'),strlen ($_POST['nombreArchivo']));

		//echo '<br><br>';
		$nuevo="./documentacion/".$_POST['tituloDoc'].$finalArchivo;
		$nombreArchivo=$_POST['tituloDoc'].$finalArchivo;
		rename($anterior, $nuevo);
		echo 'No fue adjuntado ningun archivo';
	}
  $modif_doc = new Documento($_POST["documentoId"],$_POST["categoria_doc"],$nombreArchivo,$_POST["tituloDoc"],$_POST["descripcion"],$_POST["destacado"],$_GET["fechaSubida"],$fecha,$_SESSION["referenteId"],$_SESSION["tipo"]);
  $guardar = $modif_doc->editar();

  if ($guardar >0){
        // para actualizar los permisos del documento vamos a borrar las entradas que tiene el documentoId en la tabla  permiso_doc e ingresar nuevas entradas
        $permiso_doc_viejo = new PermisoDoc(null,$_POST["documentoId"],null);
        $borrar = $permiso_doc_viejo->eliminarPermisos();
        if ($borrar >0){
          if (is_array($_POST["tipo"])) {
            foreach ($_POST["tipo"] as $value) {
              $permiso_doc = new PermisoDoc(null,$_POST["documentoId"],$value);
              $crearPermiso = $permiso_doc->agregar();
              if($crearPermiso==1){
                echo "Se actualizo permiso ".$value;
              }
            }
          }
        }
        echo "Actualizacion Exitosa";
        // sleep(5);
  }else{
    echo "Error al actualizar";
  }

  $variablephp = "index.php?mod=slat&men=informe&id=20";
  ?>
    <script type="text/javascript">
      var variablejs = "<?php echo $variablephp; ?>" ;
      function redireccion(){window.location=variablejs;}
      setTimeout ("redireccion()",0);
    </script>
<?php

}else{
  if (isset($_GET['documentoId'])) {
    $objDocumento= new Documento($_GET['documentoId']);
    $buscarObjDocumento = $objDocumento->buscar();
    $datoDocumento = mysqli_fetch_object($buscarObjDocumento);

    $categ = new CategoriaDoc(NULL,NULL,NULL);
    $buscarcategoria = $categ->buscar();
    $tipoPermiso = new PermisoCategoriaDoc(NULL,$datoDocumento->categoriaDocId,Null);
    $buscarTipoPermiso = $tipoPermiso->buscar();

    $permiso_doc = new PermisoDoc(NULL,$datoDocumento->documentoId,NULL);
    $listado_permiso = $permiso_doc->buscar();
    $permisos="";

    while($fila2 = mysqli_fetch_object($listado_permiso))
    {
      $permisos.= " ".$fila2->tipoReferente;
    }
    include_once("includes/mod_cen/formularios/f_Modif_documento.php");
  }
}
?>
