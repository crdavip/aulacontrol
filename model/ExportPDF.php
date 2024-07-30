<?php
require_once '../plugins/tcpdf/tcpdf.php';

class ExportPDF
{
    private $pdf;
    private $reportFrom;

    public function __construct($title = 'TCPDF Example', $author = 'Author Name', $headerLogo = 'logo.png', $headerLogoWidth = 30, $headerHeight = 30)
    {
        $this->reportFrom = $title;

        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor($author);
        $this->pdf->SetTitle($title);
        $this->pdf->SetSubject('TCPDF Tutorial');
        $this->pdf->SetKeywords('TCPDF, PDF, registros, reporte, Sena');

        $this->pdf->SetHeaderData($headerLogo, $headerLogoWidth, $title, 'CDMC');
        $this->pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $this->pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->pdf->SetMargins(PDF_MARGIN_LEFT, $headerHeight, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin($headerHeight / 2);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    }

    public function logo()
    {
        $logo = "../view/img/logoSena.png";
        $this->pdf->Image($logo, 20, 8, 16);
    }

    public function addPage()
    {
        $this->pdf->AddPage();
    }

    public function setFont($family, $style, $size)
    {
        $this->pdf->SetFont($family, $style, $size);
    }

    public function writeText($text)
    {
        $this->pdf->MultiCell(0, 10, $text, 0, 'L', 0, 1, '', '', true);
    }

    public function addTable($header, $data)
    {
        // Colors, line width and bold font
        $this->pdf->SetFillColor(22, 181, 54);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetLineWidth(0.3);
        $this->pdf->SetFont('helvetica', 'B', 10.5);

        // Get column widths based on report type
        $columnWidths = $this->getColumnWidths($header);

        // Header
        foreach ($header as $col) {
            $this->pdf->MultiCell($columnWidths[$col], 7, $col, 1, 'C', 1, 0, '', '', true, 0, false, true, 7, 'C', true);
        }
        $this->pdf->Ln();

        // Color and font restoration
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('helvetica', '', 9);

        // Data
        $fill = 0;
        foreach ($data as $row) {
            // Calculate the max height of the row
            $maxHeight = 0;
            foreach ($header as $col) {
                $nb = $this->pdf->getNumLines($row[array_search($col, $header)], $columnWidths[$col]);
                $maxHeight = max($maxHeight, $nb * 6);
            }
            
            // Write the row data with the max height
            foreach ($header as $col) {
                $this->pdf->MultiCell($columnWidths[$col], $maxHeight, $row[array_search($col, $header)], 'LR', 'C', $fill, 0, '', '', true, 0, false, true, $maxHeight, 'M', true);
            }
            $this->pdf->Ln();
            $fill = !$fill;
        }

        // Closing line
        foreach ($header as $col) {
            $this->pdf->Cell($columnWidths[$col], 0, '', 'T');
        }
    }

    private function getColumnWidths($header)
    {
        $columnWidths = [];

        switch ($this->reportFrom) {
            case "Reporte Registros de Ambiente":
                $defaultWidths = [
                    'Registro' => 18,
                    'Entrada' => 36,
                    'Salida' => 36,
                    'Llaves' => 15,
                    'Televisor' => 20,
                    'Aire' => 14,
                    'Instructor' => 40,
                ];
                break;
            case "Reporte Registro de Equipos":
                $defaultWidths = [
                    'Equipo' => 25,
                    'Modelo' => 30,
                    'Serie' => 25,
                    'Fecha' => 25,
                    'Estado' => 20,
                    'Responsable' => 40,
                ];
                break;
            case "Reporte Registros de Objetos":
                $defaultWidths = [
                    'R. Nro' => 20,
                    'Entrada' => 26,
                    'Salida' => 26,
                    'Objeto' => 19,
                    'Detalle' => 24,
                    'Destino' => 17,
                    'Usuario' => 30,
                    'Documento' => 25,
                ];
                break;
            case "Reporte Usuarios":
                $defaultWidths = [
                    'Documento' => 32,
                    'Nombre' => 40,
                    'Estado' => 22,
                    'Correo' => 44,
                    'Cargo' => 28,
                ];
                break;
            case "Reporte Equipos":
                $defaultWidths = [
                    'Computador' => 32,
                    'Referencia' => 40,
                    'Marca' => 36,
                    'Estado' => 34,
                    'Ambiente' => 40,
                ];
                break;
            case "Reporte Objetos":
                $defaultWidths = [
                    'Numero' => 28,
                    'Descripcion' => 44,
                    'Color' => 28,
                    'Estado' => 24,
                    'Propietario' => 34,
                    'Documento' => 28,
                ];
                break;
            default:
                $defaultWidths = [];
                break;
        }

        // Set default widths for all columns in the header
        foreach ($header as $col) {
            $columnWidths[$col] = isset($defaultWidths[$col]) ? $defaultWidths[$col] : 20; // default width if not defined
        }

        return $columnWidths;
    }

    public function output($name = 'document.pdf', $dest = 'I')
    {
        $this->pdf->Output($name, $dest);
    }
}

