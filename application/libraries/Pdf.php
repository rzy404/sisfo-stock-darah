<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    protected $dompdf;

    public function __construct()
    {
        // Set options for DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial'); // Ganti dengan font yang diinginkan
        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size, $orientation)
    {
        // Set ukuran kertas dan orientasi
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename, $options = [])
    {
        // Mengatur header dan mengirim PDF ke browser
        if (isset($options['Attachment']) && $options['Attachment']) {
            $this->dompdf->stream($filename, ['Attachment' => true]);
        } else {
            $this->dompdf->stream($filename);
        }
    }
}
