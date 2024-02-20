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
                    <form id="formAddDispositivo">
                        <!-- main content -->
                        <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Agregar Dispositivo';?></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Organización</label>
                                <select required class="form-control" id="select-organizacion" name="organizacion">
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
                                <label for="">Nombre</label>
                                <input class="form-control" id="input-nombre" name="nombre" value="" required/>
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Tipo</label>
                                <select required class="form-control" id="select-tipo_dispositivo" name="tipo_dispositivo">
                                    <option value="">Seleccione</option>
                                    <?php
                                        if(!empty($tipo_dispositivo)){
                                            foreach($tipo_dispositivo as $td){
                                                echo '<option value="'.$td['id'].'">'.$td['nombre'].'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Marcas</label>
                                <select required class="form-control" id="select-marca" name="marca">
                                    <option value="">Seleccione</option>
                                    <?php
                                        if(!empty($marcas)){
                                            foreach($marcas as $m){
                                                echo '<option value="'.$m['id'].'">'.$m['nombre'].'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Cantidad Canales</label>
                                <input class="form-control" id="input-cantidad_canales" name="cantidad_canales" value=""/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Ubicación</label>
                                <input class="form-control" id="input-ubicacion" name="ubicacion" value=""/>
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Codificar</label>
                                <input class="form-control" id="input-codificar" name="codificar" value=""/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Estado</label>
                                <select class="form-control" id="select-estado" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">IP</label>
                                <input required class="form-control" id="input-ip" name="ip" value=""/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Puerto</label>
                                <input required type="number" min="0" class="form-control" id="input-puerto" name="puerto" value=""/>
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Usuario</label>
                                <input required class="form-control" id="input-usuario" name="usuario" value=""/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Clave</label>
                                <input required type="password" class="form-control" id="input-password" name="password" value=""/>
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <!--
                            <div class="col-md-6">
                                <label for="">Cantidad Capturas</label>
                                <input type="number" class="form-control" id="input-cantidad_capturas" name="cantidad_capturas" value=""/>
                            </div>
                            -->
                            <div class="col-md-12">
                                <label for="">Datos Extras</label>
                                <textarea class="form-control" rows="4" id="textarea-datos_extra" name="datos_extra"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button id="btnIngresarCanales" type="button" class="btn btn-primary" onclick="ingresarCanales();">Ingrese canales asociados</button>
                                <button disabled id="btnCheckCanales" type="button" class="btn btn-warning" onclick="checkearCanales();"><i class="fas fa-cloud-download-alt"></i>Traer datos</button>
                            </div>
                        </div>
                        <div class="row mt-2">
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
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">Enviar</button>
                                <button class= "btn btn-xs btn-danger" onclick="actualizar();">Cancelar</button>
                            </div>
                        </div>
                        <br>
                    </form>
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
            $('#formAddDispositivo').submit(function(event) {
                // Evitar la acción predeterminada de envío del formulario
                event.preventDefault();
                
                let organizacion = $('#select-organizacion').val();
                let nombre = $('#input-nombre').val();
                let tipo = $('#select-tipo_dispositivo').val();
                let marca = $('#select-marca').val();
                let cantidad_canales = $('#input-cantidad_canales').val();
                let ubicacion = $('#input-ubicacion').val();
                let codificar = $('#input-codificar').val();
                let estado = $('#select-estado').val();
                let ip = $('#input-ip').val();
                let puerto = $('#input-puerto').val();
                let usuario = $('#input-usuario').val();
                let password = $('#input-password').val();
                let datos_extras = $('#textarea-datos_extra').val();

                let canales = $('input[name="canales"]');
                if(canales.length <= 0){
                    alert('Deben existir canales asociados al Dispositivo.');
                    return false;
                }

                let canales_arr = [];
                for(let i=0; i<canales.length;i++){
                    let temp = {'canal': $(canales[i]).attr('canal'), 'nombre': $(canales[i]).val()};
                    canales_arr.push(temp);
                }
                
                
                $.ajax({
                    url: '<?php echo base_url();?>index.php/DispositivosController/addDispositivo',
                    type: 'post',
                    data: {
                            organizacion: organizacion, nombre: nombre,
                            tipo: tipo, marca: marca,
                            cantidad_canales: cantidad_canales, ubicacion: ubicacion,
                            codificar: codificar, estado: estado,
                            ip: ip, puerto: puerto,
                            usuario: usuario, password: password,
                            datos_extras: datos_extras,
                            canales: canales_arr
                    },
                    dataType: 'text',
                    success: function(response){
                        alert(response);
                        actualizar();
                    }
                });
                
            });
        });

        function actualizar(){
            location.reload();
        }

        function checkearCanales(){
            let ip = $('#input-ip').val();
            let puerto = $('#input-puerto').val();
            let usuario = $('#input-usuario').val();
            let password = $('#input-password').val();
            let marca = $('#select-marca').val();
            let cantidad_canales = $('#input-cantidad_canales').val();

            if(ip == ''){
                alert('Debe ingresar la ip.');
                return false;
            }

            if(puerto == ''){
                alert('Debe ingresar el puerto.');
                return false;
            }

            if(usuario == ''){
                alert('Debe ingresar el usuario.');
                return false;
            }

            if(password == ''){
                alert('Debe ingresar la clave.');
                return false;
            }

            if(marca == ''){
                alert('Debe ingresar la marca.');
                return false;
            }

            if(cantidad_canales == ''){
                alert('Debe ingresar la cantidad de canales.');
                return false;
            }

            $.ajax({
                url: '<?php echo base_url();?>index.php/CanalesController/traerNombreCanales2',
                type: 'post',
                data: {ip: ip, puerto: puerto, usuario: usuario, password: password, marca: marca},
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
                                    tr += '<td><input canal="'+(j+1)+'" required class="form-control" name="canales" id="input-canal-'+(j+1)+'" value="'+response['data'][j]+'"></td>';
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

        function ingresarCanales(){
            let cantidad_canales = $('#input-cantidad_canales').val();
            let tbody = '';

            if(cantidad_canales == ''){
                alert('Debe ingresar la cantidad de canales.');
                return false;
            }

            for(let i=1; i <= cantidad_canales; i++){
                tbody += '<tr>';
                tbody += '<td>'+i+'</td>';
                tbody += '<td><input canal="'+i+'" required class="form-control" name="canales" id="input-canal-'+i+'" value="Canal '+i+'" /></td>';
                tbody += '</tr>';
            }

            $('#btnCheckCanales').prop('disabled', false);
            $('#tbody-canales').html(tbody);
        }
    </script>
</body>
</html>