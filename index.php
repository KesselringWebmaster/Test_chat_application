<?php
/* The main application process starts here.*/
session_start(); //starting session
require_once './includes.php';//inculiding necessary project files
$application = ApplicationController::getInstance();
$application->run();//running an application processor
$messages = $application->getMessagesContent();//getting messages to display
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Chat Application</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8 "/>
    <link rel="stylesheet" href="public/css/foundation.css">
    <!-- Standard CSS of Foundation -->
    <link rel="stylesheet" href="public/css/mystyle.css">
    <!-- My personal CSS file -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <!-- Adding jquery -->
</head>
<body>
<fieldset class="large-6 large-centered columns">
    <legend> Test Task: Chat Application</legend>
    <div class="row">
        <div class="large-12 columns">
            <div id="listmessages" class="panel callout radius"
                 style="height:500px;overflow-x: hidden;overflow-y: auto;">
                <ol class="list">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $mess): ?>
                            <li>
                                At <i style="font-size: 110%;"><?= gmdate($mess['time']); ?> user
                                    <strong><?= $mess['nick_name']; ?></strong> says:</i><br/>
                                <?= $mess['message']; ?>
                            </li>
                            <hr/>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li> If you want to start a conversation type something up.</li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div>
    <form role="form" id="chatForm" method="POST" action="index.php">
        <div class="row" input-wrapper>
            <div class="large-12 large-centered columns">
                <label><strong>Enter Message:</strong>
                    <input type="text" name="message" value="" id="number" autofocus autocomplete="off"/>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="large-12 large-centered columns">
                <button class="button expand radius" type="submit">Send Message</button>
            </div>
        </div>
    </form>
</fieldset>

<!-- Here I call the function to add necessary options to a form: event and xml http request object initialization -->
<script type="text/javascript" src="public/js/my.js"></script>

</body>
</html>