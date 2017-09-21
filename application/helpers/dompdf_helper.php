 <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

      function generarPdf($html, $filename, $stream=TRUE) {

        require_once("dompdf".DIRECTORY_SEPARATOR."dompdf_config.inc.php");
        if ( isset($html) ) {

	        $html = stripslashes($html);

	        $dompdf = new DOMPDF();

	        $dompdf->load_html(utf8_decode($html));
			        //$dompdf->set_paper("a4", "landscape");
	        $dompdf->set_paper("a4", "portrait");
	        $dompdf->render();
	        
    			$font = Font_Metrics::get_font("arial", "bold");
    			//$dompdf->get_canvas()->page_text(740, 550, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 6, array(0,0,0));

	        $dompdf->stream($filename . ".pdf");


	 	   }
	  }

	function guardarPdf($html, $filename, $ruta = ".\\assets\\") {
		require_once("dompdf".DIRECTORY_SEPARATOR."dompdf_config.inc.php");
		if ( isset($html) ) {
			$html = stripslashes($html);
			$dompdf = new DOMPDF();
			$dompdf->load_html($html);
			$dompdf->render();
			$pdfoutput = $dompdf->output();
			$fp = fopen($ruta.$filename.".pdf", "a");
			fwrite($fp, $pdfoutput);
			fclose($fp);
	   }
	}
