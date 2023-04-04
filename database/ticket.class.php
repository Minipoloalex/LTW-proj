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

  static function getByUser(PDO $db, string $user): array
  {
    $tickets = [];
    return $tickets;
  }
  static function getByAgent(PDO $db, string $agent): array
  {
    $tickets = [];
    return $tickets;
  }
  static function getByDepartment(PDO $db, $department): array
  {
    $tickets = [];
    $stmt = $db->prepare('SELECT * FROM TICKET');
    $stmt->execute();

    return $tickets;
  }

  static function getTickets(PDO $db): array
  {
    $stmt = $db->prepare('SELECT * FROM TICKET');
    $stmt->execute();

    $tickets = [];

    while ($ticket = $stmt->fetch()) {
      $client = Client::getById($db, intval($ticket['UserID']));
      $department = Department::getById($db, intval($ticket['DepartmentID']));
      $agent = Agent::getById($db, intval($ticket['AssignedAgent']));
      $id = intval($ticket['TicketID']);
      $hashtags = Hashtag::getByTicketId($db, $id);
      var_dump($agent->username);
      $tickets[] = new Ticket(
  //       public int $ticketid;
  // public string $title;
  // public string $username;
  // public string $status;
  // public int $submitdate; // $date = date('F j', $article['published']);
  // public string $priority;
  // public array $hashtags;
  // public string $description;
  // public string $assignedagent;
  // public string $departmentName;
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
    return $tickets;
  }

}
?>
