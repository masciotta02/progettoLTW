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

// Se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i dati dall'annuncio inviato dal modulo HTML
    $indirizzo = $_POST['indirizzo'];
    $citta = $_POST['citta'];
    $prezzo = $_POST['prezzo'];
    $camere = $_POST['camere'];
    $metri_quadri = $_POST['metriQuadri'];
    $descrizione = $_POST['descrizione'];
    $telefono = $_POST['telefono'];

    // Carica l'immagine dal modulo HTML
    $immagine = file_get_contents($_FILES['immagine']['tmp_name']);

    // Salva l'immagine nel server
    $percorso_immagine = "im-case/" . $_FILES['immagine']['name'];
    move_uploaded_file($_FILES['immagine']['tmp_name'], $percorso_immagine);

    // Prepara la query SQL per l'inserimento dell'annuncio
    $query = "INSERT INTO annunci_case (immagine, prezzo, indirizzo, città, numero_camere, metri_quadri, descrizione,image_path,num_tel) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";

    // Esegui la query con i parametri
    $result = pg_query_params($conn, $query, array(
        pg_escape_bytea($immagine),
        $prezzo,
        $indirizzo,
        $citta,
        $camere,
        $metri_quadri,
        $descrizione,
        $percorso_immagine,
        $telefono
    ));

    // Verifica se la query è stata eseguita con successo
    if ($result) {
        echo "Annuncio aggiunto con successo! <a href = ./RentLogin.html> Premi qui </a>
        per vederlo";
    } else {
        echo "Si è verificato un errore durante l'aggiunta dell'annuncio.";
    }

    // Chiudi la connessione al database
    pg_close($conn);
}
?>
