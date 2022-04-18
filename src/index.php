<?php
session_start();
function pdfSave(){
    if($_GET){
       // ob_end_clean();
        require_once('fpdf.php');
        $name = $_GET['nip'];
        $dob = $_GET['surname'];
        $job = $_GET['name'];
        $title = 'FAKTURA';

        $pdf = new FPDF();
        $pdf -> AddPage();
        $pdf->SetTitle($title);

        // FONT
        $pdf->SetFont('Arial','B',15);
        // WYSOKOŚĆ TYTUŁU
        $w = $pdf->GetStringWidth($title)+6;
        $pdf->SetX((210-$w)/2);
        // RAMKI, KOLOR I TEKST
        $pdf->SetDrawColor(221,221,221,1);
        $pdf->SetFillColor(10,158,0,1);
        $pdf->SetTextColor(255,255,255,1);
        // ODSTĘPY 
        $pdf->SetLineWidth(1);
        // POŁOŻENIE TYTUŁU
        $pdf->Cell($w, 9, $title, 1, 1, 'C', true);
        // INTERLINIE
        $pdf->Ln(10);

        $pdf->SetTextColor(0,0,0,1);
        $w = $pdf->GetStringWidth($job)+6;
        $pdf->SetX((170-$w)/2);
        $pdf->Cell(40, 10, 'Name:', 1, 0, 'C');
        $pdf->Cell($w, 10, $name, 1, 1, 'C');

        $pdf->SetX((170-$w)/2);
        $pdf->Cell(40, 10, 'DOB:', 1, 0, 'C');
        $pdf->Cell($w, 10, $dob, 1, 1, 'C');

        $pdf->SetX((170-$w)/2);
        $pdf->Cell(40, 10, 'Job:', 1, 0, 'C');
        $pdf->Cell($w, 10, $job, 1, 1, 'C');
        $pdf->Output();
    }   
}
?>

<html>
    <head lang="pl">
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        
        <p>
            <!-- tabela z wczytywaniem danych -->
            <table>
                <tr>
                    <td>
                        <form action="index.php" method="get">
                            <ul>
                                <li>
                                    <div style="float:left">NIP: <br /><input type="text" name="nip" maxlength="10" id="" size="15px" /></div>
                                    <div style="float:right">Kwota: <br /><input type="text" name="price" id="" size="15px" /></div>
                                        <p></p>
                                    <div style="float:left">Nazwisko:<br /><input type="text" name="surname" id="" size="15px" /></div>
                                    <div style="float:right">Jednostka: <br /><input type="text" name="unit" id="" size="15px" /></div>
                                        <p></p>
                                    <div style="float:left">Imie: <br /><input type="text" name="name" id="" size="15px" /></div>
                                    <div style="float:right">Typ uslugi: <br /><input type="text" name="service" id="" size="15px" /></div>
                                        <p></p>
                                    <div style="float:left">Cena netto: <br /><input type="text" name="nettoprice" id="" size="15px" /></div>
                                    <div style="float:right">Stawka: <br /><input type="text" name="salary" id="" size="15px" /></div>
                                        <p></p>
                                    <div style="float:left">Wartosc netto: <br /><input type="text" name="nettoworth" id="" size="15px" /></div>
                                    <div style="float:right">Wartosc brutto: <br /><input type="text" name="bruttoworth" id="" size="15px" /></div>
                                    <p> <input type="submit" name="send" value="Wyslij dane" /></p> 
                                </li>
                            </ul>
                        </form>
                    </td>
                </tr>
            </table>
        </p>

       <!-- generowanie pliku pdf PRZYCISK i usuwanie faktur-->

        <form action="index.php" method="get">
            <p><button name="operate" type="button" value="generate"/>wygeneruj .pdf</button></p>
            <p><button name="operate" type="button"  value="clear">Usun faktury</button></p>
        </form>

        <!--   OBSŁUGA DWÓCH PRZYCISKÓW SUBMIT - CLEAR ORAZ GENERATE PDF-->
        <?php
        $a = $_GET['subject'];
        if(isset($_GET['subject'])){
            switch($a){
                case 'generate':
                    echo pdfSave();
                    break;
                case 'clear':
                    file_put_contents("invoceAdd.txt", "");
                    break;  
            }
        }
        

        ?>

        <p>
            <!-- SYMULACJA PDF -->
            <p>
                <fieldset>
                    <form action="index.php" method="post">
                        <legend>FAKTURA NUMER <?php echo invoice_num(sendFormCounter())?></legend> <!--dodanie numeru faktury-->
                       <div style="float:right">Data: <?php echo date("y-m-d")?></div> 

                        <!-- dane sprzedawcy (stałe)-->
                        <div>
                            <b>SPRZEDAWCA:</b>
                        </div>
                        <div>nazwa firmy</div>
                        <div>Jana Pawla II 1</div>
                        <div>00-000 miasto</div>
                        <div>Nip: 0000000000</div>
                        <div>numer konta</div>
                        <div>00 0000 0000 0000 0000 0000 0000</div>

                        <?php echo dataInput();?>
                        <p>
                            <style> td, th { border: 1px solid black; } </style><!-- ustawienie czarnego obramowania tabeli w CSS -->
                        
                            
    <!-- TWORZENIE TABELI Z ZAKUPAMI PHP -->
                            <?php
                            function build_table($array){
                                $html = '<table>';
                                $html .= '<tr>';
                                foreach($array[0] as $key=>$value){
                                    $html .= '<th>' . htmlspecialchars($key) . '</th>';
                                }
                                $html .= '</tr>';
                                foreach( $array as $key=>$value){
                                    $html .= '<tr>';
                                    foreach($value as $key2=>$value2){
                                        $html .= '<td>' . htmlspecialchars($value2) . '</td>';
                                    }
                                    $html .= '</tr>';
                                }
                                $html .= '</table>';
                                return $html;
                            }
                            $array = array(
                                array('lp'=>'1','NAZWA' => $_GET['service'], 'CENA'=>$_GET['price'], 'JEDNOSTKA'=>$_GET['unit'], 'CENA NETTO'=>$_GET['nettoprice'], 'STAWKA'=> $_GET['salary'], 'WARTOSC NETTO'=> $_GET['nettoworth'], 'WARTOSC BRUTTO'=> $_GET['bruttoworth']));

                            echo build_table($array);
                            ?> 
                 </p>
             </p>
        </p>
        

        <!-- ************ PHP SCRRIPTS  ************-->

        <!-- POBRANIE WPISANYCH PRZEZ UŻYTKOWWNIKA DANYCH-->
        <?php
        function dataInput(){
            if(isset($_GET['nip']) and isset($_GET['name']) and isset($_GET['surname']) and isset($_GET['service']) and isset($_GET['price'])){
                echo '<br>';
                echo '<b>NABYWCA</b><br>';
                echo 'NIP: ';
                echo $_GET['nip'];
                echo '<br>IMIE: ';
                echo $_GET['name'];
                echo '<br>NAZWISKO: ';
                echo $_GET['surname'];
                echo '<br>TYP USŁUGI: ';
                echo $_GET['service'];
                echo '<br>KWOTA: ';
                echo $_GET['price'];
        }
        else{
            echo "nie wyslano danych";
        }
    }
        ?>
     <!-- DODANIE LICZBY WYSTAWIONYCH FAKTUR -->
        <?php
        function invoice_num ($input, $prefix = null) {
            if (is_string($prefix)){
                return sprintf("%s%s", $prefix, str_pad($input,$pad_len, STR_PAD_LEFT));
            }
            return str_pad($input, STR_PAD_LEFT);
        }
        ?>
    <!-- LICZNIK KLIKNIĘĆ -->
        <?php
        function sendFormCounter(){
            if(isset($_GET['send'])){
                if(!($_SESSION['send'])){
                    $_SESSION['send'] = 1;
                }
                else{
                    $count = $_SESSION['send']++;
                }    
            }
            $invoce_num = $_SESSION['send'];
            echo $invoce_num;
            //dataBasseFile($count);
        }
        ?>

        <!-- ZAPISANIE NUMERÓW FAKTURY DO PLIKU TXT -->
        <?php
        function dataBasseFile($number_inv){
            $number_inv = $_SESSION['send'].PHP_EOL;
            $file = fopen('invoceAdd.txt', 'a');
            fwrite($file, $number_inv);
            fclose($file);

            return $file;
        }
        ?>

        <!-- USUWANIE DANYCH ZAPISANYCH W PLIKU invoceAdd.txt -->
        <?php
        function fileClear(){
            if(isset($_GET['clear'])){
                $fp = fopen('invoceApp.txt', "r+");
                ftruncate($fp, 0);
                fclose($fp);
            }
        }
        ?>
    </body>

</html>