<?php
require_once 'config.php';
require_once 'functions.php';

connectToDB($conn);






closeDB($conn);
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Verfügbare Gebrauchtwagen</title>
    </head>
    <body>
        <h1>Verfügbare Gebrauchtwagen</h1>
        
        <div> 
            <form action="index.php" method="GET">
               <label>Nach Antriebsart</label>
                <select name="antriebsartid" id="antriebsartid">
                    <option value="" selected>Alle</option>
                    <?php /*foreach ($kurse as $kurs): ?>
                        <option value="<?php echo $kurs->kursort_id ?>"><?php echo $kurs->kursort ?></option>
                    <?php endforeach; */?>
                </select>
                <button type="submit">filtern</button>  
                
            </form>
        </div>
    </body>
</html>
