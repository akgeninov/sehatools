<?php
require('./vendor/fpdf/fpdf/src/Fpdf/Fpdf.php'); // Path ke FPDF

function createInvoicePDF($data) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Order ID: ' . $data['id_order'], 0, 1);
    $pdf->Cell(0, 10, 'Total: ' . $data['total'], 0, 1);
    // Tambahkan detail lain sesuai kebutuhan

    // Simpan file
    $filePath = 'invoices/invoice_' . $data['id_order'] . '.pdf';
    $pdf->Output($filePath, 'F');

    return $filePath;
}
?>
