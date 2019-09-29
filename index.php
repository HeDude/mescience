<?
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
if
(
    empty( getenv ( 'PHP_LIBRARY_PATH' ) )
)
{
    exit;
}
define( "LIBRARY_PHP_PATH", getenv ( 'PHP_LIBRARY_PATH' ) . "/" );
require_once LIBRARY_PHP_PATH. "urlrequest.php";

$URLrequest = new URLrequest();
$url = $URLrequest->get();
if ( empty( $url ) )
{
    return false;
}
if ( !array_key_exists( "query", $url ) || !array_key_exists( "title", $url[ "query" ] ) )
{
    return false;
}
$title_url = $url[ "query" ][ "title" ];
$api = file_get_contents( "https://api.hedude.com/question/json/nl?title=" . $title_url );
$answers = json_decode
(
    utf8_encode
    (
        $api
    ),
    JSON_OBJECT_AS_ARRAY
);

$question = new Question( $answers[ "title" ][ "title" ] );
foreach( $answers[ "title" ][ "choice" ] as $choice )
{
    $question->set_answer( $choice[ "answer" ], $choice[ "response" ] );
}
$title = $question->get_text();

echo '    <header>' . PHP_EOL;
echo '        <img src="image/socrates.svg" alt="Socrates">' . PHP_EOL;
echo '        <h1>' . $title . '</h1>' . PHP_EOL;
echo '    </header>' . PHP_EOL;
echo '    <main>' . PHP_EOL;
echo '        <ul>' . PHP_EOL;

foreach ( $question->get_answers() as $answer )
{
    $response = urlencode( $answer->get_response() );
    if ( empty( $response ) )
    {
        echo '            <li class="answer_button_off">' . $answer->text;
    }
    else
    {
        echo '            <li class="answer_button"><a href="?title=' . $response . '">' . $answer->text . '</a>';
    }
    echo '</li>' . PHP_EOL;
}
echo '            <li class="answer_button_add"><a href="mailto:oracle@leerparadijs.nl?SUBJECT='. $title_url . '&BODY=Beste%20Oracle,%0DGraag%20het%20volgende%20antwoord%20toevoegen:">Voeg jouw antwoord toe!</a></li>' . PHP_EOL;
echo '        </ul>' . PHP_EOL;
echo '    </main>' . PHP_EOL;
?>
</body>
</html>
