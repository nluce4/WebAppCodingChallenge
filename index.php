<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";
    
    if(isset($_POST['submit'])) {
        if($_POST['name'] == "" || !isset($_POST['number_of_children']) ||
        !isset($_POST['lower_bound']) || !isset($_POST['upper_bound'])) {
			echo "<script> alert('Missing Data!');</script>";
        }
        else {
            submitFactory($db, htmlspecialchars($_POST['name']), htmlspecialchars($_POST['number_of_children']), htmlspecialchars($_POST['lower_bound']), htmlspecialchars($_POST['upper_bound']));
            echo "<script>closePop();</script>";
        }
    }

    if(isset($_POST['save'])) {
        if($_POST['nameEdit'] == "" || !isset($_POST['number_of_childrenEdit']) ||
        !isset($_POST['lower_boundEdit']) || !isset($_POST['upper_boundEdit'])) {
			echo "<script> alert('Missing Data!');</script>";
        }
        else {
            saveFactory($db, htmlspecialchars($_POST['id']), htmlspecialchars($_POST['nameEdit']), htmlspecialchars($_POST['number_of_childrenEdit']), htmlspecialchars($_POST['lower_boundEdit']), htmlspecialchars($_POST['upper_boundEdit']));
            echo "<script>closePop2();</script>";
        }
    }

    if(isset($_POST['delete'])) {
        deleteFactory($db, $_POST['id']);
    }
?>
<!DOCTYPE html> 
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Coding Challenge</title>
        <link rel="stylesheet" type="text/css" href="css/default.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <script type="text/javascript" src="js/jquery-3.2.0.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
            include 'header.php';
        ?>
        <div class="content">
            <div class="popup" id="popupAdd">
                <form id="addForm" method="post">
                    <label class="list-group-item form-item" style="background-color: rgb(175,175,255)"><b>Add Factory</b></label>
                    <label class="list-group-item form-item">Factory Name (max 50 chars): <input type="text" id="name" name="name" maxlength="50"/></label>
                    <label class="list-group-item form-item">Number of Workers (0 to 15): <input type="number" id="number_of_children" name="number_of_children"/></label>
                    <div class="list-group-item form-item">
                        <label>Worker ID Value Range: </label>
                        <label class="list-group-item">Min: <input type="number" id="lower_bound" name="lower_bound" maxlength="11"/></label>
                        <label class="list-group-item">Max: <input type="number" id="upper_bound" name="upper_bound" maxlength="11"/></label>
                    </div>
                    <button class="list-group-item" style="background-color: rgb(255,175,175); text-align: center" name="cancel" onclick="closePop()"><b>Cancel</b></button>
                    <button class="list-group-item" style="background-color: rgb(175,255,175); text-align: center" name="submit" onclick="validate()"><b>Submit</b></button>
                </form>
            </div>
            <div class="popup" id="popupEdit">
                <form id="editForm" method="post">
                    <input style="display: none" id ="id" name="id"/>
                    <label class="list-group-item form-item" style="background-color: rgb(175,175,255)"><b>Edit Factory</b></label>
                    <button class="list-group-item" style="background-color: rgb(255,175,175); text-align: center;" name="delete" onclick="closePop2()"><b>Delete</b></button>
                    <label class="list-group-item form-item">Factory Name (max 50 chars): <input type="text" id="nameEdit" name="nameEdit" maxlength="50"/></label>
                    <label class="list-group-item form-item">Number of Workers (0 to 15): <input type="number" id="number_of_childrenEdit" name="number_of_childrenEdit"/></label>
                    <div class="list-group-item form-item">
                        <label>Worker ID Value Range: </label>
                        <label class="list-group-item">Min: <input type="number" id="lower_boundEdit" name="lower_boundEdit" maxlength="11"/></label>
                        <label class="list-group-item">Max: <input type="number" id="upper_boundEdit" name="upper_boundEdit" maxlength="11"/></label>
                    </div>
                    <button class="list-group-item" style="background-color: rgb(255,175,175); text-align: center" name="cancel" onclick="closePop2()"><b>Cancel</b></button>
                    <button class="list-group-item" style="background-color: rgb(175,255,175); text-align: center" name="save" onclick="validate2()"><b>Update</b></button>
                </form>
            </div>
            <button class="list-group-item" onclick="addFactory()" id="addBtn"><b>+ Add Factory</b></button>
            <?php
                //get database info
                $factories = getFactoryInfo($db);
                $children = getChildInfo($db);

                makeTree($factories, $children);
            ?>
        </div>
    </body>
</html>
<script>
    function validate(){
        if(document.getElementById("name").value == "" ||
            document.getElementById("number_of_children").value == "" ||
            document.getElementById("lower_bound").value == "" ||
            document.getElementById("upper_bound").value == "" ) 
        {
            alert("One or more values is missing!");
            event.preventDefault();
        } else if (document.getElementById("name").value.length > 50) {
            alert("Factory name needs to be less than 50 chars!");
            event.preventDefault();
        } else if (document.getElementById("number_of_children").value < 0 || document.getElementById("number_of_children").value > 15) {
            alert("Number of Workers needs to be from 0 to 15!");
            event.preventDefault();
        } else if (document.getElementById("lower_bound").value >= document.getElementById("upper_bound").value) {
            alert("Min must be less than Max!");
            event.preventDefault();
        } else if (document.getElementById("lower_bound").value.length > 11 && document.getElementById("upper_bound").value.length > 11) {
            alert("Min or Max value too large! Please use numbers less than 11 digits long.");
            event.preventDefault();
        }
    }
    function validate2(){
        if(document.getElementById("nameEdit").value == "" ||
            document.getElementById("number_of_childrenEdit").value == "" ||
            document.getElementById("lower_boundEdit").value == "" ||
            document.getElementById("upper_boundEdit").value == "" ) 
        {
            alert("One or more values is missing!");
            event.preventDefault();
        } else if (document.getElementById("nameEdit").value.length > 50) {
            alert("Factory name needs to be less than 50 chars!");
            event.preventDefault();
        } else if (document.getElementById("number_of_childrenEdit").value < 0 || document.getElementById("number_of_childrenEdit").value > 15) {
            alert("Number of Workers needs to be from 0 to 15!");
            event.preventDefault();
        } else if (document.getElementById("lower_boundEdit").value >= document.getElementById("upper_boundEdit").value) {
            alert("Min must be less than Max!");
            event.preventDefault();
        } else if (document.getElementById("lower_boundEdit").value.length > 11 && document.getElementById("upper_boundEdit").value.length > 11) {
            alert("Min or Max value too large! Please use numbers less than 11 digits long.");
            event.preventDefault();
        }
    }
    function addFactory() { 
        document.getElementById("popupAdd").style.display = "block";
    }
    function closePop() { 
        document.getElementById("popupAdd").style.display = "none";
    }
    function editFactory(name, children, lower, upper, id) {
        document.getElementById("popupEdit").style.display = "block";
        //fill values
        document.getElementById("nameEdit").value = name;
        document.getElementById("number_of_childrenEdit").value = children;
        document.getElementById("lower_boundEdit").value = lower;
        document.getElementById("upper_boundEdit").value = upper;
        document.getElementById("id").value = id;
    }
    function closePop2() {
        document.getElementById("popupEdit").style.display = "none";
    }
</script>