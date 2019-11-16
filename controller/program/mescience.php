<?php
class MeScience
{
    private $guides = false;

    public function __construct()
    {
    }

    public function model( string $config_file, string $log_file ) : bool
    {
        $this->guides = $this->set_guide( $config_file );
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

    private function set_guide( string $guide_file )
    {
        if ( !file_exists( $guide_file ) )
        {
            return false;
        }
        $this->guides = json_decode( utf8_encode( file_get_contents( $guide_file ) ), true );
        return $this->guides;
    }
}
