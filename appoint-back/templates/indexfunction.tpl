        <script type="text/ecmascript" src="jqgrid/js/prettify/prettify.js"></script>
        <link rel="stylesheet" href="jqgrid/css/prettify.css" />
        <script type="text/ecmascript" src="jqgrid/js/codetabs.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
        <script src="assets/plugins/switchery/js/switchery.min.js"></script>
        <script type="text/javascript" src="assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="assets/pages/jquery.form-advanced.init.js"></script>
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <script src="assets/plugins/bootstrap-table/js/bootstrap-table.min.js"></script>
        <script src="assets/pages/jquery.bs-table.js"></script>
        <script>
            {literal}
                $(document).ready(function(){
                    $("#dependent-btn").click(function(){
                        $("#dependent-modal").modal({backdrop: "static"});
                    });
                # });
                    function myFunction() {
                        location.reload();
                    }
                    function centerModal() {
                        $(this).css('display', 'block');
                        var $dialog = $(this).find(".modal-dialog");
                        var offset = ($(window).height() - $dialog.height()) / 2; 
                        $dialog.css("margin-top", offset);
                    }
                    $('.modal').on('show.bs.modal', centerModal);
                    $(window).on("resize", function () {
                        $('.modal:visible').each(centerModal);
                    }
                });
            {/literal}
        </script>
