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
                    <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Reporte';?></h4>
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
                    <br>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button id="btnCargarPDF" onclick="cargarPDF();" class="btn btn-danger">PDF</button>
                            <button id="btnGenerarRegistro" disabled onclick="generarRegistro();" class="btn btn-success">Generar</button>
                        </div>
                    </div>
                    <br>
                    <div class="row ml-5 mr-5">
                        <iframe style="width:100%; height: 400px;" id="iframe-reporte"></iframe>
                    </div>
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
    <script>
        var nombre_archivo = '';
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

        function cargarPDF(){
            let organizacion = $('#select-organizacion').val();
            let dispositivo = $('#select-dispositivo').val();
            let desde = $('#input-fecha_desde').val();
            let hasta = $('#input-fecha_hasta').val();
            if(dispositivo == ''){
                alert('Debe seleccionar un Dispositivo.');
                return false;
            }
            $('#btnCargarPDF').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url();?>index.php/CapturasController/cargarPDF',
                type: 'get',
                data: {org: organizacion, dev: dispositivo, desde: desde, hasta: hasta},
                dataType: 'text',
                success: function(response){
                    nombre_archivo = response;
                    $('#iframe-reporte').attr('src', '<?php echo base_url();?>assets/reportes/'+response);
                    $('#btnGenerarRegistro').prop('disabled', false);
                    $('#btnCargarPDF').prop('disabled', false);
                }
            }); 
        }

        function generarRegistro(){
            if(confirm('Confirme generar registro de este informe')){
                let url = $('#iframe-reporte').attr('src');
                let dispositivo = $('#select-dispositivo').val();
                let organizacion = $('#select-organizacion').val();

                $.ajax({
                    url: '<?php echo base_url();?>index.php/CapturasController/guardarInforme',
                    type: 'post',
                    data: {organizacion: organizacion, url: url, archivo: nombre_archivo},
                    datatype: 'text',
                    success: function(response){
                        alert(response);
                        actualizar();
                    }
                });
            }
        }
    </script>
</body>
</html>