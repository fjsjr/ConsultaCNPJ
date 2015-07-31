
<?php
function ordenaPontos( $a, $b ) {
    if ( $a['pontos'] == $b['pontos'] ) {
        return 0;
    }
    return ( $a['pontos'] < $b['pontos'] ) ? -1 : 1;
}
$ranking = array(
    0 => array( 'nome' => 'Davi', 'pontos' => 2 ),
    1 => array( 'nome' => 'Letícia', 'pontos' => 4 ),
    2 => array( 'nome' => 'Francisco', 'pontos' => 1 ),
    3 => array( 'nome' => 'Cecília', 'pontos' => 3 ),
);
usort( $ranking, 'ordenaPontos' );

var_dump($ranking);
