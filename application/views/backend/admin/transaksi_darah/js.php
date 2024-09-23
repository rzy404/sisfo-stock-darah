<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table1').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('admin/get-transaksi-darah'); ?>",
                "type": "POST",
            },
        });

        $(document).on('click', '#btnDelete', function() {
            var id = $(this).data('id');
            console.log(id);
            deleteData(id, '<?= base_url('admin/delete-transaksi-darah') ?>');
        });
    });
</script>