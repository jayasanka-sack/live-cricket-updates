<?php
session_start();
require '../../vendor/autoload.php';
include "conf/connection.php";

use LiveCricket\Middleware\Authentication as LiveAuth;

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$app->add(new LiveAuth());


$app->get('/teams', function ($request, $response, $args) {
//    echo $request->getAttribute('type');

    global $con;
    $_teams = $con->query("SELECT id,name FROM team WHERE status=1");


    $teams = [];

    while ($team= $_teams->fetch_assoc()){
        $teams[] = $team;
    }



    return $response->withStatus(201)->withJson($teams);

});

// login

$app->post('/login', function ($request, $response, $args) {

    $username = $request->getParsedBodyParam('username', '');
    $password = $request->getParsedBodyParam('password', '');

    $payload = ['logged' => false];

    if ($username == "admin" && $password == "root") {
        setSession("admin", "1", "admin");
        $payload = ['logged' => true];
        return $response->withStatus(200)->withJson($payload);
    }

    return $response->withStatus(200)->withJson($payload);
});

//get current details

$app->get('/current-details', function ($request, $response, $args) {
    global $con;

    $_current_details = $con->query("SELECT team_id,inning FROM current_detail");

    $payload = [];

    if($_current_details->num_rows>0){
        $payload = $_current_details->fetch_assoc();
    }


    return $response->withStatus(200)->withJson($payload);
});

// add wicket

$app->post('/wickets', function ($request, $response, $args) {

    global $con;

    if ($request->getAttribute('type') != 'admin') {
        return $response->withStatus(403);
    }

    $batsmanPlayerId = $request->getParsedBodyParam('batsman_player_id', '');
    $bowlerPlayerId = $request->getParsedBodyParam('bowler_player_id', '');
    $inning = $request->getParsedBodyParam('inning', '');
    $dType = $request->getParsedBodyParam('d_type', '');

    $isExecuted = $con->query("INSERT INTO wicket(batsman_player_id, bowler_player_id, inning, d_type) VALUES ('$batsmanPlayerId', '$bowlerPlayerId', '$inning','$dType')");

    if ($isExecuted) {
        $payload = ['id' => $con->insert_id];
        return $response->withStatus(201)->withJson($payload);
    }
    return $response->withStatus(400);
});

// edit wicket

$app->post('/wickets/{wicket-id}', function ($request, $response, $args) {

    global $con;

    if ($request->getAttribute('type') != 'admin') {
        return $response->withStatus(403);
    }

    $wicketId = $args['wicket-id'];

    $batsmanPlayerId = $request->getParsedBodyParam('batsman_player_id', '');
    $bowlerPlayerId = $request->getParsedBodyParam('bowler_player_id', '');
    $inning = $request->getParsedBodyParam('inning', '');
    $dType = $request->getParsedBodyParam('d_type', '');

    $isExecuted = $con->query("UPDATE wicket SET batsman_player_id='$batsmanPlayerId', bowler_player_id = '$bowlerPlayerId', inning='$inning', d_type='$dType'");

    if ($isExecuted) {
        $payload = ['id' => $con->insert_id];
        return $response->withStatus(201)->withJson($payload);
    }
    return $response->withStatus(400);
});

//post current details

$app->post('/current-details', function ($request, $response, $args) {
    global $con;

    if($request->getAttribute("type") != "admin"){
        return $response->withStatus(403);
    }

    $teamId = $request->getParsedBodyParam('team_id', '');
    $inning = $request->getParsedBodyParam('inning', '');

    $_result = $con->query("SELECT * FROM current_detail WHERE id=1");

    if($_result->num_rows == 1){
        $con->query("INSERT INTO current_detail(team_id,inning) VALUES ('$teamId', '$inning')");
    }else{
        $con->query("INSERT INTO current_detail(id, team_id, inning ) VALUES ('1','$teamId', '$inning')");
        echo "INSERT INTO current_detail(id, team_id, inning ) VALUES ('1','$teamId', '$inning')";
    }

    return $response->withStatus(200)   ;
});

//extra team score

$app->post('/extra-team-score', function ($request, $response, $args) {
    global $con;

    if($request->getAttribute("type") != "admin"){
        return $response->withStatus(403);
    }

    $inning = $request->getParsedBodyParam('inning', '');
    $teamId = $request->getParsedBodyParam('team_id', '');
    $over = $request->getParsedBodyParam('over', '');
    $type = $request->getParsedBodyParam('type', '');
    $score = $request->getParsedBodyParam('score', '');

    $con->query("INSERT INTO extra_team_score(team_id, inning, over, type, score) VALUES ('$teamId', '$inning', '$over', '$type', '$score')");

    $id = $con->insert_id;

    return $response->withStatus(201)->withJson(["id"=>$id])   ;
});

//get players

$app->get('/teams/{team-id}', function ($request, $response, $args) {
    global $con;

    $_current_details = $con->query("SELECT team_id,inning FROM current_detail");

    $payload = [];

    if($_current_details->num_rows>0){
        $payload = $_current_details->fetch_assoc();
    }


    return $response->withStatus(200)->withJson($payload);
});

//extra team score

$app->post('/plain', function ($request, $response, $args) {

    global $con;

//    if ($request->getAttribute('type') != 'admin') {
//        return $response->withStatus(403);
//    }

    $a = $request->getParsedBodyParam('A', '');
    $b = $request->getParsedBodyParam('B', '');
    $c = $request->getParsedBodyParam('C', '');
    $d = $request->getParsedBodyParam('D', '');
    $e = $request->getParsedBodyParam('E', '');
    $f = $request->getParsedBodyParam('F', '');
    $g = $request->getParsedBodyParam('G', '');
    $h = $request->getParsedBodyParam('H', '');
    $i = $request->getParsedBodyParam('I', '');
    $j = $request->getParsedBodyParam('J', '');
    $k = $request->getParsedBodyParam('K', '');
    $l = $request->getParsedBodyParam('L', '');
    $m = $request->getParsedBodyParam('M', '');
    $n = $request->getParsedBodyParam('N', '');

    $isExecuted = $con->query("UPDATE plane SET a='$a',b='$b',c='$c',d='$d',e='$e',f='$f', g='$g',h='$h',i='$i',j='$j',k='$k',l='$l',m='$m',n='$n' WHERE id=1");

//    echo "UPDATE plane SET a='$a',b='$b',c='$c',d='$d',e='$e',f='$f', g='$g',h='$h',i='$i',j='$j',k='$k',l='$l',m='$m',n='$n' WHERE id=1";

    if ($isExecuted) {
        $payload = ['id' => 1];
        return $response->withStatus(201)->withJson($payload);
    }
    return $response->withStatus(400);
});

$app->get('/plain', function ($request, $response, $args) {

    global $con;

    $result = $con->query("SELECT * FROM plane");

    $payload = $result->fetch_assoc();

    return $response->withStatus(200)->withJson($payload);

});


try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
} catch (\Slim\Exception\NotFoundException $e) {
} catch (Exception $e) {
    echo 'error';
}


function setSession($type, $id, $displayName)
{
    $_SESSION['logged'] = true;
    $_SESSION['type'] = $type;
    $_SESSION['id'] = $id;
    $_SESSION['displayName'] = $displayName;
}

function addCurrentDetails($bowler, $striker, $nonStriker){
    global $con;
    $con->query("INSERT INTO current_detail(bowler_player_id, striker_player_id, batsman2_player_id) VALUES ('$bowler', '$striker', '$nonStriker')");
}