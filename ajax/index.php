<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>AJAX Beispiel</title>
        
        <!-- jQuery einbinden -->
        <script src="js/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <h1>AJAX Beispiel</h1>
        
        <div id="content">
            
        </div>
        <button id="load-content-btn">Content nachladen</button>
        
        <script>
            // wenn auf den Button mit der id #load-content-btn geklickt wird
            $('#load-content-btn').click(function(event) {
                
                // statische Inhalte in content hinzufügen
                //$('#content').append('<p>Content</p>');
                
                // Inhalte aus ajax.html nachladen und in #content anzeigen (bestehender Inhalt wird ersetzt)
                //$('#content').load('ajax.html');
                
                // Inhalte aus ajax.html nachladen und an den bestehenden Inhalt von #content anfügen
                /*$.ajax({
                    url: 'ajax.html',
                    method: 'GET'
                }).done(function(data) {
                    $('#content').append(data);
                });*/
                
                $.get('ajax.html', function(data) {
                    $('#content').append(data);
                });
            });
        </script>
    </body>
</html>
