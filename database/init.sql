CREATE TABLE CLIENT (
    UserID INTEGER,
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
    Status VARCHAR(255) NOT NULL DEFAULT 'open',
    SubmitDate INTEGER NOT NULL,
    Priority VARCHAR(255),
    Hashtag VARCHAR(255),
    AssignedAgent VARCHAR(255),
    DepartmentID VARCHAR(255),  /* can be null */
    
    CONSTRAINT "PK_Ticket" PRIMARY KEY(TicketID),
    FOREIGN KEY (AssignedAgent) REFERENCES AGENT(UserID),
    FOREIGN KEY (DepartmentID) REFERENCES DEPARTMENT(DepartmentID)
    /*List all changes done to a ticket (e.g., status changes, assignments, edits).*/
);

CREATE TABLE DEPARTMENT (
    DepartmentID INTEGER,
    DepartmentName VARCHAR(255) NOT NULL,
    PRIMARY KEY (DepartmentID)
);


CREATE TABLE STATUS (
    TicketID INTEGER,
    Type VARCHAR(255), /*open, process, close, transfer*/
    TimeStamp INTEGER NOT NULL,
    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID)
);


CREATE TABLE FORUM (
    Question TEXT
    Answer TEXT
);