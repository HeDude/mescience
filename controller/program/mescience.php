<?php
class MeScience
{
    public function __construct()
    {
    }

    public function model( string $config_file, string $log_file ) : bool
    {
        return true;
    }

    public function view( string $template ) : bool
    {
        if ( file_exists( $template ) )
        {
            require_once $template;
            return true;
        }
        return false;
    }
}
