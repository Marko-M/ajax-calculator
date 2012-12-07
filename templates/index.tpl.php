<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8" />
        <title><?php echo APP_NAME; ?></title>

        <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="css/calculator.css" />

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/calculator.js"></script>  
    </head>
    
    <body>
        <div id="calculator-wrapper" class="ui-widget">
            <div id="calculator-name">
                <?php echo APP_NAME; ?>
            </div>
            <div id="calculator-div"></div> 
            <div id="calculator-input-wrapper" >
                <input id="calculator-input" />
            </div>             
        </div>
    </body>
    
</html>
