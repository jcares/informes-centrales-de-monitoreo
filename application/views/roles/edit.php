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
                    <h4><?php if(!empty($titulo)) echo $titulo;?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <input required class="form-control" name="input-rol" id="input-rol" placeholder="Ingrese Rol" value="<?php if(!empty($data[0]['nombre'])) echo $data[0]['nombre'];?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success" onclick="updateRol();">Enviar</button>
                            <a class= "btn btn-xs btn-danger" href="<?php echo base_url();?>index.php/RolesController/index">Cancelar</a>
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
        function updateRol(){
            let rol_id = '<?php echo $data[0]['id'];?>';
            let rol_nombre = $('#input-rol').val();

            if(rol_nombre.trim() != ''){
                $.ajax({
                    url: '<?php echo base_url();?>index.php/RolesController/editRol',
                    data: {id: rol_id, nombre: rol_nombre},
                    type: 'post',
                    dataType: 'text',
                    success: function(response){
                        if(response == '1')
                            window.location.href = '<?php echo base_url();?>index.php/RolesController/index';
                        else
                            alert('Hubo un problema con la actualización del Rol # '+ id);
                    }   
                });
            }
            else{
                alert('Rol debe tener un valor');
            }
        }
    </script>
</body>

</html>