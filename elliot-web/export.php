<?php

    session_start();
    include_once("config.php")

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Exportação</title>
</head>
<body>

<?php

$arquivo = ("exporta.xls");

$html = '<table border="1">';
$html = '<tr>';
$html = '<td colspan="5">Exportacao de Relatorios - Elliot</td>';
$html = '</tr>';
$html = '</table>';

$html = '<tr>';
$html = '<td><b>Nome</b></td>';
$html = '<td><b>Paginas</b></td>';
$html = '</tr>';

$resultado = "SELECT user,sum(pages) FROM jobs_log group by user";
$resultado_export = mysql_query($conn, $resultado);

while($row_nomes = mysql_fetch_assoc($resultado_export)){
            $html .= '<tr>';
            $html .= '<td>'.$row_nomes["id"].'</td>';
            $html .= '<td>'.$row_nomes["nome"].'</td>';
            $html .= '</tr>';
            
}

// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename={$arquivo}" );
header ("Content-Description: PHP Generated Data" );

echo $html;
exit;
?>	
</body>
</html>