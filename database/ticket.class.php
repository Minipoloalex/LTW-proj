<?php
declare(strict_types=1);
require_once(__DIR__ . '/department.class.php');
require_once(__DIR__ . '/hashtag.class.php');
require_once(__DIR__ . '/agent.class.php');
require_once(__DIR__ . '/client.class.php');
require_once(__DIR__ . '/action.class.php');
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

    Action::addUserAction($db, $userID, $ticketID, 'create', $submitdate);
    return $ticketID;
  }
  function updateTicket(PDO $db, ?int $departmentID, ?int $agentID, string $priority, array $hashtags, int $userID): Action {
    $ticketHashtags = array_map(function($hashtag) {
      return $hashtag->hashtagid;
    }, $this->hashtags);
    $old_agent = $this->assignedagent;
    $hashtags_to_add = array_diff($hashtags, $ticketHashtags);
    $hashtags_to_remove = array_diff($ticketHashtags, $hashtags);

    if ($this->departmentName !== $departmentID || $this->assignedagent !== $agentID || $this->priority !== $priority) {
      $this->departmentName = $departmentID != NULL ? Department::getById($db, $departmentID)->departmentName : NULL;
      $this->assignedagent = $agentID != NULL ? Agent::getById($db, $agentID)->username : NULL;

      $this->priority = $priority;
      $this->status = $this->assignedagent !== NULL ? 'in progress' : 'open';
      $stmt = $db->prepare('UPDATE TICKET SET DepartmentID = ?, AssignedAgent = ?, Priority = ?, Status = ? WHERE TicketID = ?');
      $stmt->execute(array($departmentID, $agentID, $priority, $this->status, $this->ticketid));
    }
    
    foreach ($hashtags_to_add as $hashtagID) {
      Hashtag::addHashtagToTicket($db, $this->ticketid, $hashtagID);
    }
    foreach ($hashtags_to_remove as $hashtagID) {
      Hashtag::removeHashtagFromTicket($db, $this->ticketid, $hashtagID);
    }
    if (!empty($hashtags_to_add) || !empty($hashtags_to_remove)) {
      $this->hashtags = Hashtag::getByTicketId($db, $this->ticketid);
    }
    
    $new_agent = $this->assignedagent;

    if ($old_agent === $new_agent) {
      return Action::addEditAction($db, $userID, $this->ticketid, 'edit', time(), null);
    }
    if ($new_agent === NULL) {
      $old_agent_id = Agent::getByUsername($db, $old_agent)->id;
      return Action::addEditAction($db, $userID, $this->ticketid, 'unassign', time(), $old_agent_id);
    }
    return Action::addEditAction($db, $userID, $this->ticketid, 'assign', time(), $agentID);
  }

  static function reopenTicket(PDO $db, int $ticketID, int $userID): Action {
    Ticket::updateStatus($db, $ticketID, 'open');
    return Action::addUserAction($db, $userID, $ticketID, 'reopen', time());
  }
  static function closeTicket(PDO $db, int $ticketID, int $userID): Action {
    Ticket::updateStatus($db, $ticketID, 'closed');
    return Action::addUserAction($db, $userID, $ticketID, 'close', time());
  }
  static function updateStatus(PDO $db, int $ticketID, string $status) {
    $stmt = $db->prepare('UPDATE TICKET SET Status = ? WHERE TicketID = ?');
    $stmt->execute(array($status, $ticketID));
  }

  // adicionar filtros por data
  // static function filter(PDO $db, array $status, array $priorities, array $hashtags, array $agents, array $departments, int $page = 1): array
  static function filter(PDO $db, array $status = [], array $priorities = [], array $hashtags = [] , array $agents = [], array $departments = [], int $page = 1): array
  {
  
    $query = 'SELECT T.TicketID, T.Title, T.UserID, T.Status, T.SubmitDate, T.Priority, T.Description, T.AssignedAgent, T.DepartmentID FROM TICKET T LEFT JOIN HASHTAG_TICKET H USING(TicketID) WHERE TRUE';
    // $query = 'SELECT T.TicketID, T.Title, T.UserID, T.Status, T.SubmitDate, T.Priority, H.HashtagID, T.Description, T.AssignedAgent, T.DepartmentID FROM TICKET T LEFT JOIN HASHTAG_TICKET H USING(TicketID) WHERE TRUE';
    $statusF = '';
    $prioritiesF = '';
    $hashtagsF = '';
    $agentsF = '';
    $departmentsF = '';
    $params = array();
    
    if(!empty($status)){
      $statusF = ' and ';
      for ($i = 0; $i<count($status); $i++) {
        if ($i == 0) {
          $statusF = $statusF.sprintf('(T.Status = ?');
          $params[] = $status[$i];
        } 
        else {
          $statusF = $statusF.sprintf(' or T.Status = ?');
          $params[] = $status[$i];
        }
      }
      $statusF = $statusF.')';
      // for ($i = 0; $i<count($status); $i++) {
      //   if ($i == 0) {$statusF = $statusF.sprintf('(T.Status = %s', $status[$i]);} 
      //   else {$statusF = $statusF.sprintf(' or T.Status = %s', $status[$i]);}
      // }
      // $statusF = $statusF.')';
  }
  
  if(!empty($priorities)){
    $prioritiesF = ' and ';
    // if ($statusF != '') {$prioritiesF = ' and ';}
    for ($i = 0; $i<count($priorities); $i++) {
      if ($i == 0) {
        $prioritiesF = $prioritiesF.sprintf('(T.Priority = ?');
        $params[] = $priorities[$i];
      } 
      else {
        $prioritiesF = $prioritiesF.sprintf(' or T.Priority = ?');
        $params[] = $priorities[$i];
      }
    $prioritiesF = $prioritiesF.')';

    }
    // for ($i = 0; $i<count($priorities); $i++) {
    //   if ($i == 0) {$prioritiesF = $prioritiesF.sprintf('(T.Priority = %s', $priorities[$i]);} 
    //   else {$prioritiesF = $prioritiesF.sprintf(' or T.Priority = %s', $priorities[$i]);}
    // }
    // $prioritiesF = $prioritiesF.')';
}

if(!empty($hashtags)){
  $hashtagsF = ' and ';
  // if ($statusF != '' || $prioritiesF != '') {$hashtagsF = ' and ';}
  for ($i = 0; $i<count($hashtags); $i++) {
    if ($i == 0) {
      $hashtagsF = $hashtagsF.sprintf('(H.HashtagID = ?');
      $params[] = $hashtags[$i];
    } 
    else {
      $hashtagsF = $hashtagsF.sprintf(' or H.HashtagID = ?');
      $params[] = $hashtags[$i];
    }
  }
  $hashtagsF = $hashtagsF.')';
  // for ($i = 0; $i<count($hashtags); $i++) {
  //   if ($i == 0) {$hashtagsF = $hashtagsF.sprintf('(H.HashtagID = %s', $hashtags[$i]);} 
  //   else {$hashtagsF = $hashtagsF.sprintf(' or H.HashtagID = %s', $hashtags[$i]);}
  // }
  // $hashtagsF = $hashtagsF.')';
}

if(!empty($agents)){
  $agentsF = ' and ';
  // if ($statusF != '' || $prioritiesF != '' || $hashtagsF != '') {$agentsF = ' and ';}
  for ($i = 0; $i<count($agents); $i++) {
    if ($i == 0) {
      $agentsF = $agentsF.sprintf('(T.AssignedAgent = ?');
      $params = $agents[$i];
    } 
    else {
      $agentsF = $agentsF.sprintf(' or T.AssignedAgent = ?');
      $params = $agents[$i];
    }
  }
  $agentsF = $agentsF.')';
  // for ($i = 0; $i<count($agents); $i++) {
  //   if ($i == 0) {$agentsF = $agentsF.sprintf('(T.AssignedAgent = %s', $agents[$i]);} 
  //   else {$agentsF = $agentsF.sprintf(' or T.AssignedAgent = %s', $agents[$i]);}
  // }
  // $agentsF = $agentsF.')';
}

if(!empty($departments)){
  $departmentsF = ' and ';
  // if ($statusF != '' || $prioritiesF != '' || $hashtagsF != '' || $agentsF != '') {$departmentsF = ' and ';}
  for ($i = 0; $i<count($departments); $i++) {
    if ($i == 0) {
      $departmentsF = $departmentsF.sprintf('(T.DepartmentID = ?');
      $params[] = $departments[$i];
    } 
    else {
      $departmentsF = $departmentsF.sprintf(' or T.DepartmentID = ?');
      $params[] = $departments[$i];
    }
  }
  $departmentsF = $departmentsF.')';
  // for ($i = 0; $i<count($departments); $i++) {
  //   if ($i == 0) {$departmentsF = $departmentsF.sprintf('(T.DepartmentID = %s', $departments[$i]);} 
  //   else {$departmentsF = $departmentsF.sprintf(' or T.DepartmentID= %s', $departments[$i]);}
  // }
  // $departmentsF = $departmentsF.')';
}   
    // filters
    $query .= $statusF.$prioritiesF.$hashtagsF.$agentsF.$departmentsF;
    $query .= " LIMIT 10 OFFSET " . ($page - 1) * 10 . ";";
    $stmt = $db->prepare($query);
    // $stmt = $db->prepare($query.' WHERE '.$statusF.$prioritiesF.$hashtagsF.$agentsF.$departmentsF.';');
    $stmt->execute($params);

    $tickets = [];

    while ($ticket = $stmt->fetch()) {
      $tickets[] = Ticket::convertToTicket($db, $ticket);
    }
    return $tickets;
  }

  // TODO
  // null filters
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


    return $filters;
  }
  function isClosed() {
    return strtolower($this->status) === 'closed';
  }
}
?>
