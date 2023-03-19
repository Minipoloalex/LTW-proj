DROP TABLE IF EXISTS HASHTAG_TICKET;
DROP TABLE IF EXISTS HASHTAG;
DROP TABLE IF EXISTS ACTION;
DROP TABLE IF EXISTS MESSAGE; /* tem que ser antes de ticket e de client */
DROP TABLE IF EXISTS DEPARTMENT; /* tem que ser antes de admin e agent, que pertencem a um departamento*/
DROP TABLE IF EXISTS ADMIN;
DROP TABLE IF EXISTS AGENT;
DROP TABLE IF EXISTS CLIENT;
DROP TABLE IF EXISTS TICKET;
DROP TABLE IF EXISTS FORUM;


/*******************************************************************************
   Create Tables
********************************************************************************/


CREATE TABLE CLIENT (
    UserID INTEGER PRIMARY KEY,
    Name VARCHAR(120) NOT NULL,
    Username VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL CHECK (LEN(Password) > 5), /* segurança da password */
    Email VARCHAR(60) NOT NULL UNIQUE,
    CONSTRAINT "PK_Client" PRIMARY KEY (UserID)
);


CREATE TABLE AGENT(
    UserID INTEGER,
    DepartmentID INTEGER,
    CONSTRAINT "PK_Agent" PRIMARY KEY (UserID)
    FOREIGN KEY (UserID) REFERENCES CLIENT(UserID),
    FOREIGN KEY(DepartmentID) REFERENCES DEPARTMENT(DepartmentID)
);

CREATE TABLE ADMIN(
    UserID INTEGER,
    CONSTRAINT "PK_Admin" PRIMARY KEY(UserID),
    FOREIGN KEY (UserID) REFERENCES AGENT(UserID)
);

/* admin <- agent <- client */

CREATE TABLE TICKET (
    TicketID INTEGER,
    UserID INTEGER,
    Status VARCHAR(255) NOT NULL DEFAULT 'open',
    SubmitDate INTEGER NOT NULL, 
    Priority VARCHAR(255),
    Hashtag VARCHAR(255),
    AssignedAgent INTEGER,
    DepartmentID VARCHAR(255),  /* can be null */
    
    CONSTRAINT "PK_Ticket" PRIMARY KEY(TicketID),
    FOREIGN KEY (UserID) REFERENCES CLIENT(UserID),
    FOREIGN KEY (AssignedAgent) REFERENCES AGENT(UserID),
    FOREIGN KEY (DepartmentID) REFERENCES DEPARTMENT(DepartmentID),
    
    /*List all changes done to a ticket (e.g., status changes, assignments, edits).*/
);

CREATE TABLE DEPARTMENT (
    DepartmentID INTEGER,
    DepartmentName VARCHAR(255) NOT NULL,
    PRIMARY KEY (DepartmentID)
);

CREATE TABLE HASHTAG (
    HashtagID INTEGER PRIMARY KEY,
    HashtagName VARCHAR(50)
);

CREATE TABLE HASHTAG_TICKET(    /* many to many */
    TicketID INTEGER PRIMARY KEY,
    HashtagID INTEGER PRIMARY KEY,
    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID),
    FOREIGN KEY (HashtagID) REFERENCES HASHTAG(HashtagID)
);

CREATE TABLE ACTION (
    ActionID INTEGER PRIMARY KEY,
    TicketID INTEGER,
    UserID INTEGER,
    Type VARCHAR(255), /*open, process, close, transfer*/
    TimeStamp INTEGER NOT NULL,

    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID),
    FOREIGN KEY (UserID) REFERENCES Client(UserID)
);

CREATE TABLE MESSAGE (
    MessageID INTEGER PRIMARY KEY,
    TicketID INTEGER,
    UserID INTEGER,
    MessageText TEXT,

    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID),
    FOREIGN KEY (UserID) REFERENCES Client(UserID)
);


CREATE TABLE FORUM (
    ForumId INTEGER PRIMARY KEY,
    Question TEXT,
    Answer TEXT,
);
