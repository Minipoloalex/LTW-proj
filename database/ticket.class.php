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
  public ?string $priority;
  public array $hashtags;
  public string $description;
  public ?string $assignedagent;
  public ?string $departmentName;

  public function __construct(int $ticketid, string $title, string $username, string $status, int $submitdate, ?string $priority, array $hashtags, string $description, ?string $departmentName, ?string $assignedagent)
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
  static function existsTicket(PDO $db, string $title, int $userID): bool {
    /* Same user can't create tickets with the same name */
    /* Not tested yet */
    
    $stmt = $db->prepare('SELECT * FROM TICKET WHERE Title = ? AND UserID = ?');
    $stmt->execute(array($title, $userID));
    $ticket = $stmt->fetch();
    return $ticket;
  }

  static function createTicket(PDO $db, string $title, string $username, ?string $priority, array $hashtags, string $description, ?string $departmentName) : int {
    /* Not tested yet */
    /* Need to add hashtags to other table (not done yet) */
    $submitdate = idate('U');
    var_dump($submitdate);
    
    $status = "open";

    $stmt = $db->prepare('INSERT INTO TICKET VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, NULL)');  /* assignedAgent is null */
    $stmt->execute(array($title, $username, $status, $submitdate, $priority, $description, $departmentName));
    return intval($db->lastInsertId());
  }

  // adicionar filtros por data
  static function filter(PDO $db, array $status, array $priorities, array $hashtags, array $agents, array $departments): array
  {
    var_dump($departments);
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
    var_dump($departments[$i]);
    if ($i == 0) {$departmentsF = $departmentsF.sprintf('(T.DepartmentID = %s', $departments[$i]);} 
    else {$departmentsF = $departmentsF.sprintf(' or T.DepartmentID= %s', $departments[$i]);}
  }
  $departmentsF = $departmentsF.')';
}    
    echo $departmentsF;
    echo $query.' WHERE '.$statusF.$prioritiesF.$hashtagsF.$agentsF.$departmentsF.';';
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
    $status = ['open', 'in progress', 'closed'];
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
    var_dump($filters);


    return $filters;
  }
}
?>
