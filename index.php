<?php

if ( empty( $_GET ) )
{
    header("Location: ?title=about_what_may_I_ask_you_something");
    die();
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Me Science - Wijsheid over innerlijk welbevinding</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Keywords" content="Oracle Science Wellbeing Happiness Question">
    <meta name="Description" content="Wisdom about wellbeing">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Source%20Code%20Pro' rel='stylesheet'>
</head>
<body>
<?php
if ( empty( $_GET["title"] ) )
{
   $title = "Oeps, er is iets mis met de url!";
   $main = "            <li><h2>Pas de url aan!</h2></li>" . PHP_EOL;
}
else
{
    if ( $_GET["title"] == "random")
    {
        $api = file_get_contents( "https://api.hedude.com/question/json/nl" );
    }
    else
    {
        $api = file_get_contents( "https://api.hedude.com/question/json/nl?title=" . htmlspecialchars( $_GET["title"] ) );
    }

    $answers = json_decode
    (
        utf8_encode
        (
            $api
        ),
        JSON_OBJECT_AS_ARRAY
    );
    $title = "Je hebt mijn vragendoolhof verlaten";
    if ( !empty ( $answers ) )
    {
        $question = new Question( $answers[ "title" ][ "title" ] );
        $title = $question->get_text();


        if ( $answers[ "title" ][ "choice" ] )
        {
            foreach( $answers[ "title" ][ "choice" ] as $choice )
            {
                $question->set_answer( $choice[ "answer" ], $choice[ "response" ] );
            }
            $main = "            <li><h2>Klik op jouw antwoord!</h2></li>" . PHP_EOL;
        }
        else
        {
            $main = '            <li><h2>Je hebt een uitgang gevonden uit mijn doolhof aan vragen!</h2></li>' . PHP_EOL;
        }

        foreach ( $question->get_answers() as $answer )
        {
            $response = urlencode( $answer->get_response() );
            if ( empty( $response ) )
            {
                $main .= '            <li class="answer_button_off">' . $answer->text. '</li>' . PHP_EOL;
            }
            else
            {
                $main .= '            <a href="?title=' . $response . '"><li class="answer_button">' . $answer->text . '</li></a>' . PHP_EOL;
            }
        }
        $main .= '            <a href="mailto:oracle@leerparadijs.nl?SUBJECT='. urlencode( $title ) . '&BODY=Beste%20Oracle,%0DGraag%20het%20volgende%20antwoord%20toevoegen:"><li class="answer_button_add">Voeg jouw antwoord toe!</li></a>' . PHP_EOL;
    }
}
echo '    <header>' . PHP_EOL;
echo '        <img src="image/socrates.svg" alt="Socrates">' . PHP_EOL;
echo '        <h1>' . $title . '</h1>' . PHP_EOL;
echo '    </header>' . PHP_EOL;
echo '    <main>' . PHP_EOL;
echo '        <ul>' . PHP_EOL;
echo              $main;
echo '        </ul>' . PHP_EOL;
echo '    </main>' . PHP_EOL;

?>
</body>
</html>
<?php

class Question
{
    private $text;
    private $answers = array();
    public function __construct( $text )
    {
        $this->text = $text;
    }

    public function set_answer( $text, $response )
    {
        $this->answers[] = new Answer( $text, $response );
    }

    public function get_answers()
    {
        return $this->answers;
    }

    public function get_text()
    {
        return $this->text;
    }

}

class Answer
{
   public $text;
   private $response;

   public function __construct( $text, $response )
   {
       $this->text = $text;
       $this->response = new Response( $response );
   }

   public function get_response()
   {
       return $this->response->get_weighted_random();
   }
}

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
