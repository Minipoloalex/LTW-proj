<?php
declare(strict_types=1);
require_once(__DIR__ . '/department.class.php');
require_once(__DIR__ . '/hashtag.class.php');
require_once(__DIR__ . '/agent.class.php');
require_once(__DIR__ . '/client.class.php');
class Ticket implements JsonSerializable
{
  public int $ticketid;
  public string $title;
  public string $username;
  public string $status;
  public int $submitdate; // $date = date('F j', $article['published']);
  public ?string $priority;
  public array $hashtags;
  public string $description;
  public ?string $assignedagent;
  public ?string $departmentName;

  public function __construct(int $ticketid, string $title, string $username, string $status, int $submitdate, ?string $priority, array $hashtags, string $description, ?string $assignedagent, ?string $departmentName)
  {
    $this->ticketid = $ticketid;
    $this->title = $title;
    $this->username = $username;
    $this->status = $status;
    $this->submitdate = $submitdate;
    $this->priority = $priority;
    $this->hashtags = $hashtags;
    $this->description = $description;
    $this->departmentName = $departmentName;
    $this->assignedagent = $assignedagent;
  }

  public function jsonSerialize(){
    return [
      'ticketid' => $this->ticketid,
      'title' => $this->title,
      'username' => $this->username,
      'status' => $this->status,
      'submitdate' => $this->submitdate,
      'priority' => $this->priority,
      'hashtags' => $this->hashtags,
      'description' => $this->description,
      'assignedagent' => $this->assignedagent,
      'departmentName' => $this->departmentName
    ];
  }  
  static function getById(PDO $db, int $id): ?Ticket {
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE TicketID = ?');
    $stmt->execute(array($id));
    $ticket = $stmt->fetch();
    if (!$ticket) {
      return null;
    }
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
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE AssignedAgent = ?');
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
    $department = $ticket['DepartmentID'] != NULL ? Department::getById($db, intval($ticket['DepartmentID'])) : NULL;
    $agent = $ticket['AssignedAgent'] != NULL ? Agent::getById($db, intval($ticket['AssignedAgent'])) : NULL;
    $id = intval($ticket['TicketID']);
    $hashtags = Hashtag::getByTicketId($db, $id);
    return new Ticket(
      $id,
      $ticket['Title'],
      $client->username,
      $ticket['Status'],
      intval($ticket['SubmitDate']),
      $ticket['Priority'],
      $hashtags,
      $ticket['Description'],
      $agent != NULL ? $agent->username : NULL,
      $department != NULL ? $department->departmentName: NULL,
    );
  }
  static function existsTicket(PDO $db, string $title, int $userID): ?int {
    /* Same user can't create tickets with the same name */
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE Title = ? AND UserID = ?');
    $stmt->execute(array($title, $userID));
    $ticket = $stmt->fetch();
    return intval($ticket['TicketID']) ?? NULL;
  }

  static function createTicket(PDO $db, string $title, int $userID, ?string $priority, array $hashtags, string $description, ?int $departmentID) : int {
    $submitdate = idate('U');
    $status = "open";
    
    $stmt = $db->prepare('INSERT INTO TICKET(TicketID, Title, UserID, Status, SubmitDate, Priority, Description, AssignedAgent, DepartmentID)
    VALUES (NULL, ?, ?, ?, ?, ?, ?, NULL, ?)');  /* assignedAgent is always null */
    $stmt->execute(array($title, $userID, $status, $submitdate, $priority, $description, $departmentID));
    $ticketID = intval($db->lastInsertId());

    foreach ($hashtags as $hashtagID) {
      Hashtag::addHashtagToTicket($db, $ticketID, $hashtagID);
    }

    return $ticketID;
  }
  function updateTicket(PDO $db, ?int $departmentID, ?int $agentID, string $priority, array $hashtags) {
    // TODO: test this
    if ($this->departmentName !== $departmentID || $this->assignedagent !== $agentID || $this->priority !== $priority) {
      $stmt = $db->prepare('UPDATE TICKET SET DepartmentID = ?, AssignedAgent = ?, Priority = ? WHERE TicketID = ?');
      $stmt->execute(array($departmentID, $agentID, $priority, $this->ticketid));
    }
    $hashtags_to_add = array_diff($hashtags, $this->hashtags);
    $hashtags_to_remove = array_diff($this->hashtags, $hashtags);
    var_dump($this->hashtags);
    var_dump($hashtags);
    var_dump($hashtags_to_add);
    var_dump($hashtags_to_remove);

    foreach ($hashtags_to_add as $hashtagID) {
      Hashtag::addHashtagToTicket($db, $this->ticketid, $hashtagID);
    }
    foreach ($hashtags_to_remove as $hashtagID) {
      Hashtag::removeHashtagFromTicket($db, $this->ticketid, $hashtagID);
    }
  }

  // adicionar filtros por data
  static function filter(PDO $db, array $status, array $priorities, array $hashtags, array $agents, array $departments): array
  {
  
    $query = 'SELECT T.TicketID, T.Title, T.UserID, T.Status, T.SubmitDate, T.Priority, H.HashtagID, T.Description, T.AssignedAgent, T.DepartmentID FROM TICKET T JOIN HASHTAG_TICKET H USING(TicketID)';
    $statusF = '';
    $prioritiesF = '';
    $hashtagsF = '';
    $agentsF = '';
    $departmentsF = '';
    
    if(!empty($status)){
      for ($i = 0; $i<count($status); $i++) {
        if ($i == 0) {$statusF = $statusF.sprintf('(T.Status = %s', $status[$i]);} 
        else {$statusF = $statusF.sprintf(' or T.Status = %s', $status[$i]);}
      }
      $statusF = $statusF.')';
  }
  
  if(!empty($priorities)){
    if ($statusF != '') {$prioritiesF = ' and ';}
    for ($i = 0; $i<count($priorities); $i++) {
      if ($i == 0) {$prioritiesF = $prioritiesF.sprintf('(T.Priority = %s', $priorities[$i]);} 
      else {$prioritiesF = $prioritiesF.sprintf(' or T.Priority = %s', $priorities[$i]);}
    }
    $prioritiesF = $prioritiesF.')';
}

//conversão para IDs
if(!empty($hashtags)){
  if ($statusF != '' || $prioritiesF != '') {$hashtagsF = ' and ';}
  for ($i = 0; $i<count($hashtags); $i++) {
    if ($i == 0) {$hashtagsF = $hashtagsF.sprintf('(H.HashtagID = %s', $hashtags[$i]);} 
    else {$hashtagsF = $hashtagsF.sprintf(' or H.HashtagID = %s', $hashtags[$i]);}
  }
  $hashtagsF = $hashtagsF.')';
}

//conversão para IDs
if(!empty($agents)){
  if ($statusF != '' || $prioritiesF != '' || $hashtagsF != '') {$agentsF = ' and ';}
  for ($i = 0; $i<count($agents); $i++) {
    if ($i == 0) {$agentsF = $agentsF.sprintf('(T.AssignedAgent = %s', $agents[$i]);} 
    else {$agentsF = $agentsF.sprintf(' or T.AssignedAgent = %s', $agents[$i]);}
  }
  $agentsF = $agentsF.')';
}
//conversão para IDs
if(!empty($departments)){
  if ($statusF != '' || $prioritiesF != '' || $hashtagsF != '' || $agentsF != '') {$departmentsF = ' and ';}
  for ($i = 0; $i<count($departments); $i++) {
    if ($i == 0) {$departmentsF = $departmentsF.sprintf('(T.DepartmentID = %s', $departments[$i]);} 
    else {$departmentsF = $departmentsF.sprintf(' or T.DepartmentID= %s', $departments[$i]);}
  }
  $departmentsF = $departmentsF.')';
}   
    $stmt = $db->prepare($query.' WHERE '.$statusF.$prioritiesF.$hashtagsF.$agentsF.$departmentsF.';');
    $stmt->execute();

    $tickets = [];

    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }

  static function getFilters(PDO $db): array {
    $filters = [];
    $status = ["'open'", "'in progress'", "'closed'"];
    $priorities = ['high', 'medium', 'low'];
    $hashtags = [];
    $agents = [];
    $departments = [];

    $filters[]=$status;
    $filters[]=$priorities;

    $stmt = $db->prepare('SELECT * from HASHTAG;');
    $stmt->execute();
    while ($ht = $stmt->fetch()) {
      $hashtags[] = $ht;
    }
    $filters[] = $hashtags;

    $stmt = $db->prepare('SELECT UserID, Username FROM AGENT JOIN CLIENT USING(UserID);');
    $stmt->execute();
    while ($ag = $stmt->fetch()) {
      $agents[] = $ag;
    }
    $filters[] = $agents;

    $stmt = $db->prepare('SELECT * FROM DEPARTMENT;');
    $stmt->execute();
    while ($dp = $stmt->fetch()) {
      $departments[] = $dp;
    }
    $filters[] = $departments;


    return $filters;
  }
}
?>
