<?php
    require_once 'functions.php';
    $conn = connectToDB();
    
    $searchParam = getParameter('search', '');
    
    $orderAttributeParam = getParameter('order', '');
    $orderDirectionParam = strtolower(getParameter('dir', ''));
    
    $orderDirection = ($orderDirectionParam == 'desc' ? 'DESC' : 'ASC');
    
    switch ($orderAttributeParam) {
        case 'id':
            $orderAttribute = 'bestellung_id';
            break;
        
        case 'kunde':
            $orderAttribute = 'nachname';
            break;
        
        case 'datum':
            $orderAttribute = 'bestelldatum';
            break;
        
        case 'status':
            $orderAttribute = 'bestellstatus';
            break;
        
        case 'bestellwert':
            $orderAttribute = 'bestellsumme';
            break;
        
        default:
            $orderAttribute = 'bestelldatum';
            $orderDirection = 'DESC'; 
    }
    
    $query = "SELECT bestellungen.bestellung_id, bestelldatum, bestellstatus, vorname, nachname, SUM(menge * verkaufspreis) AS bestellsumme"
           . " FROM bestellungen"
           . " JOIN kunden ON bestellungen.kunde_id = kunden.kunde_id"
           . " JOIN bestellpositionen ON bestellungen.bestellung_id = bestellpositionen.bestellung_id"
           . " JOIN produkte ON produkte.produkt_id = bestellpositionen.produkt_id"
           . " WHERE vorname LIKE ? || nachname LIKE ?"
           . " GROUP BY bestellungen.bestellung_id"
           . " ORDER BY $orderAttribute $orderDirection";
    
    // typischer Aufbau eines Select Statements
    /*
     * SELECT Attribut1, Attribut2, COUNT(*) AS Anzahl, ...
     * FROM tabelle1
     * JOIN tabelle2 ON ...
     * JOIN tabelle3 ON ...
     * WHERE x > y
     * GROUP BY Attribut1
     * HAVING COUNT(*) > 2
     * ORDER BY Attribut1 DESC
     * LIMIT 10;
     */
    
    $stmt = $conn->prepare($query);
    
    $searchText = '%%';
    if (!empty($searchParam)) {
        $searchText = "%$searchParam%";
    }
    
    $stmt->bind_param('ss', $searchText, $searchText);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $bestellungen = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $bestellungen[] = $row;
        }
    }
    
    closeDB($conn);
    
    $pageTitle = 'Bestellungen';
?>

<?php require_once 'inc/header.php'; ?>

<h1><?= $pageTitle ?></h1>

<?php if (count($bestellungen) > 0 || !empty($searchParam)): ?>
    <form action="index.php" method="GET">
        <label for="search">Suche</label>
        <input type="search" id="search" name="search" value="<?= $searchParam ?>">
        <button type="submit">suchen</button>
    </form>
<?php endif; ?>

<?php if (count($bestellungen) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>
                    <a href="index.php?order=id&dir=<?= (empty($orderDirectionParam) || $orderDirectionParam == 'desc' ? 'asc' : 'desc') ?>">Nr.</a>
                </th>
                <th>
                    <a href="index.php?order=kunde&dir=<?= (empty($orderDirectionParam) || $orderDirectionParam == 'desc' ? 'asc' : 'desc') ?>">Kunde</a>
                </th>
                <th>
                    <a href="index.php?order=datum&dir=<?= (empty($orderDirectionParam) || $orderDirectionParam == 'desc' ? 'asc' : 'desc') ?>">Datum</a>
                </th>
                <th>
                    <a href="index.php?order=status&dir=<?= (empty($orderDirectionParam) || $orderDirectionParam == 'desc' ? 'asc' : 'desc') ?>">Status</a>
                </th>
                <th>
                    <a href="index.php?order=bestellwert&dir=<?= (empty($orderDirectionParam) || $orderDirectionParam == 'desc' ? 'asc' : 'desc') ?>">Bestellwert</a>
                </th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bestellungen as $bestellung): ?>
                <tr>
                    <td><?= $bestellung->bestellung_id ?></td>
                    <td><?= $bestellung->nachname . ' ' . $bestellung->vorname ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($bestellung->bestelldatum)) ?></td>
                    <td><?= $bestellung->bestellstatus ?></td>
                    <td><?= number_format($bestellung->bestellsumme, 2, ',', '.') ?></td>
                    <td>
                        <a href="bestellung_details.php?id=<?= $bestellung->bestellung_id ?>">Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Keine Bestellungen vorhanden.</p>
<?php endif; ?>

<?php require_once 'inc/footer.php'; ?>