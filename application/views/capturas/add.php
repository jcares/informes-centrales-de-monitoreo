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
                    <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Imágenes x Clasificar (individual)';?></h4>
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
                            <label for="">Canal</label>
                            <select name="canal" id="select-canal" class="form-control">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Consolidado</label>
                            <br>
                            <input id="input-consolidado" type="checkbox" class="form-control" name="consolidado" style="cursor: pointer; width: 35px; height: 35px;">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="">Imagen</label>
                            <button id="btnCapturar" onclick="capturar();" class="btn btn-primary btn-sm">Capturar</button>
                            <div class="d-flex justify-content-center">
                                <img style="cursor: pointer;" onclick="abrirGrande(this.src);" width="300" height="300" src="https://aquiporti.ec/dreamlab/wp-content/uploads/2020/02/default-300x300.jpg" id="img-captura" class="img-responsive pull-center"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-8">
                            <label for="">Sugerencias</label>
                            <select class="form-control" id="select-sugerencia">
                                    <?php
                                        $this->db->select('*');
                                        $this->db->from('observaciones_sugeridas');
                                        $obs = $this->db->get()->result_array();
                                        if(!empty($obs)){
                                            foreach($obs as $o){
                                                echo '<option value="'.$o['id'].'">'.$o['id'].' | '.$o['observacion'].'</option>';
                                            }
                                        }
                                    ?>
                            </select>
                        </div>
                        <div class="col-md-2 mt-3">
                            <button onclick="utilizarSugerencia();" class="btn btn-primary w-100">Utilizar</button>
                        </div>
                        <div class="col-md-2 mt-3">
                            <button onclick="borrar();" class="btn btn-danger w-100">Borrar</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="">Observación</label>
                            <textarea id="area-observacion" class="form-control" rows="4" placeholder="Ingrese observación..."></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="button" onclick="guardarCaptura();" class="btn btn-success">Enviar</button>
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
        var img_captura = $('#img-captura');
        var nombre_imagen = '';
        $(document).ready(function() {
            $("#select-sugerencia").select2({placeholder: "Seleccione..."});
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
            
            $('#select-dispositivo').change(function(){
                let option = '<option value="">Seleccione</option>';
                $.ajax({
                    url: '<?php echo base_url();?>index.php/CapturasController/obtenerCantidadCanalesDispotivo',
                    type: 'post',
                    data: {dispositivo: $(this).val()},
                    dataType: 'text',
                    success: function(response){
                        /*
                        let canales = parseInt(response);
                        for(let i=1; i<=canales; i++){
                            option += '<option value="'+i+'">'+i+'</option>';
                        }
                        */
                        $('#select-canal').html(response);
                    }
                });
            });

        });

        function utilizarSugerencia(){
            let sugerencia = $("#select-sugerencia option:selected").text();
            $('#area-observacion').append(' '+sugerencia);
        }

        function capturar(){
            let organizacion = $('#select-organizacion').val();
            let dispositivo = $('#select-dispositivo').val();
            let canal = $('#select-canal').val();
            if(canal == ''){
                alert('Debe selecciona un canal');
                return false;
            }
            img_captura.attr('src', '<?php echo base_url();?>assets/img/loading.gif');
            $('#btnCapturar').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url();?>index.php/CapturasController/obtenerCapturaDispositivoCanal',
                type: 'post',
                data:{organizacion: organizacion, dispositivo: dispositivo, canal: canal},
                dataType: 'text',
                success: function(response){
                    if(response == '2'){
                        img_captura.attr('src', '<?php echo base_url();?>assets/img/imagen_no_encontrada.jpg');
                    }
                    else{
                        img_captura.attr('src', '<?php echo base_url();?>assets/imagenes_capturadas/'+response);
                        nombre_imagen = response;
                    }
                    $('#btnCapturar').prop('disabled', false);
                }
            });
        }

        function actualizar(){
            location.reload();
        }

        function guardarCaptura(){
            let organizacion = $('#select-organizacion').val();
            let dispositivo = $('#select-dispositivo').val();
            let canal = $('#select-canal').val();
            let observacion = $('#area-observacion').val();
            let consolidado = ($('#input-consolidado').is(':checked') == true) ? 1 : 0;
            if(canal == ''){
                alert('Debe selecciona un canal');
                return false;
            }
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/CapturasController/addCaptura',
                type: 'post',
                dataType: 'text',
                data: {organizacion: organizacion, dispositivo: dispositivo, canal: canal, observacion: observacion, 
                        consolidado: consolidado, ruta_imagen: nombre_imagen},
                success: function(response){
                    if(response == '1'){
                        if(confirm('Se ha guardado de manera correcta la captura. ¿Desea volver a realizar otra Captura?')){
                            location.reload();
                        }
                        else{
                            window.location.href = '<?php echo base_url();?>index.php/CapturasController';
                        }
                    }
                    else
                        alert('Ha ocurrido un problema con la Captura. Por favor comunicarse con el Administrador del Sistema.');
                }
            });
        }

        function borrar(){
            $('#area-observacion').val('');
        }

        function abrirGrande(src){
            $('#img-expandir').attr('src', src);
            $('#modal-captura').modal('show');
        }
    </script>
</body>
</html>