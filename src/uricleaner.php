<?php
    
    namespace superdeeid;
    
    class uricleaner
    {
        private string $keepKeysValuesPairString;
        private string $fullUri;
        
        public function __construct( string $uri = "" )
        {
            if ( $uri === "" ) {
                $_http = ( !isset( $_SERVER['HTTPS'] ) ) ? "http" : "https";
                $uri   = $_http . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            }
            
            $_fullUri                   = pathinfo( $uri );
            $_fullUri['dirname']        .= '/';
            $_fullUri['_queriesArray']  = [];
            $_fullUri['_queriesString'] = "";
            
            parse_str( parse_url( $uri, PHP_URL_QUERY ), $_queries );
            
            if ( !empty( $_queries ) ) {
                $_fullUri['_queriesArray'] = $_queries;
                [
                    $_fullUri['basename'],
                    $_fullUri['_queriesString'],
                ] = explode( "?", $_fullUri['basename'], 2 );
            }
            
            $_ext                     = explode( "?", $_fullUri['extension'], 2 );
            $_fullUri['extension']    = $_ext[0];
            $_fullUri['_completeURL'] = rtrim( $_fullUri['dirname'] . $_fullUri['basename'] . "?" . $_fullUri['_queriesString'], '?' );
            
            $this->fullUri = $_fullUri;
            
            $this->keepKeysValuesPairString = "";
        } //-- /__construct()
        
        /**
         * return the original queries in a string output if format is "string".
         * return the orignial queries in a array output if format is "array".
         *
         * @param string $format
         *
         * @return mixed|string
         */
        public function getOriginalsQueries( string $format = "string" )
        {
            if ( $format === 'array' ) {
                return $this->fullUri['_queriesArray'];
            }
            
            return $this->fullUri['_queriesString'];
        } //-- /getOriginalsQueries()
        
        
        /**
         * remove queries key pair that's doesnt exist in the $keepArrayKeys array.
         * if the return format is set to "array" it will return an array of both keep and deleted keys/values.
         * if the return format is set to "string" it will return a string of the keep ones only.
         * Also will set $this->keepKeysValuesPairArray and $this->keepKeysValuesPairString (for getCompleteUrl()
         * method JIC).
         *
         * @param array  $keepArrayKeys
         * @param string $format
         *
         * @return array|array[]|string
         */
        public function keepOnlyQueries( array $keepArrayKeys, string $format = "string" )
        {
            $cleaned = [
                'keep'    => [],
                'deleted' => [],
            ];
            foreach ( $this->fullUri['_queriesArray'] as $key => $value ) {
                if ( in_array( $key, $keepArrayKeys ) ) $cleaned['keep'][$key] = $value;
                else $cleaned['deleted'][$key] = $value;
            }
            
            //-- set the value for theses variables in the scope.
            $this->keepKeysValuesPairString = http_build_query( $cleaned['keep'] );
            
            if ( $format === "array" ) return $cleaned;
            
            return $this->keepKeysValuesPairString;
        } //-- /keepOnlyQueries()
        
        /**
         * return the complete url.
         * if set to false (default), it will return the original url before any transformations.
         * if set to true and $this->keepKeysValuesPairString is empty (so no queries treathed yet, it will return also
         * the original url before any transformations). if set to true and $this->keepKeysValuesPairString isn't
         * empty, it will return the url with the cleaned queries.
         *
         * @param bool $mode
         *
         * @return string
         */
        public function getCompleteUrl( bool $mode = FALSE ): string
        {
            if ( $mode === TRUE ) {
                $__ = "?" . ( ( $this->keepKeysValuesPairString !== "" ) ? $this->keepKeysValuesPairString : $this->fullUri['_queriesString'] );
                
                return rtrim( $this->fullUri['dirname'] . $this->fullUri['basename'] . $__, '?' );
            }
            
            return $this->fullUri['_completeURL'];
        } //-- /getUri()
    }
    
    //-- ---------------------------------------------------------------------------------------------------------------
    //-- ---------------------------------------------------------------------------------------------------------------
    //-- ---------------------------------------------------------------------------------------------------------------
    //-- ---------------------------------------------------------------------------------------------------------------
    //-- ---------------------------------------------------------------------------------------------------------------