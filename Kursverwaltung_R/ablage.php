/*
 * 
 * ------Marken holen---------
 * 
 */

$query = "SELECT marken.* FROM marken ";

$stmt = $conn->prepare($query);

$stmt->execute();

$result = $stmt->get_result();

$marken = [];

if($result && $result->num_rows > 0){
    while($row = $result->fetch_object()){
        $marken = $row;
    }
}

/*
 * 
 * ------Antriebsarten holen---------
 * 
 */

$query = "SELECT antriebsarten.* FROM antriebsarten ";

$stmt = $conn->prepare($query);

$stmt->execute();

$result = $stmt->get_result();

$antriebsarten = [];

if($result && $result->num_rows > 0){
    while($row = $result->fetch_object()){
        $antriebsarten = $row;
    }
}