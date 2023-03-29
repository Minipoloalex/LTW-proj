<?php

    declare(strict_types = 1);

    class Client {
        public int $id;
        public string $name;
        public string $username;
        public string $password;
        public string $email;
        public function __construct(int $id, string $name, string $username, string $password, string $email)
        {
            $this->id = $id;
            $this->name = $name;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
        }
     
        function save($db) {
            $stmt = $db->prepare('
              UPDATE Client SET Name = ?
              WHERE UserId = ?
            ');
      
            $stmt->execute(array($this->Name, $this->id));
          }

        static function getClientWithPassword(PDO $db, string $email, string $password) : ?Client {
            $stmt = $db->prepare('
              SELECT *
              FROM Client
              WHERE lower(email) = ? AND password = ?
            ');
      
            $stmt->execute(array(strtolower($email), sha1($password)));
        
            if ($client = $stmt->fetch()) {
              return new Client(
                intval($client['UserID']),
                $client['Name'],
                $client['Username'],
                $client['password'],
                $client['email']
              );
            } else return null;
          }
        

        static function searchClients(PDO $db, string $search, int $count) : array {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM Client WHERE Name LIKE ? LIMIT ?');
            $stmt->execute(array($search . '%', $count));
        
            $clients = array();
            while ($client = $stmt->fetch()) {
              $clients[] = new Client(
                intval($client['UserID']),
                    $client['Name'],
                    $client['Username'],
                    $client['password'],
                    $client['email']
              );
            }
        
            return $clients;
          }
        
        

        static function getClients(PDO $db, int $count)  : array {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM Client LIMIT ? ');
            $stmt->execute(array($count));

            $clients = array();
            while($client = $stmt->fetch()){
                $clients[] = new Client(
                    intval($client['UserID']),
                    $client['Name'],
                    $client['Username'],
                    $client['password'],
                    $client['email']
                );
            }
            return $clients;
        }


        static function getById(PDO $db, int $id) : Client {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM Client WHERE UserID = ?');
            $stmt->execute(array($id));
        
            $client = $stmt->fetch();
        
            return new Client(
                intval($client['UserID']),
                $client['Name'],
                $client['Username'],
                $client['password'],
                $client['email']
            );
          }



    }
