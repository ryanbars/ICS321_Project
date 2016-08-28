<div>
    <p class="lead">Welcome, <?php echo $_SESSION['user_name']; ?>. </p>
    <?php
		$host  = "localhost";
		$un    = "user1";
		$pw    = "password1";
		$db    = "database";
		$user = $_SESSION['user_name'];
		
			$conn = mysqli_connect($host, $un, $pw, $db);
			if ($conn -> connect_errno){
				echo "Failed to connect to MySQL: (".$conn->connect_errno.")".$conn->connect_error;
			}
			if (isset($_POST["submit"])){
			$sql="INSERT INTO reservations (RmID, userName, InDate, OutDate) 
				  Select RmID,
				  '$_SESSION[user_name]' as userName,
				  '$_POST[CheckIN]' as InDate,
				  '$_POST[CheckOUT]' as OutDate
				  From rooms
				  Where RmType = '$_POST[Rmtype]' AND RmID not in (SELECT RmID FROM reservations WHERE '$_POST[CheckIN]' between InDate and OutDate)
				  LIMIT 1";
			mysqli_query($conn,$sql);
			
			
			$sql3="UPDATE roomcount
				   SET RoomsAvailable = RoomsAvailable-1
				   Where RmType = '$_POST[Rmtype]'";
			mysqli_query($conn,$sql3);
			
			
			$sql2="INSERT INTO Guests (Name, Phone, Credit_Card)
					        VALUES ('$_POST[name]', '$_POST[phone]', '$_POST[credit]')";
			if (!mysqli_query($conn,$sql2)){
						echo('Error: ' . mysqli_error($conn));
					}
			
			}
			 
		?>
		<form name= "exampleform"  method="post">
        Full Name: <input type= "text" name="name">
        <br />
        Phone Number: <input type= "text" name="phone">
        <br />
        Credit Card: <input type= "text" name="credit">
        <br />
        
		Room Type: <select name="Rmtype", id="Rmtype"> <option value="SMALL">Small</option>
														<option value="MEDIUM">Medium</option>
														<option value="LARGE">Large</option>
				   </select>
		<br />
		Check In Date: <input type= "text" name="CheckIN" size="25">
        <br />
        Check Out Date: <input type= "text" name="CheckOUT">
        <br />
        
        
		<input type = "submit" value = "Enter into System" name = "submit">
        <input type = "submit" value = "Display Reserved Rooms" name = "display">
		</form>

		<br />
        
        <?php
			if(isset($_POST["display"]))
			{
				//Print rooms taken
				$sqlquery = "SELECT * FROM reservations as x";
				$result = mysqli_query($conn,$sqlquery);
				
				echo "<table border='1', align='center'>
				<caption>All Booked Rooms</caption>
				<tr>
				<th>RmID</th>
				<th>InDate</th>
				<th>OutDate</th>
				</tr>";

				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
					echo "<td>" . $row['RmID'] . "</td>";
					echo "<td>" . $row['InDate'] . "</td>";
					echo "<td>" . $row['OutDate'] . "</td>";
					echo "</tr>";
					echo "<br>";
				}
				
				//Print rooms available
				$sqlquery2 = "SELECT * FROM roomcount as x";
				$result = mysqli_query($conn,$sqlquery2);
				
				echo "<table border='1', align='center'>
				<caption>Type of Rooms Available</caption>
				<tr>
				<th>Room Type</th>
				<th>Number of Rooms Available</th>
				</tr>";

				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
					echo "<td>" . $row['RmType'] . "</td>";
					echo "<td>" . $row['RoomsAvailable'] . "</td>";
					echo "</tr>";
					echo "<br>";
				}
			}
	?>
		
			
	<?php
	function getRoomsBooked($conn,$user_name,$counter){
		$count = 0;
		$users = "SELECT DISTINCT UserName FROM reservation";
		$getUserRooms = mysqli_query($conn, $users);
		while ($row = mysqli_fetch_array($getUserRooms)){
			if ($counter == 0){
				echo $row['RmID']."<br>";	
			}
		$count +=1;
		}
	return $count;
  	}
	
	echo "Your currently booked rooms: <br/>";
	$user = $_SESSION['user_name'];
	$result = mysqli_query($conn,"SELECT * FROM reservations WHERE userName = '$user'");
	mysqli_error($conn);
	while($row = mysqli_fetch_array($result))
	  {
		  echo $row['userName'] . " you have booked room#" . $row['RmID'] . " from " . $row['InDate'] . " till " . $row['OutDate'];
		  echo "<br>";
	  }
	?>
</div>


<div>
<br />
<br />
    <a href="index.php?logout">Logout</a>
</div>