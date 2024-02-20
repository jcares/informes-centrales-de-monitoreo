<?php
    $this->db->select('*');
    $this->db->from('observaciones_sugeridas');
    $obs = $this->db->get()->result_array();
    $options_string = '';
    if(!empty($obs)){
        foreach($obs as $o){
            $options_string .= '<option value="'.$o['id'].'">'.$o['id'].' | '.$o['observacion'].'</option>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('layout/head');?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">  
        <!-- sidebar-->
        <?php $this->load->view('layout/sidebar');?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php $this->load->view('layout/nav');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid m-2" style="background-color: #fff; min-height: 500px;">
                    <!-- main content -->
                    <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Imágenes x Clasificar';?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Organización</label>
                            <select class="form-control" id="select-organizacion">
                                <option value="">Seleccione</option>
                                <?php 
                                    if(!empty($organizaciones)){
                                        foreach($organizaciones as $o){
                                            echo '<option value="'.$o['id'].'">'.$o['nombre'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Dispositivo</label>
                            <select class="form-control" id="select-dispositivo">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="">Fecha Desde</label>
                            <input class="form-control" type="date" name="fecha_desde" id="input-fecha_desde" value="<?php echo date('Y-m-').'01';?>" />
                        </div>
                        <div class="col-md-6">
                            <label for="">Fecha Hasta</label>
                            <input class="form-control" type="date" name="fecha_desde" id="input-fecha_hasta" value="<?php echo date('Y-m-d');?>"/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-primary" onclick="mostrar();">Buscar</button>
                        </div>
                    </div>
                    <br>
                    <div class="row capturasRegistradas">
                        
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" onclick="consolidar();">Enviar</button>
                            <button class= "btn btn-xs btn-danger" onclick="actualizar();">Cancelar</button>
                        </div>
                    </div>
                    <br>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php $this->load->view('layout/footer');?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <?php $this->load->view('layout/scripts');?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select-organizacion').change(function(){
                let options_dispositivos = '<option value="">Seleccione</select>';
                $.ajax({
                    url: '<?php echo base_url();?>index.php/CapturasController/obtenerDispositivosOrganizacion',
                    data: {organizacion: $('#select-organizacion').val()},
                    type: 'post',
                    dataType: 'json',
                    success: function(response){
                        if(response.length > 0){
                            for(let i=0; i<response.length; i++){
                                options_dispositivos += '<option value="'+response[i]['id']+'">'+response[i]['nombre']+'</option>';
                            }
                        }
                        $('#select-dispositivo').html(options_dispositivos);
                    }
                });
            });
        });

        function actualizar(){
            location.reload();
        }

        function mostrar(){
            let organizacion = $('#select-organizacion').val();
            let dispositivo = $('#select-dispositivo').val();
            let desde = $('#input-fecha_desde').val();
            let hasta = $('#input-fecha_hasta').val();
            if(dispositivo == ''){
                alert('Debe seleccionar un Dispositivo.');
                return false;
            }
            let capturas = '';
            $.ajax({
                url: '<?php echo base_url();?>index.php/CapturasController/obtenerCapturasConsolidar',
                type: 'post',
                data: {dispositivo: dispositivo, desde: desde, hasta: hasta},
                dataType: 'json',
                success: function(response){
                    if(response.length > 0){
                        for(let i=0; i<response.length; i++){

                            capturas += '<div class="col-md-6 mb-2">';
                            capturas += '<div class="row">';
                            capturas += '<div class="col-md-4">';
                            capturas += '<ul>';
                            capturas += '<li><b>Fecha:</b> '+response[i]['fecha']+'</li>';
                            capturas += '<li><b>Hora:</b> '+response[i]['hora']+'</li>';
                            capturas += '<li><b>Usuario:</b> '+((response[i]['usuario'] != null && response[i]['usuario'] != '') ? response[i]['usuario'] : 'Sistema')+'</li>';
                            capturas += '<li><b>Canal:</b> '+response[i]['numero_canal']+' '+response[i]['nombre_canal']+'</li>';
                            capturas += '</ul>';
                            capturas += '</div>';
                            capturas += '<div class="col-md-8">';
                            capturas += '<img  style="cursor: pointer;" onclick="abrirGrande(this.src);" width="100%" height="300" src="<?php echo base_url();?>assets/imagenes_capturadas/'+response[i]['ruta_imagen']+'" id="img-captura" class="img-responsive pull-right"/>';
                            capturas += '</div>';
                            capturas += '</div>';
                            
                            capturas += '<div class="row">';
                            
                            capturas += '<div class="col-md-8">';
                            capturas += '<label for="">Sugerencias</label>';
                            capturas += '<select class="form-control sugerencias" id="select-sugerencia-'+response[i]['id']+'">';
                            capturas += '<?php echo $options_string;?>';
                            capturas += '</select>';
                            capturas += '</div>';

                            capturas += '<div class="col-md-2 mt-3">';
                            capturas += '<button onclick="utilizarSugerencia('+response[i]['id']+');" class="btn btn-primary w-100">Utilizar</button>';
                            capturas += '</div>';
                            capturas += '<div class="col-md-2 mt-3">';
                            capturas += '<button onclick="borrar('+response[i]['id']+');" class="btn btn-danger w-100">Borrar</button>';
                            capturas += '</div>';

                            capturas += '';

                            capturas += '</div>';
                            
                            capturas += '<div class="row">';
                            capturas += '<div class="col-md-12">';
                            capturas += '<textarea id="'+response[i]['id']+'" class="form-control mt-1 observaciones" rows="4" placeholder="Ingrese observación...">'+((response[i]['observacion'] != "null") ? response[i]['observacion'] : "")+'</textarea>';
                            capturas += '</div>';
                            capturas += '</div>';

                            capturas += '</div>';

                        }
                    }
                    $('.capturasRegistradas').html(capturas);
                    $(".sugerencias").select2({placeholder: "Seleccione..."});
                }
            });
        }

        function consolidar(){
            if(confirm('Confirmar la consolidación')){
                let observaciones = $('textarea.observaciones');
                let data_consolidar = [];
                if(observaciones.length > 0){
                    for(let i=0; i<observaciones.length; i++){
                        let identificador = $(observaciones[i]).attr('id');
                        let observacion = $(observaciones[i]).val();
                        data_consolidar.push({'id': identificador, 'observacion': observacion});
                    }

                    $.ajax({
                        url: '<?php echo base_url();?>index.php/CapturasController/guardarConsolidacion',
                        type: 'post',
                        data: {'data_consolidar': data_consolidar},
                        dataType: 'text',
                        success: function(response){
                            if(response == '1'){
                                alert('Consolidación generada de manera correcta.');
                                location.reload();
                            }  
                        }
                    });
                }
                else{
                    alert('No existen capturas a consolidar.');
                }
            }
        }

        function utilizarSugerencia(x){
            let sugerencia = $("#select-sugerencia-"+x+" option:selected").text();
            $('#'+x).append(' '+sugerencia);
        }

        function borrar(x){
            $('#'+x).val('');
        }

        function abrirGrande(src){
            $('#img-expandir').attr('src', src);
            $('#modal-captura').modal('show');
        }
    </script>
</body>
</html>