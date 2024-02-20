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
                    <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Canales';?></h4>
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
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-warning" onclick="traerData();"><i class="fas fa-cloud-download-alt"></i>Traer datos</button>
                            <button class="btn btn-danger ml-1" onclick="eliminarCanales();"><i class="fas fa-trash"></i>Eliminar</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="30%">Número Canal</th>
                                    <th width="70%">Nombre Canal</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-canales">

                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button onclick="guardarNombres();" class="btn btn-success">Enviar</button>
                            <button class= "btn btn-xs btn-danger" onclick="actualizar();">Cancelar</button>
                        </div>
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
            
            $('#select-dispositivo').change(function(){
                $.ajax({
                    url: '<?php echo base_url();?>index.php/CanalesController/obtenerCantidadCanalesDispotivo',
                    type: 'post',
                    data: {dispositivo: $(this).val()},
                    dataType: 'text',
                    success: function(response){
                        $('#tbody-canales').html(response);

                        
                    }
                });
            });

        });

        function actualizar(){
            location.reload();
        }

        function guardarNombres(){

            let device_id = $('#select-dispositivo').val();
            let canales = $('input[name="canales"]');

            if(device_id == ''){
                alert('Debe seleccionar un Dispositivo.');
                return false;
            }

            let datos = [];
            
            for(let i=0; i < canales.length; i++){
                let id = $(canales[i]).attr('id');
                let canal = id.replace('input-canal-', '');
                let nombre = $(canales[i]).val();

                datos.push({canal: canal, nombre: nombre});
            }

            $.ajax({
                url: '<?php echo base_url();?>index.php/CanalesController/addCanales',
                type: 'post',
                data: {dispositivo: device_id, canales: datos},
                dataType: 'text',
                success: function(response){
                    alert('Se han actualizado los nombres de los canales');
                    actualizar();
                }
            });
        }

        function traerData(){
            let device_id = $('#select-dispositivo').val();

            if(device_id == ''){
                alert('Debe seleccionar un Dispositivo.');
                return false;
            }

            $.ajax({
                url: '<?php echo base_url();?>index.php/CanalesController/traerNombreCanales',
                type: 'post',
                data: {dispositivo: device_id},
                dataType: 'json',
                success: function(response){
                    if(response['codigo'] == 1){
                        if(confirm('Existe nombre para los canales. ¿Desea usarlos?')){
                            for(let j=0; j<response['data'].length; j++){
                                if($('#input-canal-'+(j+1)).length > 0)
                                    $('#input-canal-'+(j+1)).val(response['data'][j]);
                                else{
                                    //crear tr y input
                                    let tr = '<tr>'
                                    tr += '<td>'+(j+1)+'</td>';
                                    tr += '<td><input class="form-control" name="canales" id="input-canal-'+(j+1)+'" value="'+response['data'][j]+'"></td>';
                                    tr += '</tr>';
                                    console.log(tr);
                                    $('#tbody-canales').html($('#tbody-canales').html()+tr);
                                }
                            }
                        }
                    }
                    else{
                        alert(response['mensaje']);
                    }
                }
            });
        }

        function eliminarCanales(){
            let device_id = $('#select-dispositivo').val();

            if(device_id == ''){
                alert('Debe seleccionar un Dispositivo.');
                return false;
            }

            if(confirm('Confirme la eliminación de los canales')){
                $.ajax({
                    url: '<?php echo base_url();?>index.php/CanalesController/eliminarCanales',
                    type: 'post',
                    data: {dispositivo: device_id},
                    success: function(response){
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>