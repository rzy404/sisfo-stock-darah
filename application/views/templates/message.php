<?php
if ($this->session->has_userdata('swal_message') && $this->session->has_userdata('swal_type')) {
    $swal_message = $this->session->flashdata('swal_message');
    $swal_type = $this->session->flashdata('swal_type');
    echo "<script>Swal.fire('$swal_message', '', '$swal_type');</script>";
}
