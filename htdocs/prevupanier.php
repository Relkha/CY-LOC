<?php  
	session_start();
   
        
        if ( ! isset($_SESSION['PanierRef'])){  // si vous n'avez aucun produit 
            echo "Votre panier est vide";
            
        } 
        else {  // si le panier existe bien 
            echo '<table border="1">';
            echo '<thead> <tr> <td>Nom</td> <td>Ref</td><td>Prix</td>';
             for($i=0; $i< count($_SESSION['PanierNom']); $i++){
                echo '<tr><td>'.$_SESSION['PanierNom'][$i].'</td> ';
                
                echo '<td>'.$_SESSION['PanierRef'][$i].'</td> ';
                echo '<td>'.$_SESSION['PanierPrix'][$i].'€</td>';
            
                
                $total += ($_SESSION['PanierQte'][$i]*$_SESSION['PanierPrix'][$i]); 
            }
            
            echo '<tr><td> Total </td><td>'. $total. '€</td>';
            
        }

?>