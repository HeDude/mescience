<?php
require_once( "controller/answer.php"   );
require_once( "controller/response.php" );

class Question
{
    const API_URL        = 'https://api.hedude.com/question/json/nl';
    const START_QUESTION = 'about_what_may_I_ask_you_something';

    private $answers     = array();
    private $api         = array();
    private $id          = false;
    private $instruction = 'Klik op jouw antwoord!';
    private $title       = false;

    //! The Question id should be in the url or else relocate to the url of the start question
    public function __construct()
    {
        if ( !array_key_exists( "title", $_GET ) )
        {
            $relocate = "Location: ?title=" . self::START_QUESTION;
            if ( array_key_exists( "source", $_GET ) )
            {
                $relocate .= "&source=" . $_GET[ "source" ];
            }
            header( $relocate );
            die();
        }
    }

    public function get_answers() : array
    {
        if ( empty( $this->answers ) )
        {
            if ( $this->get_choices() )
            {
                foreach ( $this->get_choices() as $choice )
                {
                    $this->answers[] = new Answer( $choice[ "answer" ], $choice[ "response" ] );
                }
            }
        }
        return $this->answers;
    }

    public function get_id()
    {
        if ( empty( $this->id ) )
        {
            $this->id = $_GET[ "title" ];
        }
        return $this->id;
    }

    public function get_instruction()
    {
        if ( empty( $this->id ) )
        {
            $this->instruction = 'Pas de url aan!';
        }
        elseif ( !$this->get_choices() )
        {
            $this->instruction = 'Je hebt een uitgang gevonden uit mijn doolhof aan vragen!';
        }
        return $this->instruction;
    }

    public function get_title()
    {
        $api = $this->get_api();
        if ( empty( $this->id ) )
        {
            $this->title = 'Oeps, er is iets mis met de url!';
        }
        elseif ( empty( $this->title ) )
        {
            if ( !$this->get_choices() )
            {
                $this->title = 'Je hebt mijn vragendoolhof verlaten!';
            }
            elseif ( array_key_exists( "title", $api ) && array_key_exists( "title", $api ) )
            {
                $this->title = $api[ "title" ][ "title" ];
            }
            else
            {
                $this->title = 'Oeps, er is iets mis met de vraag!';
            }
        }
        return $this->title;
    }

    private function get_api() : array
    {
        if ( empty ( $this->get_id() ) )
        {
            return array();
        }
        if ( empty( $this->api ) )
        {
            if ( $this->get_id() == "random")
            {
                $api = file_get_contents( self::API_URL );
            }
            else
            {
                $api = file_get_contents( self::API_URL . "?title=" . htmlspecialchars( $this->get_id() ) );
            }

            $this->api = json_decode
            (
                utf8_encode
                (
                    $api
                ),
                JSON_OBJECT_AS_ARRAY
            );
        }
        return $this->api;
    }

    private function get_choices()
    {
        $api = $this->get_api();
        return $api[ "title" ][ "choice" ];
    }
}
