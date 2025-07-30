<?php

namespace App\Adapters\Pdf;

use App\Adapters\Contracts\PdfAdapter;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;

class DomPdfAdapter implements PdfAdapter
{
    public function generate(string $filename, string $content, string $orientation): void
    {
        $options = new DompdfOptions();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', $orientation); //portrait ou landscape

        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => false]);
    }
}