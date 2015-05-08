<?php
ini_set('display_errors', 'On');
include 'password.php';
?>


<form action="video.php" method="GET">
    <input type="text" name="film" required>Enter Movie Name<br>
    <input type="text" name="category">Enter Movie Category (Action,Comedy,etc.)<br>
    <input type="text" name="length" value="0" >Enter Movie Length (Minutes)<br>
    <input type="submit" name="submit" value="Enter Movie"><br>    
</form>


<form action="video.php" method="GET">
    <input type="submit" name="delete" value="Delete All Movies"><br>
</form>
    
    


<?php

//connect to mySql Database
//host,username,password,database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkerb2-db",$myPassword,"parkerb2-db");
    if($mysqli->connect_errno)
        {
            echo "Failed to Connect to MySQL Server: (". $mysqli->connect_errno .") " . $mysqli->connect_errno;
        }
    else
        {
       
        }
        
    

//delete all records from database
if (isset($_GET["delete"]))
    {
        $dt = "DELETE FROM videos";
        
        if($mysqli->query($dt) === TRUE)
            {
                $reset = "ALTER TABLE videos AUTO_INCREMENT = 1";
                $mysqli->query($reset);                      
                
             
            }
        else
            {
               echo $mysqli->errno;
            }
    }
    


if(isset($_GET["idCheckout"]))
    {
      
        $checkStatus = "SELECT rented FROM videos WHERE id = " . $_GET["idCheckout"] . "";
        
        $recordreturned = $mysqli->query($checkStatus);
        $inout = $recordreturned->fetch_object();
    
        echo $inout->rented;
        if($inout->rented == 0)
            
                {
                    $checkOut = "UPDATE videos SET rented = '1' WHERE id = " . $_GET["idCheckout"] ;
                    $mysqli->query($checkOut);
                
                }
                
            else
                {
                
                    $checkIn = "UPDATE videos SET rented = '0' WHERE id = ". $_GET["idCheckout"] ;
                    $mysqli->query($checkIn);
                  
                }

  
       
    }

if(isset($_GET["idDelete"]))
    {
           
            $dr = "DELETE FROM videos WHERE id=" . $_GET["idDelete"];
            
            if($mysqli->query($dr) === TRUE)
                {
                  
                }
            else
                {
                    
                }
                
                   
    }



//create blank table for videos
$ct = "CREATE TABLE videos
                  (
                  id INT AUTO_INCREMENT PRIMARY KEY,
                  name VARCHAR(255) NOT NULL UNIQUE,
                  category VARCHAR(255),
                  length INT UNSIGNED,
                  rented BOOLEAN DEFAULT '0'
                  )";
                  


if($mysqli->query($ct) === TRUE)
    {
       
    }
else
    {
       
    }
                  




//create html table table from database
if(isset($_POST["categories"]) && ($_POST["categories"]!="All"))
    {
        $ss = "SELECT * FROM videos WHERE category = ". $_POST['categories'] ;
        $displayMovies = $mysqli->query($ss); 
    }

else

    {
        $ss = "SELECT * FROM videos";
        $displayMovies = $mysqli->query($ss);
    }



//query database for distince values in category field
$cc ="SELECT DISTINCT category from videos";
$displayCat = $mysqli->query($cc);

//create dropdown selection for categories
echo '<form action="video.php" method="POST">';

    echo "<select name='categories'>";
    echo '<option value="All">Show All Movies </option>';
    while($row = $displayCat->fetch_assoc())    
        {
            echo "<option value={$row["category"]} > {$row["category"]} </option>";
        }
    echo '<input type="submit">';
    echo "</select>";   

echo "</form>";





//insert user data into mySQL database
if(isset($_GET["submit"]))
   {
    
    //is movie length a number
    if(!is_numeric($_GET["length"]) && $_GET["length"]!="")
        {
            echo "Error: Entry for Movie Length is not a number, please re-enter.";            
        }
    //is movie length positive 
    elseif($_GET["length"] < 0)
        {
            echo "Error: Enter a positive number for Movie Length.";
        }
        
    else
    
        {
            $av = "INSERT INTO videos (name,category,length) VALUES (?,?,?)";    
            $stmt = $mysqli->prepare($av);
            $stmt->bind_param("ssi",$_GET["film"],$_GET["category"],$_GET["length"]);
            $stmt->execute();
        }
   }
   
   

?>






<form>
<table border="1">
    <tr><td>ID</td> <td>Name</td>   <td>Category</td>   <td>Length</td> <td>Available</td><td>Change Status</td><td>Remove</td>   </tr>



    
<?php


//create main movies table and buttons
$rentStatus="";
while($row = $displayMovies->fetch_assoc())
    {
        echo "<form>";
        echo "<tr>";
        
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["length"] . "</td>";
            
            //display if rented or not          
            if($row["rented"] == 0)
            {$rentStatus="Available";} else {$rentStatus="Checked Out";}
            echo "<td>" . $rentStatus . "</td>";
            
            $id=$row["id"];
                   
            echo '<td><form action="video.php" method="GET">';
            echo '<input type="submit" name="idCheckout" value="' . $id . '">CheckIn/Out';
            echo '</td>';            
            
            echo '<td><form action="video.php" method="GET">';
            echo '<input type="submit" name="idDelete" value="' . $id . '">Delete';
            echo '</td>';             

        echo "</tr>";
        echo "</form>";
    }
    


?>
    
</table> 
    
    
    
    









