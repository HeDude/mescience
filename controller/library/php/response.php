<?php
class Response
{
    private $next_questions = array();

    public function __construct( $response )
    {
        $this->next_questions = $response;
    }

    public function get_weighted_random()
    {
        $sum_of_all_weights = array_sum( $this->next_questions );
        $random_from_weights = rand( 1, $sum_of_all_weights );
        foreach ( $this->next_questions as $next_question => $weight )
        {
            $random_from_weights -= $weight;
            if ( $random_from_weights <= 0 )
            {
                return $next_question;
            }
        }
        return false;
    }
}
