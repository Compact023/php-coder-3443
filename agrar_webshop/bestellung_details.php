<?php
    require_once 'functions.php';
    $conn = connectToDB();
    
    $bestellungId = (int) getParameter('id', 0);
    
    $query = "SELECT bestellung_id, bestelldatum, bestellstatus, kunden.kunde_id, vorname, nachname, email, telefonnummer, strasse, ort, plz"
           . " FROM bestellungen"
           . " JOIN kunden ON bestellungen.kunde_id = kunden.kunde_id"
           . " JOIN orte ON kunden.ort_id = orte.ort_id"
           . " WHERE bestellung_id = ?";
    
    $stmt = $conn->prepare($query);
    
    $stmt->bind_param('i', $bestellungId);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $bestellung = null;
    if ($result && $result->num_rows == 1) {
        $bestellung = $result->fetch_object();
    }
    
    // Bestellpositionen auslesen
    $bestellpositionen = [];
    
    if ($bestellung) {
        $query = "SELECT produkte.*, menge, (menge * verkaufspreis) AS gesamtpreis"
           . " FROM bestellpositionen"
           . " JOIN produkte ON produkte.produkt_id = bestellpositionen.produkt_id"
           . " WHERE bestellung_id = ?";
    
        $stmt = $conn->prepare($query);

        $stmt->bind_param('i', $bestellungId);

        $stmt->execute();

        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $bestellpositionen[] = $row;
            }
        }
    }
    
    closeDB($conn);
    
    $pageTitle = 'Bestellung Detailansicht';
?>

<?php require_once 'inc/header.php'; ?>

<h1><?= $pageTitle ?></h1>

<?php if ($bestellung): ?>

    <table>
        <tbody>
            <tr>
                <th>Bestelldatum</th>
                <td>
                    <?= date('d.m.Y H:i', strtotime($bestellung->bestelldatum)) ?>
                </td>
            </tr>
            <tr>
                <th>Bestellstatus</th>
                <td><?= $bestellung->bestellstatus ?></td>
            </tr>
            <tr>
                <th style="vertical-align: top">Kunde</th>
                <td>
                    <?= $bestellung->kunde_id ?><br>
                    <?= $bestellung->vorname . ' ' . $bestellung->nachname ?><br>
                    
                    <?= $bestellung->strasse ?><br>
                    <?= $bestellung->plz . ' ' . $bestellung->ort ?><br>
                    
                    <a href="mailto:<?= $bestellung->email ?>"><?= $bestellung->email ?></a><br>
                    <?= $bestellung->telefonnummer ?>
                </td>
            </tr>
        </tbody>
    </table>

    <h2>Bestellpositionen</h2>
    <?php if (count($bestellpositionen) > 0): ?>
        <?php $gesamtSumme = 0; ?>
        <table>
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Produkt</th>
                    <th>Einkaufspreis</th>
                    <th>Verkaufspreis</th>
                    <th>Menge</th>
                    <th>Gesamtpreis</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bestellpositionen as $key => $bestellposition): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $bestellposition->produkt_id . ' ' . $bestellposition->name ?></td>
                        <td><?= number_format($bestellposition->einkaufspreis, 2, ',', '.') ?></td>
                        <td><?= number_format($bestellposition->verkaufspreis, 2, ',', '.') ?></td>
                        <td><?= $bestellposition->menge ?></td>
                        <td><?= number_format($bestellposition->gesamtpreis, 2, ',', '.') ?></td>
                    </tr>
                    <?php $gesamtSumme += $bestellposition->gesamtpreis; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><strong>Gesamtsumme:<strong></td>
                    <td><strong><?= number_format($gesamtSumme, 2, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p>Keine Bestellpositionen gefunden.</p>
    <?php endif; ?>

<?php else: ?>
    <p>Bestellung nicht gefunden.</p>
<?php endif; ?>

<?php require_once 'inc/footer.php'; ?>