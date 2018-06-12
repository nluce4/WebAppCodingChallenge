<?php

    include "config.php";

    $db = new mysqli($dbhost, $dbuser, $dbpass, $database);
    if($db->connect_error){
        die("DB Connection Failed: " . $db->connect_error);
    }

    function makeTree($factories, $children) {
        echo "<ul class='list-group'>";
        foreach ($factories as $factory) {
            echo "<button class='factory' onclick='editFactory(".'"'.$factory['name'].'"'.",".$factory['number_of_children'].",".$factory['lower_bound'].",".$factory['upper_bound'].",".$factory['id'].")'><label><b>".$factory['name']."</b></label>";
            echo "<ul class='list-group'>";
            foreach ($children as $child) {
                if($child['parent_id'] == $factory['id'])
                    echo "<li class='list-group-item'><label>".$child['number']."</label></li>";
            }
            echo "</ul>";
            echo "</button>";
        }
        echo "</ul>";
    }

    function getFactoryInfo($db){
        $qry = "SELECT * FROM factories";
        $result = $db->query($qry);
        $factories = array();

        while($row = $result->fetch_assoc()){
            $factories[$row['id']] = array(
                "id" => $row['id'], 
                "name" => $row['name'],
                "number_of_children" => $row['number_of_children'],
                "lower_bound" => $row['lower_bound'],
                "upper_bound" => $row['upper_bound']);
        }

        return $factories;
    }

    function getChildInfo($db){
        $qry = "SELECT * FROM children";
        $result = $db->query($qry);
        $children = array();
        $counter = 0;

        while($row = $result->fetch_assoc()){
            $children[$counter++] = array(
                "parent_id" => $row['parent_id'], 
                "number" => $row['number']);
        }

        return $children;
    }

    function submitFactory($db, $name, $number, $lower, $upper) {
        //insert factory
        $qry = "INSERT INTO factories (name, number_of_children, lower_bound, upper_bound)
                    VALUES ('".$name."',".$number.",".$lower.",".$upper.");";
        $db->query($qry);

        //also get the resulting id
        $qry = "SELECT LAST_INSERT_ID() AS id;";
        $result = $db->query($qry);

        $row = $result->fetch_assoc();
        $id = $row['id'];

        //insert randomly generated children for the factory
        $qry = "INSERT INTO children (parent_id, number) VALUES ";
        for($counter = 1; $counter < $number; $counter++){
            $qry .= "(".$id.",".rand($lower,$upper)."),";
        }
        $qry .= "(".$id.",".rand($lower,$upper).");";
        $db->query($qry);
    }

    function saveFactory($db, $id, $name, $number, $lower, $upper) {
        //update factory
        $qry = "UPDATE factories SET name='".$name."', number_of_children=".$number.", lower_bound=".$lower.", upper_bound=".$upper." WHERE id=".$id.";";
        $db->query($qry);

        //delete factory children
        $qry = "DELETE FROM children WHERE parent_id=".$id.";";
        $db->query($qry);

        //insert randomly generated children for the factory
        $qry = "INSERT INTO children (parent_id, number) VALUES ";
        for($counter = 1; $counter < $number; $counter++){
            $qry .= "(".$id.",".rand($lower,$upper)."),";
        }
        $qry .= "(".$id.",".rand($lower,$upper).");";
        $db->query($qry);
    }

    function deleteFactory($db, $id) {
        //delete factory
        $qry = "DELETE FROM factories WHERE id=".$id.";";
        $db->query($qry);

        //delete factory children
        $qry = "DELETE FROM children WHERE parent_id=".$id.";";
        $db->query($qry);
    }
?>
