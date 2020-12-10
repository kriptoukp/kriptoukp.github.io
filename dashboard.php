<?php 
    session_start();
    include("database.php");
    extract($_POST);


    if (!isset($_SESSION["user"]))
    {
        header('Location: http://localhost/kripto/login.php');
    exit;
    }

    if(isset($_POST['kirim']))
    {
        $usr = $_SESSION['user'];
        $text = $_POST['texts'];
        $receiver = $_POST['rec'];  
        $query="insert into `message`(`id`, `message`, `sender`, `receiver`, `date`) values ('','$text','$usr','$receiver',CURRENT_TIMESTAMP)";
        // echo $text;
        // echo $receiver;
        $rs=mysqli_query($conn,$query)or die("Could Not Perform the Query");
        exit();
    }

    if(isset($_POST['showlistchat'])){
        $usr = $_SESSION['user'];
        $rs=mysqli_query($conn,"select distinct person from ( select sender as person from message WHERE receiver = '$usr' union select receiver as person from message WHERE sender = '$usr' ) as person");
        while($row=mysqli_fetch_array($rs)){
			echo "<span usr=".$row[0].">".$row[0]."</span><br>";
		}
        exit();
    }
    if(isset($_POST['showdetailchat'])){
        $usr = $_SESSION['user'];
        $usr2 = $_POST['user2']; 
        $rs=mysqli_query($conn,"SELECT * FROM `message` WHERE (`sender` = '$usr2' AND `receiver` = '$usr') OR (`sender` = '$usr' AND `receiver` = '$usr2') ORDER BY `date`");
        while($row=mysqli_fetch_array($rs)){
            if($row[2] == $usr){
                echo "
                    <div class='containers light'>
                    <span class='time-left'>".$row[4]."</span><br>
                    <p class='kanan'>".$row[1]."</p>
                    </div>
                ";
            }else{
                echo "
                    <div class='containers darker'>
                    <span class='time-left'>".$row[4]."</span><br>
                    <p class='kiri'>".$row[1]."</p>
                    </div>
                ";
            }

        }
        exit();
    }

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function(){
            console.log("ready");
            var usr2;
            setInterval(function(){ refresh(); }, 500);
            function refresh(){
                console.log("REFRESH");
                loaddata();
                if (usr2){
                    loadchat();
                }
            };

            function kirim(receiver,teks){
                $.ajax({
					url  : "dashboard.php",
					type : "POST",
					async : false,
					data : {
						kirim :1,
                        texts : teks,
                        rec : receiver
						},
					success : function(res){
                        console.log(res);
					}
				});
            }
            function loaddata(){
                // console.log("LOAD DATA");
                $.ajax({
					url  : "dashboard.php",
					type : "POST",
					async : false,
					data : {
						showlistchat :1
						},
					success : function(res){
                        $(".listchat").empty();
                        $(".listchat").append(res);
						// console.log(res);
					}
				});
            }
            function loadchat(){
                // console.log("LOAD CHAT");
                $.ajax({
					url  : "dashboard.php",
					type : "POST",
					async : false,
					data : {
						showdetailchat :1,
                        user2 : usr2
						},
					success : function(res){
                        $(".detailchat").empty();
                        $(".detailchat").append(res);
						// console.log(res);
					}
				});
            }
            $('body').delegate('span','click',function(){
                console.log("SPAN CLICK");
                console.log($(this).attr('usr'));
                usr2 = $(this).attr('usr');
                loadchat();
                
            });
            $('body').delegate('#kirim','click',function(){
                console.log("KIRIM");
                console.log($("#name").val());
                console.log($("#teksarea").val());
                kirim($("#name").val(),$("#teksarea").val());
            });

        });
    </script>
    
    <div class="container">
    <?php
    echo "Heello,".$_SESSION["user"];?>
    <form method="post">
        <label for=""pwd"> User Id</label>
        <input type="text" id="name" name="receiver"><br><br>
        <div class="form-group">
            <label for="teksarea">Your message</label>
            <textarea class="form-control" id="teksarea" rows="3" name="text"></textarea>
        </div>
        <a id="kirim"> KIRIM </a><br>
    </form>
        <div class="row">
            <div class="col-lg-3">
                list chat
                <div class="listchat">
                    Test
                </div>
            </div>
            <div class="col-lg-9">
                <div class="detailchat">
                </div>
            </div>
        </div>
    </div>
  </body>
  <style>
    .containers {
        border: 2px solid #dedede;
        border-radius: 5px;
        padding: 50px;
        margin: 10px 0;
    }

    /* Darker chat container */
    .darker {
        border-color: #ccc;
        background-color: #ddd;
    }
    .light {
        border-color: #ccc;
        background-color: #52B2BF ;
    }
    .kanan{
        float : right;
    }
    .kiri{
        float : left;
    }
    
  </style>
</html>