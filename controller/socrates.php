<?php
require_once( "question.php");

class Socrates
{
    private $question = false;
    private $log     = false;
    private $source  = false;

    public function __construct()
    {
        $this->question = new Question();
    }

    public function model( string $config_file, string $log_file ) : bool
    {
        $this->source   = $this->set_source( $config_file );
        $this->log      = $this->set_log( $log_file );
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

    private function get_id()
    {
        return $this->question->get_id();
    }

    private function get_log()
    {
        if ( $this->log )
        {
            return $this->log;
        }
        if ( !array_key_exists( "id", $_SESSION )  )
        {
            $_SESSION[ "id" ] = rand( 111111111111, 999999999999 );
        }
        return
            "|" . $_SESSION[ "id" ]   .
            "|" . $this->get_source() .
            "|" . $this->get_answer() .
            "|" . $this->get_id()     . PHP_EOL;
    }

    private function get_answer()
    {
        if ( !array_key_exists( "answer", $_GET ) )
        {
            return false;
        }
        return $_GET[ "answer" ];
    }

    private function get_source()
    {
        return $this->source;
    }

    private function set_log( $log_file ) : string
    {
        if ( empty( $this->get_source() ) || !file_exists( $log_file ) || !is_writable( $log_file ) )
        {
            session_unset();
            session_destroy();
            return false;
        }
        session_start();
        file_put_contents( $log_file, $this->get_log(), FILE_APPEND | LOCK_EX );
        return $this->get_log();
    }

    private function set_source( string $source_file )
    {
        if ( !array_key_exists( "source", $_GET ) || !file_exists( $source_file ) )
        {
            return false;
        }
        $valid_sources = explode( "\n",  file_get_contents( $source_file ) );
        if( !in_array( hash( "sha256", $_GET[ "source" ] ), $valid_sources ) )
        {
            return false;
        }
        return $_GET[ "source" ];
    }
}
