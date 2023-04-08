/*******************************************************************************
   Populate Tables
********************************************************************************/

-- CLIENT
INSERT INTO CLIENT (UserID, Name, Username, Password, Email) VALUES
(1, 'John Doe', 'johndoe', 'password123', 'johndoe@example.com'),
(2, 'Jane Smith', 'janesmith', 'secret456', 'janesmith@example.com'),
(3, 'Bob Johnson', 'bobjohnson', 'letmein789', 'bobjohnson@example.com'),
(4, 'Alice Brown', 'alicebrown', 'qwertyuiop', 'alicebrown@example.com'),
(5, 'David Lee', 'davidlee', 'password123', 'davidlee@example.com');

-- DEPARTMENT
INSERT INTO DEPARTMENT (DepartmentID, DepartmentName) VALUES
(1, 'Sales'),
(2, 'Support'),
(3, 'Billing'),
(4, 'Technical');

-- AGENT
INSERT INTO AGENT (UserID, DepartmentID) VALUES
(2, 2),
(3, 1),
(4, 4),
(5, 2);

-- ADMIN
INSERT INTO ADMIN (UserID) VALUES
(3),
(4);

-- TICKET
INSERT INTO TICKET (Title, TicketID, UserID, Status, SubmitDate, Priority, Description, AssignedAgent, DepartmentID) VALUES
('titulooo1', 1, 1, 'open', 1647724861, 'high','fun description', 3, 1),
('titulooo2', 2,  2, 'closed', 1647667800, 'low','sad description', 4, 4),
('titulooo3', 3,  3, 'in progress', 1647732762, 'medium', 'happy description',2, 3),
('titulooo4', 4, 4, 'open', 1647714997, 'high','no description', 5, 2),
('titulooo5', 5, 5, 'in progress', 1647703354, 'high','what description', 2, 3);
-- HASHTAG
INSERT INTO HASHTAG (HashtagID, HashtagName) VALUES
(1, '#customer_issue'),
(2, '#bug_report'),
(3, '#billing_issue'),
(4, '#technical_issue');

-- HASHTAG_TICKET
INSERT INTO HASHTAG_TICKET (TicketID, HashtagID) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 4);

-- ACTION
INSERT INTO ACTION (ActionID, TicketID, UserID, Type, TimeStamp) VALUES
(1, 1, 1, 'open', 1647724861),
(2, 2, 2, 'close', 1647667800),
(3, 3, 3, 'process', 1647732762),
(4, 4, 5, 'open', 1647714997),
(5, 5, 2, 'process', 1647703354);

-- MESSAGE
INSERT INTO MESSAGE (MessageID, TicketID, UserID, MessageText) VALUES
(1, 1, 1, 'Hello, I need help with my order.'),
(2, 1, 2, 'Sure, what seems to be the problem?'),
(3, 1, 1, 'My order was supposed to arrive yesterday, but it still has not arrived.'),
(4, 1, 2, 'I am sorry to hear that. Let me check the status of your order.'),
(5, 1, 2, 'It seems that your order is delayed. I will contact the courier and get back to you.'),
(6, 1, 1, 'Thank you for your help.'),
(7, 1, 2, 'No problem, we are happy to help.');

-- FORUM
INSERT INTO FORUM (ForumID, Question, Answer) VALUES
(1, 'What is the best way to contact customer support?', 'You can reach our customer support team by phone or email.'),
(2, 'How do I reset my password?', 'You can reset your password by clicking on the "Forgot Password" link on the login page.');