<?php
declare(strict_types=1);
require_once(__DIR__ . '/department.class.php');
require_once(__DIR__ . '/hashtag.class.php');
require_once(__DIR__ . '/agent.class.php');
require_once(__DIR__ . '/client.class.php');
class Ticket
{
  public int $ticketid;
  public string $title;
  public string $username;
  public string $status;
  public int $submitdate; // $date = date('F j', $article['published']);
  public string $priority;
  public array $hashtags;
  public string $description;
  public string $assignedagent;
  public string $departmentName;

  public function __construct(int $ticketid,string $title, string $username, string $status, int $submitdate, string $priority, array $hashtags, string $description, string $assignedagent, string $departmentName)
  {
    $this->ticketid = $ticketid;
    $this->title = $title;
    $this->username = $username;
    $this->status = $status;
    $this->submitdate = $submitdate;
    $this->priority = $priority;
    $this->hashtags = $hashtags;
    $this->description = $description;
    $this->assignedagent = $assignedagent;
    $this->departmentName = $departmentName;
  }
  static function getById(PDO $db, int $id): Ticket {
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE TicketID = ?');
    $stmt->execute(array($id));
    $ticket = $stmt->fetch();
    return Ticket::convertToTicket($db, $ticket);
  }
  static function getByUser(PDO $db, int $userId): array
  {
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE UserID = ?');
    $stmt->execute(array($userId));
    $tickets = [];
    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }
  static function getByAgent(PDO $db, int $agentId): array
  {
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE AgentID = ?');
    $stmt->execute(array($agentId));
    $tickets = [];
    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }
  static function getByDepartment(PDO $db, int $departmentId): array
  {
    $tickets = [];
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE DepartmentID = ?');
    $stmt->execute(array($departmentId));
    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }

  static function getTickets(PDO $db): array
  {
    $stmt = $db->prepare('SELECT * FROM TICKET');
    $stmt->execute();

    $tickets = [];

    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }

  private static function convertToTicket(PDO $db, array $ticket) {
    $client = Client::getById($db, intval($ticket['UserID']));
    $department = Department::getById($db, intval($ticket['DepartmentID']));
    $agent = Agent::getById($db, intval($ticket['AssignedAgent']));
    $id = intval($ticket['TicketID']);
    $hashtags = Hashtag::getByTicketId($db, $id);
    return new Ticket(
      $id,
      $ticket['Title'],
      $client->username,
      $ticket['Status'],
      $ticket['SubmitDate'],
      $ticket['Priority'],
      $hashtags,
      $ticket['Description'],
      $agent->username,
      $department->departmentName
    );
  }

  static function filter(PDO $db, array $status, array $priorities, array $hashtags, array $agents, array $departments): array
  {
    $query = 'SELECT * FROM TICKET JOIN HASHTAG_TICKET USING(TicketID)';
    $statusF = '';
    $prioritiesF = '';
    $hashtagsF = '';
    $agentsF = '';
    $departmentsF = '';
    
    if(!empty($status)){
      for ($i = 0; $i<count($status); $i++) {
        if ($i == 0) {$statusF = $statusF + sprintf('(Status = %s', $status[$i]);} 
        else {$statusF = $statusF + sprintf(' or Status = %s', $status[$i]);}
      }
      $statusF = $statusF + ')';
  }
  
  if(!empty($priorities)){
    for ($i = 0; $i<count($priorities); $i++) {
      if ($i == 0) {$prioritiesF = $prioritiesF + sprintf('(Priority = %s', $priorities[$i]);} 
      else {$prioritiesF = $prioritiesF + sprintf(' or Priority = %s', $priorities[$i]);}
    }
    $prioritiesF = $prioritiesF + ')';
}
//conversão para IDs
if(!empty($agents)){
  for ($i = 0; $i<count($agents); $i++) {
    if ($i == 0) {$agentsF = $agentsF + sprintf('(AssignedAgent = %s', $agents[$i]);} 
    else {$agentsF = $agentsF + sprintf(' or AssignedAgent = %s', $agents[$i]);}
  }
  $agentsF = $agentsF + ')';
}
//conversão para IDs
if(!empty($departments)){
  for ($i = 0; $i<count($departments); $i++) {
    if ($i == 0) {$departmentsF = $departmentsF + sprintf('(DepartmentID = %s', $departments[$i]);} 
    else {$departmentsF = $departmentsF + sprintf(' or Depa tmentID= %s', $departments[$i]);}
  }
  $departmentsF = $departmentsF + ')';
}

//verificar
if(!empty($hashtags)){
  for ($i = 0; $i<count($hashtags); $i++) {
    if ($i == 0) {$hashtagsF = $hashtagsF + sprintf('(Hashtags = %s', $hashtags[$i]);} 
    else {$hashtagsF = $hashtagsF + sprintf(' or Hashtags = %s', $hashtags[$i]);}
  }
  $hashtagsF = $hashtagsF + ')';
}   
    
    
//criar a query    $stmt = $db->prepare('SELECT * FROM TICKET');
    $stmt->execute();

    $tickets = [];

    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }
}
?>
