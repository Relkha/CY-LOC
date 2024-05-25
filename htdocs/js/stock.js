var i=0; 
    /* for(i=0; i<=15; i++){
        nom = 'ajouter'+i; 
        alert(nom);
         document.getElementById(nom).addEventListener("click", MAJstock); 
    }*/
    
   
function MAJstock(ref){
    alert("selma elk");
    qt = document.getElementById('qte');
    alert("la quantité est"+ qt);
    alert(ref); 

}




/*function sendForm(art, nombre ,id , categorie , stocks ,prix) {
 
    if (nombre>stocks){
        alert("non");
        document.getElementById('.$i.').value="";
        
    }
    else{
   
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
             
                //ligne en commentaire permettant de tester, si réactiver ce que renvoi l'appel php
                rep = xhr.responseText;
 
            }
        };
    
    alert("article ajouté au panier");
    
    xhr.open("POST", "panier3.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
    xhr.send("art=" + art + "&nombre=" + nombre + "&id=" + id + "&categorie=" + categorie + "&prix=" + prix );
}
    location.reload();
}
    function toggle(){
for(i = 0 ; i <document.getElementsByClassName('toto').length ; i++)
{
    document.getElementsByClassName("toto")[i].style.display = document.getElementsByClassName('toto')[i].style.display == 'block' ? "none" : "block";
}}
*/
