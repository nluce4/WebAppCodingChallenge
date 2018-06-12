<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
?>

<div id="headerContainer" class="container-fluid header-container">
    <div class="row">
        <div id="logoContainer" class="col-xs-2">
            <img style="border: 2px solid white" src="images/profile.jpg"/>
        </div>

        <div id="titleContainer" class="col-xs-7">
            <h1 style="color: white"><b>Nicholas Luce | Coding Challenge</b></h1>
            <h3 style="color: white">HTML5, CSS, JavaScript, PHP, and MySQL hosted by Heroku.</h3>
            <h3 style="color: white">Code available <a href="https://github.com/nluce4/WebAppCodingChallenge"><u>here</u></a>.</h3>
        </div>

    </div>
    <hr style="margin: 10px 0px 20px 0px;"/>
</div>
