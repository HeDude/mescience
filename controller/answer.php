<?php
class Answer
{
   public $text;
   private $response;

   public function __construct( $text, $response )
   {
       $this->text = $text;
       $this->response = new Response( $response );
   }

   public function get_response() : string
   {
       return $this->response->get_weighted_random();
   }

   public function get_text() : string
   {
       return $this->text;
   }
}
