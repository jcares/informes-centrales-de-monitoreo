<?php
$nombre = !empty($usuario[0]['nombre']) ? $usuario[0]['nombre'] : '';
$email = !empty($usuario[0]['email']) ? $usuario[0]['email'] : '';
$password = !empty($usuario[0]['password']) ? $usuario[0]['password'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('layout/head');?>
    <style>
        fieldset 
        {
            border: 1px solid #ddd !important;
            margin: 0;
            xmin-width: 0;
            padding: 10px;       
            position: relative;
            border-radius:4px;
            background-color:#fff;
            padding-left:10px!important;
        }	
        legend
        {
            font-size:14px;
            font-weight:bold;
            margin-bottom: 0px; 
            width: 100%; 
            border: 1px solid #ddd;
            border-radius: 4px; 
            padding: 5px 5px 5px 10px; 
            background-color: #ffffff;
        }
    </style>
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
                <div class="container-fluid">
                    <!-- main content -->
                    <h1 class="h3 mb-4 text-gray-800">Mi Cuenta</h1>
                    <hr>
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                            <div class="panel-body">
                                <fieldset class="col-12 pl-5 pr-5">    	
                                    <legend>Mis Datos</legend>
                                    <?php echo $this->session->flashdata('message');?>
                                    <div class="panel panel-default">
                                        <form action="<?php echo base_url();?>index.php/UsuariosController/actualizarMisDatos" method="post">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-6">Nombre</div>
                                                    <div class="col-6"><input required type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre;?>"/></div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-6">Email</div>
                                                    <div class="col-6"><input required type="email" class="form-control" name="email" id="email" value="<?php echo $email;?>"/></div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-6">Contraseña</div>
                                                    <div class="col-6"><input required type="password" class="form-control" name="password" id="password" value="<?php echo $password;?>"/></div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-12"><button class="btn btn-primary">Guardar</button></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </fieldset>				
                            <div class="clearfix"></div>
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
            
        });
    </script>
</body>
</html>