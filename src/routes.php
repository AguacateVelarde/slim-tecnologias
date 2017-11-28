<?php

use Slim\Http\Request;
use Slim\Http\Response;

use Spatie\ArrayToXml\ArrayToXml;

  $app->get('/post/all', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM entradas");
     $sth->execute();
     $xml = new SimpleXMLElement('<xml/>');

     while($row=$sth->fetch()){
       $xmlout = $xml->addChild('entrada');
       $xmlout->addChild('id_user', utf8_decode( $row['id_user']) );
       $xmlout->addChild('title', utf8_decode( $row['title']) );
       $xmlout->addChild('body', utf8_decode( $row['body']) );
       $xmlout->addChild('id', utf8_decode( $row['id']) );
       $xmlout->addChild('date', utf8_decode( $row['date']) );

     }
     Header('Content-type: text/xml');
     Header('Access-Control-Allow-Origin: *');
    return ($xml->asXML());
 });

 $app->get('/post/[{id}]', function ($request, $response, $args) {
   $sth = $this->db->prepare("SELECT * FROM entradas WHERE id=:id");
   $sth->bindParam("id", $args['id']);
    $sth->execute();
    $xml = new SimpleXMLElement('<xml/>');

    while($row=$sth->fetch()){
      $xmlout = $xml->addChild('entrada');
      $xmlout->addChild('id_user', utf8_decode( $row['id_user']) );
      $xmlout->addChild('title', utf8_decode( $row['title']) );
      $xmlout->addChild('body', utf8_decode( $row['body']) );
      $xmlout->addChild('id', utf8_decode( $row['id']) );
      $xmlout->addChild('date', utf8_decode( $row['date']) );

    }
    Header('Content-type: text/xml');
   print($xml->asXML());
});


$app->get('/post/users/[{id}]', function ($request, $response, $args) {
  $sth = $this->db->prepare("SELECT * FROM entradas WHERE id_user=:id");
  $sth->bindParam("id", $args['id']);
   $sth->execute();
   $xml = new SimpleXMLElement('<xml/>');

   while($row=$sth->fetch()){
     $xmlout = $xml->addChild('entrada');
     $xmlout->addChild('id_user', utf8_decode( $row['id_user']) );
     $xmlout->addChild('title', utf8_decode( $row['title']) );
     $xmlout->addChild('body', utf8_decode( $row['body']) );
     $xmlout->addChild('id', utf8_decode( $row['id']) );
     $xmlout->addChild('date', utf8_decode( $row['date']) );

   }
   Header('Content-type: text/xml');
   Header('Access-Control-Allow-Origin: *');
  return($xml->asXML())
          ->withHeader('Access-Control-Allow-Origin', 'http://localhost')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


//Agregar entradas

$app->post('/post/add', function ($request, $response) {
       $input = $request->getParsedBody();
       echo $input['title'];
       $sql = "INSERT INTO entradas (id_user, title, body, id, date) VALUES ( :id_user, :title, :body, :id, :date)";
        $sth = $this->db->prepare($sql);
        $values = [ "id_user" => $input['id_user'], "title" => $input['title'], "body" => $input['body'], "id" => $input['id'], "date" => $input['date'] ];


       $sth->execute( $values );


       $id = $this->db->lastInsertId();
       return $this->response->withJson($id);
   });
