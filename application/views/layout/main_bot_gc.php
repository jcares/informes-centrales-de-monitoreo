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
    <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/sb-admin-2.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/chart.js/Chart.min.js"></script>
    <script>
        $(document).ready(function() {
            
        });
        function logout(){
            window.location.href = '<?php echo base_url();?>'+'index.php/UsuariosController/logout';
        }
    </script>
    </body>

</html>