<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Vérifier si le fichier est valide
if (isset($_POST["submit"])) {
    $csv_mimetypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
    );
    if (in_array($_FILES['fileToUpload']['type'], $csv_mimetypes)){
        $uploadOk = 1;
    } else if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Désolé, le fichier est trop large !";
        $uploadOk = 0;
    } else {
        echo "Désolé, ce n'est pas le bon type de fichier.";
        $uploadOk = 0;
    }
}
// Vérifier que le fichier n'existe pas
if (file_exists($target_file)) {
    echo "Le fichier existe déjà !";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Désolé votre fichier ne peut pas être téléchargé !";

} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $row = 1;
        if (($handle = fopen('uploads/' . $_FILES["fileToUpload"]["name"] , "r")) !== FALSE) {
            $html = '<table style="border-collapse: collapse;"> <tbody>';
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        $html .= '<tr>';
                        $num = count($data);
                        $row++;
                        for ($c=0; $c < $num; $c++) {
                            $html .= '<td style="border: 1px solid #333;">' . $data[$c] . '</td>';
                        }
                    }
                    $html .= '</tr>';

            $html .= '</tbody> </table>';
            echo $html;
            fclose($handle);
        }
    } else {
        echo "Désolé, il y a une erreur pour télécharger le fichier.";
    }
}
?>