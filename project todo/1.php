<?php
  $servername="localhost";
  $user="root";
  $password="";
  $database="tasks";
  $con=mysqli_connect($servername,$user,$password,$database);
  if(!$con){
    echo "unexpected error from our side.";
  }
  $deleteresult=false;
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    
    <style>
      body{
        /* background:black; */
      }
      .main{
        width:100%;
        display:flex;
        justify-content:space-around;
      }
      .mynavbar{
        position:sticky;
        top:0;
        background-color:#F561BB;
        color:white;
        padding:10px;
        font-size: 30px;
        z-index:999;
      }
      .taskbox{
        background-color:#ff0080;
        width:450px;
        height:100%;
        border:2px solid gray;
        padding:20px;
        border-radius: 15px;
      }
      .addnotebtn{
        /* background-color:black; */
        color:white;
        border-radius:5px;
        color:#3B2D35;
        padding:5px;
        border:none;
        cursor: pointer;
      }
      .editbtn{
        background-color:#ff69b3;
      }
      .deletebtn{
        background-color:#ff0080;
      }
      .cross:hover{
        color:#218F60;
        font-size:30px;
      }
      .cross:active,.cross:visited{
        border:none;
      }
    </style>
</head>
<body>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit your task details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="http://localhost/Likhit/project%20todo/1.php" method="POST">
              <input type="hidden" name="operation" value="edit">
              <input type="hidden" name="snoedit" id="snoedit">
              <div class="form-group">
                <label for="titleedit">task title</label>
                <input type="text" class="form-control in" id="edittitle" name="edittitle" aria-describedby="emailHelp" placeholder="Title of your task" required>
              </div>
              <div class="form-group">
                <label for="descriptionedit">task description</label>
                <input type="text-area" class="form-control" id="editdescription" name="editdescription" placeholder="Description of your task" required>
              </div>
              <button type="submit" class="editnotebtn btn btn-primary"><b>editnote</b></button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
      if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['operation']=='edit'){
        $title=$_POST['edittitle'];
        $description=$_POST['editdescription'];
        $sno=$_POST['snoedit'];
        $q="update tasklist set title='$title',description='$description' where sno='$sno'";
        $result=mysqli_query($con,$q);
      }
    ?>
    <header class="mynavbar">
      <span><b><i>Fancy Notes</i></b></span>
    </header>

    <?php
        if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['operation']=='add'){
          $title=$_POST['title'];
          $description=$_POST['description'];
          $q="insert into tasklist(title,description) values('$title','$description')";
          $result=mysqli_query($con,$q);
          if($result){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>success!</strong>,added your task
            <button type='button' class='close cross' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
            </div>";
          }
        }
      ?>

    <div class="main mt-5">
      <div class="taskbox">
        <form action="http://localhost/Likhit/project%20todo/1.php" method="POST">
          <div class="form-group">
            <input type="hidden" name="operation" value="add">
            <label for="title">task title</label>
            <input type="text" class="form-control in" id="title" name="title" aria-describedby="emailHelp" placeholder="Title of your task" required>
          </div>
          <div class="form-group">
            <label for="description">task description</label>
            <input type="text-area" class="form-control" id="description" name="description" placeholder="Description of your task" required>
          </div>
          <button type="submit" class="addnotebtn"><b>Add note</b></button>
        </form>
      </div>
      
      <?php
      if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['operation']=='del'){
        $reqsno=$_POST['deletebtn'];
        $q="delete from tasklist where sno='$reqsno'";
        $deleteresult=mysqli_query($con,$q);
      }
    ?>
    <?php
      if ($deleteresult) {
        $q1 = "UPDATE stars SET n = n + 1";
        mysqli_query($con, $q1);
        $deleteresult=false;
      }
      
    ?>

      <table class='table table-striped mytable mt-3' id="myTable">
      <thead>
        <tr>
          <th scope='col'>sno</th>
          <th scope='col'>Task Title</th>
          <th scope='col'>Task Description</th>
          <th scope='col'>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $q="select * from tasklist";
        $result=mysqli_query($con,$q);
        $sno=0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno=$sno+1;
          echo "<tr>
          <th scope='row'>".$sno."</th>
          <td>".$row['title']."</td>
          <td>".$row['description']."</td>
          <td>
          <button type='button' class='btn editbtn' data-toggle='modal' data-target='#editModal' id=".$row['sno'].">edit note</button>
          <form action='http://localhost/Likhit/project%20todo/1.php' method='POST' style='display: inline-block'>
            <input type='hidden' name='operation' value='del'>
            <button type='submit' name='deletebtn' class='btn deletebtn' value=".$row['sno'].">X</button>
          </form>
          </td>         
          </tr>";
        }
      ?> 
      </tbody>
      </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#myTable').DataTable({
          "columnDefs": [
          { "targets": [0, 1, 2, 3] }
          ],
          lengthMenu: [ [7, 10, -1], [7, 10, "All"] ],
          pageLength: 7 
        });
      });
    </script>
    <script>
      edits=document.getElementsByClassName('editbtn');
      Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>
          {tr=e.target.parentNode.parentNode;
          title=tr.getElementsByTagName('td')[0].innerText;
          description=tr.getElementsByTagName('td')[1].innerText;
          document.getElementById("edittitle").value=title;
          document.getElementById("editdescription").value=description;
          snoedit.value=e.target.id;
        })
      })
    </script>
    </body>
</html>