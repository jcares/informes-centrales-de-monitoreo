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
                    <h4><?php if(!empty($titulo)) echo $titulo;else echo 'Permisos';?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-control" id="select-roles">
                                <option value="">Seleccione</option>
                                <?php
                                if(!empty($roles)){
                                    foreach($roles as $r){
                                        echo '<option value="'.$r['id'].'" >'.$r['nombre'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="table-opciones" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Opción</th>
                                        <th>Check</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($opciones)){
                                        foreach($opciones as $o){
                                            echo '<tr>';
                                            echo '<td>'.$o['id'].'</td>';
                                            echo '<td>'.$o['nombre'].'</td>';
                                            echo '<td><input id="'.$o['id'].'" class="chk-opciones" type="checkbox"/></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <button onclick="guardarPermisos();" class="btn btn-success">Guardar</button>
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
            cargarDataTable();
            $('#select-roles').change(function(){
                $('.chk-opciones').prop('checked', false);
                $.ajax({
                    url: '<?php echo base_url();?>index.php/RolesController/obtenerOpcionesRol',
                    data: {rol: $('#select-roles').val()},
                    type: 'post',
                    dataType: 'json',
                    success: function(response){
                        if(response.length > 0){
                            for(let i=0; i<response.length; i++){
                                $('#'+response[i]['opciones_id']).prop('checked', true);
                            }
                        }
                    }
                });
            });
        });

        function cargarDataTable(){
            let table = new DataTable('#table-capturas');
        }

        function guardarPermisos(){
            if(confirm('Confirme esta operación')){
                let rol = $('#select-roles').val();
                if(rol == ''){
                    alert('Seleccione un rol por favor.');
                    return false;
                }
                let opciones = $('.chk-opciones');
                let opciones_checked = [];
                for(let i=0; i< opciones.length; i++){
                    if($(opciones[i]).is(':checked')){
                        opciones_checked.push($(opciones[i]).attr('id'));
                    }
                }
                if(opciones_checked.length > 0){
                    $.ajax({
                        url: '<?php echo base_url();?>index.php/RolesController/addPermiso',
                        type: 'post',
                        data: {rol: rol, opciones: opciones_checked},
                        dataType: 'text',
                        success: function(response){
                            if(response == 1)
                                alert('Se ha generado de manera correcta los permisos al Rol seleccionado.');
                            else
                                alert('Ha ocurrido un error, por favor comunicarse con el Administrador del sistema.');

                            location.reload();
                        }
                    });
                }
                else{
                    alert('Debe seleccionar al menos una opción.');
                }
            }
            
        }
    </script>
</body>
</html>