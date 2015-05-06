<?php
ini_set('display_errors', 'On');
include 'password.php';
?>

<form action="video.php" method="GET">
    <input type="text" name="film">Enter Movie Name<br>
    <input type="text" name="category">Enter Movie Category (Action,Comedy,etc.)<br>
    <input type="text" name="length">Enter Movie Length (Minutes)<br>
    <input type="submit" name="submit" value="Enter Movie"><br>
    
</form>

<form action="video.php" method="GET">
    <input type="submit" name="delete" value="Delete All Movies"><br>
</form>
    
    


<?php

//host,username,password,database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkerb2-db",$myPassword,"parkerb2-db");
if($mysqli->connect_errno)
    {
        echo "Failed to Connect to MySQL Server: (". $mysqli->connect_errno .") " . $mysqli->connect_errno;
    }
else
    {
        echo "Connection Worked!<br>";
    }
    
    

//delete all records
if (isset($_GET["delete"]))
{
    $dt = "DELETE FROM videos";
    if($mysqli->query($dt) === TRUE)
        {
            echo "All Records Deleted.<br>"; 
        }
    else
        {
            echo "Problem with Deleting all Records<br>";
        }
}
    




//create table
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
        echo "Table Created Successfully!";
    }
else
    {
        echo "Table not Created";
    }
                  

//prepared statement to show movies


if(isset($_GET["submit"]))
   {
    //need to add checks
    $av = "INSERT INTO videos (name,category,length) VALUES (?,?,?)";    
    $stmt = $mysqli->prepare($av);
    $stmt->bind_param("ssi",$_GET["film"],$_GET["category"],$_GET["length"]);
    $stmt->execute();      
    
   }

//create table
$ss = "SELECT * FROM videos";
$displayMovies = $mysqli->query($ss);

?>

<table border="1">
    <tr><td>ID</td> <td>Name</td>   <td>Category</td>   <td>Length</td> <td>Available</td><td>Change Status</td><td>Remove</td>   </tr>
    
<?php

if(isset($_GET["idCheckout"]))
    {
        //$co = "UPDATE videos SET rented  = 
       
    }

if(isset($_GET["idDelete"]))
    {
        echo $_GET["idDelete"];
        $dr = 'DELETE FROM videos WHERE id= $_GET["idDelete"]';
        if($mysqli->query($dr) === TRUE)
            {
                echo "Record Deleted Successfully!";
            }
        else
            {
                echo "Not Deleted";
            }
        
    }



$rentStatus="";
while($row = $displayMovies->fetch_assoc())
    {
        echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["length"] . "</td>";
            
            //display if rented or not          
            if($row["rented"] == 0)
            {$rentStatus="Available";} else {$rentStatus="Checked Out";}
            echo "<td>" . $rentStatus . "</td>";
           
            
?>
    
            <td><form action="video.php" method="GET">
            <button type="submit" name="idCheckout" value='$row["id"]'>Check Out/In Movie</button></td>
            
             <td><form action="video.php" method="GET">
             <button type="submit" name="idDelete" value=$row["id"]>Delete Movie</button></td>    
    
         
             
    
<?php
            
            
            
            
        echo "</tr>";
    }   



?>
    
</table> 
    
    
    
    









