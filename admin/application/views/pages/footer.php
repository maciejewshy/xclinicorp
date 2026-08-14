<!-- não apagar a partir deste ponto -->
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Automatização de disparos | GENILSON MACIEJEWSHY ROCHA - <?PHP echo '@' . date('Y'); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<div id="usuarioModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="MyModal">




        </div>

    </div>
</div>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body" style=" padding:100px 85px 100px 75px; font-size:30px; "><strong>Deseja realmente sair?</strong></div>
            <div class="modal-footer">
                <button class="btn btn-danger bt-lg" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary" href="<?php echo base_url() . 'Clientes/logout' ?>">Sair</a>
            </div>
        </div>
    </div>
</div>

   <!-- Modal -->
   <div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        
                            <div class="modal-body" style="margin:auto; font-size:22px; color: red; padding:150px 0">
                                Este usuário já existe.<br> Escola outro nome!
                            </div>
                            
                        </div>
                    </div>
                </div>


<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->

<?php
if (isset($script)) {
    foreach ($script as $key => $value) {
        echo $value;
        echo "   \n"; # code...
    }
}
?>

<script>

    $('#addUser').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "index/formUser",
            data: "data",
            dataType: "html",
            success: function(response) {
                $(".modal-dialog").removeClass('modal-sm');
                $("#MyModal").html(response);
                $('#usuarioModal').modal('show');
            }
        });
    });

    
    $('#ResPws').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "index/ressetPws",
            dataType: "html",
            success: function(response) {
                $("#MyModal").html(response);
                $(".modal-dialog").addClass('modal-sm');
                $('#usuarioModal').modal('show');
            }
        });
    });




    
    // $('#exampleModal').on('show.bs.modal', function(event) {
    //     var button = $(event.relatedTarget)
    //     var recipient = button.data('whatever');
    //     var modal = $(this);
    //     modal.find('.modal-title').text('New message to ' + recipient);
    //     modal.find('#id_cliente').val(recipient);
    // });

    $('#addCanais').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever');
        var modal = $(this);
        modal.find('.modal-title').text('New message to ' + recipient);
        modal.find('#id_cliente').val(recipient);
    })
</script>
</body>

</html>