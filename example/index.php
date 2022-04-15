<?php
    
    use superdeeid\uricleaner\uricleaner;
        
    //-- example 1

    $uri = new uricleaner("https://qwe.asdasd.com/index.php?q=100&userid=34&sexe=f");
    
    echo "<pre>";
    var_dump( $uri->getOriginalsQueries("array") );
    var_dump( $uri->keepOnlyQueries([ 'q', 'w', 'userid'],"array") );
    
    $uri->keepOnlyQueries([ 'q', 'w', 'userid'],"string");
    var_dump( $uri->getCompleteUrl(true) );
    echo "</pre>";


    //-- example 2

    $uri = new uricleaner(); //-- actual url
    
    echo "<pre>";
    var_dump( $uri->getOriginalsQueries("array") );
    var_dump( $uri->keepOnlyQueries([ 'q', 'w', 'userid'],"array") );    
    var_dump( $uri->getCompleteUrl() ); //-- return the original querystring
    echo "</pre>";