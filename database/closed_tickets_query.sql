.headers on
.nullvalue NULL
.mode column

-- INSERT INTO TICKET VALUES ( 98, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES ( 99, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (100, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (101, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (102, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (103, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (104, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (105, "title", 1, "closed", 1, "high", "description", 1, 1);
-- INSERT INTO TICKET VALUES (106, "title", 1, "closed", 1, "high", "description", 1, 1);

-- INSERT INTO ACTION VALUES( 998,  98, 1, "close", strftime('%s', 'now') - 1000);
-- INSERT INTO ACTION VALUES( 999,  99, 1, "close", strftime('%s', 'n0  ow') - 2000);
-- INSERT INTO ACTION VALUES(1000, 100, 1, "close", strftime('%s', 'now') - 86400);
-- INSERT INTO ACTION VALUES(1001, 101, 1, "close", strftime('%s', 'now') - 86400 * 2);
-- INSERT INTO ACTION VALUES(1002, 102, 1, "close", strftime('%s', 'now') - 86400 * 3);
-- INSERT INTO ACTION VALUES(1003, 103, 1, "close", strftime('%s', 'now') - 86400 * 4);
-- INSERT INTO ACTION VALUES(1004, 104, 1, "close", strftime('%s', 'now') - 86400 * 4);
-- INSERT INTO ACTION VALUES(1005, 105, 1, "close", strftime('%s', 'now') - 86400 * 5);
-- INSERT INTO ACTION VALUES(1006, 106, 1, "close", strftime('%s', 'now') - 86400 * 6);

SELECT COUNT(t.TicketID) as count, date_range.date as date
FROM (
        SELECT strftime('%Y-%m-%d', 'now', 'localtime', '-' || (n - 1) || ' days') AS date
        FROM (SELECT row_number() OVER () AS n FROM (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7))
        ORDER BY date DESC
    ) date_range
    LEFT JOIN(
    TICKET as t JOIN (
        SELECT A.TicketID, max(A.TimeStamp) AS actionDate
        FROM Action A
        GROUP BY TicketID
    ) as act ON t.TicketID = act.TicketID
) ON date_range.date = strftime('%Y-%m-%d', act.actionDate, 'unixepoch') and t.status = 'closed'
GROUP BY date_range.date
ORDER BY date_range.date ASC;
