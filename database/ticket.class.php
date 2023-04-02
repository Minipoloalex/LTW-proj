<?php
declare(strict_types = 1);
require_once(__DIR__ . '');
// TicketID INTEGER,
//     UserID INTEGER,
//     Status VARCHAR(255) NOT NULL DEFAULT 'open',
//     SubmitDate INTEGER NOT NULL, 
//     Priority VARCHAR(255),
//     Hashtag VARCHAR(255),
//     AssignedAgent INTEGER,
//     DepartmentID INTEGER, 

class Ticket {
    public string $username;
    public string $status;
    public int $submitdate; // $date = date('F j', $article['published']);
    public string $priority;
    public string $hashtag;
    public string $assignedagent;
    public string $departmentName;

    public function __construct(string $username, string $status, int $submitdate, string $priority, string $hashtag, string $assignedagent, string $departmentName) {
        $this->username = $username;
        $this->status = $status; 
        $this->submitdate = $submitdate; 
        $this->priority = $priority; 
        $this->hashtag = $hashtag;
        $this->$assignedagent = $assignedagent;
        $this->departmentName = $departmentName;
      }
  
      public function getByUser(PDO $db, string $user) : array  {
        $tickets = [];
        return $tickets;
      }
      public function getByAgent(PDO $db, string $agent) : array {
        $tickets = [];
        return $tickets;
      }
      public function getByDepartment(PDO $db, $department) : array {
        $tickets = [];
        $stmt = $db->prepare('SELECT * FROM TICKET');
        $stmt->execute();

        return $tickets;
      }

      public function getTickets(PDO $db): array {
        $stmt = $db->prepare('SELECT * FROM TICKET');
        $stmt->execute();
    
        $tickets = [];
        
        while ($ticket = $stmt->fetch()) {
            $client = Client::getById($db, $ticket['userid']);
            $department = Department::getById($db, $ticket['departmentId']);
          $tickets[] = new Ticket(
            $client->username,
            $ticket->status,
            $ticket['submitdate'],
            $ticket['priority'],
            $ticket['hashtag'], // TODO
            $ticket['assignedAgent'], // TODO
            $ticket['departmentName'] // TODO
          );
        }
  
        return $tickets;
      }

}
?>
