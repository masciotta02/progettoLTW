<?php
// Connessione al database PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "nome_database";
$user = "nome_utente";
$password = "password";

$conn = pg_connect("host=localhost port=5432 dbname=annuncicase user=postgres password=biar");

// Verifica la connessione
if (!$conn) {
    die("Connessione al database fallita.");
}

// Query per recuperare gli annunci con limitazione per l'impaginazione
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 10; // Numero di annunci per pagina
$offset = ($page - 1) * $items_per_page;

$query = "SELECT * FROM annunci_case ORDER BY id LIMIT $items_per_page OFFSET $offset";
$result = pg_query($conn, $query);

$annunci = array();

if ($result) {
    // Loop attraverso gli annunci e aggiungili all'array $annunci
    while ($row = pg_fetch_assoc($result)) {
        // Converti l'immagine in Base64
        $immagine_base64 = base64_encode($row['immagine']);
        // Aggiungi l'immagine codificata in Base64 all'array $annunci
        $row['immagine'] = $immagine_base64;

        $annunci[] = $row;

    }
}

// Chiudi la connessione
pg_close($conn);

// Restituisci gli annunci come dati JSON
header('Content-Type: application/json');
echo json_encode($annunci);
?>

