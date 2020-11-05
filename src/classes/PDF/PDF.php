<?php


namespace classes\SHARED\PDF;

use TCPDF;

class PDF
{
    public function __construct(
        $seiten = [
            ["Zugangsdaten KRS-IT", "<p>Du erhältst hier deine Zugangsdaten</p>"]
        ])
    {
        // Erstellung des PDF Dokuments
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Dokumenteninformationen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('AUTOR');
        $pdf->SetTitle('TITLE');
        $pdf->SetSubject('DOKUMENTEN BETREFF');


        // Header und Footer Informationen
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Auswahl des Font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Auswahl der MArgins
        //Left, Top, Right
        $pdf->SetMargins(25, 20, 25);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        // Automatisches Autobreak der Seiten
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Image Scale
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Schriftart
        $pdf->SetFont('Helvetica', '', 10);

        $html_tmp = '
        <table cellpadding="5" cellspacing="0" style="width: 100%; ">
                <tr>
                        <td><img width="180px" src="img/..."></td>
                        <td style="text-align: right">
                            ...
                        </td>
                </tr>
        </table>';


        foreach ($seiten as $tmp) {

            $seite = $html_tmp . "<h2>" . $tmp[0] . "</h2>" . $tmp[1];

            // Neue Seite
            $pdf->AddPage();

            // Fügt den HTML Code in das PDF Dokument ein
            $pdf->writeHTML($seite, false, false, true, false, '');
        }


        //Ausgabe der PDF
        //Variante 1: PDF direkt an den Benutzer senden:
        $pdf->Output('dokument.pdf', 'I');

        //Variante 2: PDF im Verzeichnis abspeichern:
        //$pdf->Output(dirname(__FILE__).'/'.$pdfName, 'F');
        //echo 'PDF herunterladen: <a href="'.$pdfName.'">'.$pdfName.'</a>';
    }

}