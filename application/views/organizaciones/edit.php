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
                    <form id="formEditCliente">
                        <!-- main content -->
                        <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Editar Cliente #'.$id;?></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Rut</label>
                                <input class="form-control" id="input-rut" name="rut" value="<?php echo $organizacion[0]['rut'];?>" placeholder="Eje: 11222333-4" required/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Tipo</label>
                                <select required name="canal" id="select-tipo_organizacion" name="tipo_organizacion" class="form-control">
                                    <option value="">Seleccione</option>
                                    <?php 
                                    if(!empty($tipo_organizacion)){
                                        foreach($tipo_organizacion as $to){
                                            if($organizacion[0]['tipo_organizacion_id'] == $to['id'])
                                                echo '<option selected value="'.$to['id'].'">'.$to['tipo'].'</option>';
                                            else
                                                echo '<option value="'.$to['id'].'">'.$to['tipo'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Nombre</label>
                                <input class="form-control" id="input-nombre" name="nombre" value="<?php echo $organizacion[0]['nombre'];?>" required/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Dirección</label>
                                <input class="form-control" id="input-direccion" name="direccion" value="<?php echo $organizacion[0]['direccion'];?>"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Teléfono</label>
                                <input class="form-control" id="input-telefono" name="telefono" value="<?php echo $organizacion[0]['telefono'];?>"/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Email</label>
                                <input type="email" class="form-control" id="input-email" name="email" value="<?php echo $organizacion[0]['email'];?>"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Nombre Contacto</label>
                                <input class="form-control" id="input-contacto" name="contacto" value="<?php echo $organizacion[0]['contacto'];?>"/>
                            </div>
                            <div class="col-md-6">
                                <label for="">Cantidad Dispositivos</label>
                                <input type="number" class="form-control" id="input-cantidad_dispositivos" name="cantidad_dispositivos" value="<?php echo $organizacion[0]['cantidad_dispositivos'];?>"/>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="">Ingrese correos contactos adicionales</label><button type="button" class="btn btn-primary ml-1 btn-sm" onclick="nuevaFilaContacto();">+</button>
                                <table class="table table-bordered table-table-striped">
                                    <thead>
                                        <tr>
                                            <th width="70%">Contacto</th>
                                            <th width="10%">Activo</th>
                                            <th width="20%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-contactos">
                                        <?php
                                        
                                        if(!empty($contactos)){
                                            foreach($contactos as $c){
                                                echo '<tr>';
                                                echo '<td><input required type="email" class="form-control emailsContacto" value="'.$c['contacto'].'" placeholder="Ingrese email"></td>';
                                                echo '<td class="text-center"><input type="checkbox" class="checksContacto" '.(($c['estado'] == 1) ? 'checked' : '' ).' style="cursor:pointer;"></td>';
                                                echo '<td><button type="button" class="btn btn-danger btn-sm" onclick="removerEmailContacto(this);">eliminar</button></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
            $('#formEditCliente').submit(function(event) {
                // Evitar la acción predeterminada de envío del formulario
                event.preventDefault();
                
                let rut = $('#input-rut').val();
                let tipo_organizacion = $('#select-tipo_organizacion').val();
                let nombre = $('#input-nombre').val();
                let direccion = $('#input-direccion').val();
                let telefono = $('#input-telefono').val();
                let email = $('#input-email').val();
                let contacto = $('#input-contacto').val();
                let cantidad_dispositivos = $('#input-cantidad_dispositivos').val();

                let emails_contactos = [];
                let checks_contactos = [];
                let emails = $('.emailsContacto');
                let checks = $('.checksContacto');
                for(let i=0; i<emails.length; i++){
                    if($(emails[i]).val() != ''){
                        emails_contactos.push($(emails[i]).val());
                        checks_contactos.push(  $(checks[i]).is(':checked') == true  ? 1 : 0  );
                    }
                }
                
                if(confirm('Confirme esta operación')){
                    $.ajax({
                        url: '<?php echo base_url();?>index.php/OrganizacionesController/editOrganizacion',
                        type: 'post',
                        data: {
                                id: '<?php echo $id; ?>',
                                rut: rut, tipo_organizacion: tipo_organizacion,
                                nombre: nombre, direccion: direccion,
                                telefono: telefono, email: email,
                                contacto: contacto, cantidad_dispositivos: cantidad_dispositivos,
                                emails_contactos: emails_contactos,
                                checks_contactos: checks_contactos
                        },
                        dataType: 'text',
                        success: function(response){
                            alert(response);
                            window.location.href = '<?php echo base_url();?>index.php/OrganizacionesController/index';
                        }
                    });
                }
               
            });
        });

        function actualizar(){
            location.reload();
        }

        function nuevaFilaContacto(){
            let tr = '';
            tr += '<tr>';
            tr += '<td><input required type="email" class="form-control emailsContacto" value="" placeholder="Ingrese email"></td>';
            tr += '<td class="text-center"><input type="checkbox" class="checksContacto" style="cursor: pointer;"></td>';
            tr += '<td><button type="button" class="btn btn-danger btn-sm" onclick="removerEmailContacto(this);">eliminar</button></td>';
            tr += '</tr>';

            $('#tbody-contactos').append(tr);
        }

        function removerEmailContacto(btn){
            if(confirm('Confirme esta operación')){
                let fila = $(btn).closest('tr');
                fila.remove();
            } 
        }
    </script>
</body>
</html>