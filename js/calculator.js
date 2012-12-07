var calculator = {
    // Print error span into results div
    go_error: function(){
       $("#calculator-div").html('<span class="calculator-error">Error!</span>');
    },    
    // Update results div
    go_div: function(label){
       $("#calculator-div").text(label.split('=')[1]);
    },
    // Update input
    go_input: function(label){
        $('#calculator-input').val(label.split('=')[0]);
    },
    // Separate ajax call to get results
    calculate: function(term){
        $.ajax({
            // Use GET method
            type: 'GET',
            
            // Backend script
            url: 'ajax.php',
            
            // Input element content
            data: { calc: term },
            
            /* Same domain,
             * free to use use json */
            dataType: 'json',
            
            // Success callback
            success: function(data){
                if(data.length === 0){
                    // If server returned empty array
                    calculator.go_error('Parse error!');                   
                }else{
                    /* If Server returned non empty array
                     * update input element and results div */
                    calculator.go_input(data.label);
                    calculator.go_div(data.label);                   
                }
            }
        });        
    }    
}

$(document).ready(function() {
    // Initiate jQuery-UI autocomplete
    $('#calculator-input').autocomplete({
        // Minimum 2 chars
        minLength: 2,
        
        // Backend script name
        source: 'ajax.php',
        
        // On select event callback
        select: function( e, ui ) {
            // Update input
            calculator.go_input(ui.item.label);
            
            // Update results div
            calculator.go_div(ui.item.label);
        }
    });

    // Triger server side calculation using ENTER key
    $('#calculator-input').bind('keypress', function(e) {
        code = e.keyCode ? e.keyCode : e.which;
        if(code.toString() == 13) {
            /* Prevent all default actions for this input element */
            e.preventDefault();

            var term = $.trim($(this).val());
            if(term != ''){
                // If input isn't empty contact server side
                calculator.calculate(term);
            }
        }
    });
});