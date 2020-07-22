DROP DATABASE IF EXISTS apps_database;
CREATE DATABASE apps_database;
USE apps_database;

CREATE TABLE customers (
   customer_emailaddress	VARCHAR( 50 ),
   customer_firstname	VARCHAR( 50 ),
   customer_lastname	VARCHAR( 50 ),
   customer_loginpassword	VARCHAR( 255 ),
   PRIMARY KEY ( customer_emailaddress )
);

CREATE TABLE print_job_types (
   print_job_type_id INT( 11 ) NOT NULL AUTO_INCREMENT,
   print_job_type_name VARCHAR( 20 ),
   print_job_type_class ENUM( 'CODEX', 'SHEET' ),
   PRIMARY KEY ( print_job_type_id )
);

CREATE TABLE print_jobs (
   print_job_id	INT( 11 ) NOT NULL AUTO_INCREMENT,
   customer_emailaddress	VARCHAR( 50 ),
   print_job_type_id INT( 11 ),
   print_job_name	VARCHAR( 100 ),
   print_job_registrationtime	DATETIME,
   print_job_percentdone	INT( 3 ),
   PRIMARY KEY ( print_job_id ),
   FOREIGN KEY ( customer_emailaddress ) REFERENCES customers( customer_emailaddress ),
   FOREIGN KEY ( print_job_type_id ) REFERENCES print_job_types( print_job_type_id )
);

CREATE TABLE subtasks (
   subtask_id	INT( 11 ) NOT NULL AUTO_INCREMENT,
   subtask_name	VARCHAR( 50 ),
   PRIMARY KEY ( subtask_id )
);

CREATE TABLE members_of_staff (
   member_of_staff_id	INT( 11 ) NOT NULL AUTO_INCREMENT,
   member_of_staff_firstname	VARCHAR( 50 ),
   member_of_staff_lastname	VARCHAR( 50 ),
   member_of_staff_isfrontdeskofficer BOOLEAN DEFAULT FALSE,
   member_of_staff_loginpassword	VARCHAR( 255 ),
   PRIMARY KEY ( member_of_staff_id )
);

CREATE TABLE scheduled_subtasks (
   print_job_id	INT( 11 ),
   subtask_id	INT( 11 ),
   member_of_staff_id	INT( 11 ),
   scheduled_subtask_serialnumber	INT( 11 ),
   scheduled_subtask_proposedstarttime	DATETIME,
   scheduled_subtask_proposedcompletiontime	DATETIME,
   scheduled_subtask_completionstatus	ENUM( 'Completed', 'In progress', 'Pending' ) DEFAULT 'Pending',
   PRIMARY KEY ( print_job_id, subtask_id ),
   FOREIGN KEY ( print_job_id ) REFERENCES print_jobs( print_job_id ),
   FOREIGN KEY ( subtask_id ) REFERENCES subtasks( subtask_id ),
   FOREIGN KEY ( member_of_staff_id ) REFERENCES members_of_staff( member_of_staff_id )
);

/* Database Initializations */
INSERT INTO subtasks ( subtask_id, subtask_name ) VALUES
   ( 1, "Typesetting" ),
   ( 2, "Designing" ),
   ( 3, "Filming" ),
   ( 4,  "Masking" ),
   ( 5,  "Burning" ),
   ( 6,  "Washing" ),
   ( 7,  "Printing" ),
   ( 8,  "Folding" ),
   ( 9,  "Binding" ),
   ( 10,  "Trimming" ),
   ( 11,  "Packaging" );

INSERT INTO print_job_types ( print_job_type_id, print_job_type_name, print_job_type_class ) VALUES
   ( 1, 'Book', 'CODEX' ),
   ( 2, 'Booklet', 'CODEX' ),
   ( 3, 'Brochure', 'CODEX' ),
   ( 4, 'Flyer', 'SHEET' ),
   ( 5, 'Poster', 'SHEET' ),
   ( 6, 'Complimentary Card', 'SHEET' ),
   ( 7, 'Wedding Card', 'SHEET' ),
   ( 8, 'Letter Head', 'SHEET' );

INSERT INTO members_of_staff ( member_of_staff_id, member_of_staff_firstname, member_of_staff_lastname, member_of_staff_loginpassword ) VALUES
   ( 1, "Ada", "Okoye", "$2y$10$629FkEHi0aUwQhv9XrjsYOsYjZrpIfYKCoRrhOyxUdViNluNJjEle" ),
   ( 2, "Prosper", "Adekunle", "$2y$10$6QKl7eMnB64fRGNMSL7gTOqrG9pxNgUKUh0/6VxQhtJ144vQcbfAW" ),
   ( 3, "Nelson", "Daniels", "$2y$10$K8qSpHv11Hat4SentmhOwukRhkC4GddbpAhDac3F.99WOH8ykFH6." );

INSERT INTO members_of_staff ( member_of_staff_id, member_of_staff_firstname, member_of_staff_lastname, member_of_staff_isfrontdeskofficer, member_of_staff_loginpassword ) VALUES
   ( 4, "Rosemary", "Ozioko", TRUE, "$2y$10$4qCXcSZW/ejEjwmf7R4v..jei9yw6oOv2z9Rw1XmLZI/ZJ8qr8LqO" );

INSERT INTO customers ( customer_emailaddress, customer_firstname, customer_lastname, customer_loginpassword ) VALUES
   ( "ejioforife@yahoo.com", "Ifechukwu", "Ejiofor", "$2y$10$rdB5ZAiSKY.ryBVw/THSOOcNzEhRyoI8suNjsIaEL2Eh5B0jGoGvK" ),
   ( "pnokeke@unn.edu", "P.N.", "Okeke", "$2y$10$G0E.91iIEoqDYrGwUDY70erf6p8DydhHO/VzE3LNHh4I6dpbceQJu" );

INSERT INTO print_jobs ( print_job_id, customer_emailaddress, print_job_type_id, print_job_name, print_job_registrationtime ) VALUES
   ( 5, "pnokeke@unn.edu", 1, "New School Physics for Senior Secondary Schools", '2018-04-24 08:00:00' ),
   ( 4, "ejioforife@yahoo.com", 1, "Introduction to Computer Science - 2nd Edition", "2018-04-23 08:00:00" );

INSERT INTO print_jobs ( print_job_id, customer_emailaddress, print_job_type_id, print_job_name, print_job_registrationtime, print_job_percentdone ) VALUES
   ( 3, "ejioforife@yahoo.com", 1, "Numerical Methods in Computer Science - Vol 1", "2018-04-22 08:00:00", 20 ),
   ( 2, "pnokeke@unn.edu", 2, "Practical Physics for Undergraduate Students", "2018-04-21 10:00:00", 44 ),
   ( 1, "ejioforife@yahoo.com", 3, "The Story of My Life", '2018-04-20 08:00:00', 100 );

INSERT INTO scheduled_subtasks ( print_job_id, subtask_id, member_of_staff_id, scheduled_subtask_serialnumber, scheduled_subtask_proposedstarttime, scheduled_subtask_proposedcompletiontime, scheduled_subtask_completionstatus ) VALUES
   ( 1, 1, 1, 1, "2018-05-21 08:00:00", "2018-05-22 09:00:00", "Completed" ),
   ( 1, 2, 2, 2, "2018-05-22 09:20:00", "2018-05-22 16:00:00", "Completed" ),
   ( 1, 3, 2, 3, "2018-05-23 08:00:00", "2018-05-24 12:00:00", "Completed" ),
   ( 1, 4, 1, 4, "2018-05-24 13:00:00", "2018-05-27 16:00:00", "Completed" ),
   ( 1, 5, 1, 5, "2018-05-28 08:00:00", "2018-05-28 10:00:00", "Completed" ),
   ( 1, 6, 1, 6, "2018-05-28 10:00:01", "2018-05-28 12:00:00", "Completed" ),
   ( 1, 7, 3, 7, "2018-05-28 12:30:00", "2018-05-28 16:00:00", "Completed" ),
   ( 1, 8, 3, 8, "2018-05-29 13:00:00", "2018-05-29 15:00:00", "Completed" ),
   ( 1, 10, 1, 9, "2018-05-30 09:00:00", "2018-05-30 12:00:00", "Completed" );

INSERT INTO scheduled_subtasks ( print_job_id, subtask_id, member_of_staff_id, scheduled_subtask_serialnumber, scheduled_subtask_proposedstarttime, scheduled_subtask_proposedcompletiontime, scheduled_subtask_completionstatus ) VALUES
   ( 2, 1, 2, 1, '2018-04-22 10:00:00', '2018-04-22 13:00:00', 'Completed' ),
   ( 2, 3, 2, 2, '2018-04-22 13:00:00', '2018-04-22 16:00:00', 'Completed' ),
   ( 2, 5, 1, 4, '2018-04-24 08:00:00', '2018-04-24 15:00:00', 'Completed' ),
   ( 2, 6, 1, 3, '2018-04-23 12:00:00', '2018-04-23 15:00:00', 'Completed' ),
   ( 2, 7, 3, 5, '2018-04-25 12:00:00', '2018-04-25 17:00:00', 'In progress' ),
   ( 2, 8, 1, 6, '2018-04-26 09:00:00', '2018-04-26 13:00:00', 'Pending' ),
   ( 2, 9, 2, 7, '2018-04-26 13:00:00', '2018-04-26 16:00:00', 'Pending' ),
   ( 2, 10, 3, 8, '2018-04-27 12:00:00', '2018-04-27 15:00:00', 'Pending' ),
   ( 2, 11, 1, 9, '2018-04-27 15:00:00', '2018-04-27 17:00:00', 'Pending' );

INSERT INTO scheduled_subtasks ( print_job_id, subtask_id, member_of_staff_id, scheduled_subtask_serialnumber, scheduled_subtask_proposedstarttime, scheduled_subtask_proposedcompletiontime, scheduled_subtask_completionstatus ) VALUES
   ( 3, 2, 2, 1, "2018-06-01 08:00:00", "2018-06-02 09:00:00", "Completed" ),
   ( 3, 1, 2, 2, "2018-06-02 09:20:00", "2018-06-02 16:00:00", "Completed" ),
   ( 3, 3, 1, 3, "2018-06-03 08:00:00", "2018-06-07 12:00:00", "In progress" ),
   ( 3, 4, 2, 4, "2018-06-07 13:00:00", "2018-06-07 16:00:00", "Pending" ),
   ( 3, 5, 1, 5, "2018-06-08 08:00:00", "2018-06-08 10:00:00", "Pending" ),
   ( 3, 6, 3, 6, "2018-06-08 10:00:01", "2018-06-08 12:00:00", "Pending" ),
   ( 3, 7, 3, 7, "2018-06-08 12:30:00", "2018-06-08 16:00:00", "Pending" ),
   ( 3, 8, 1, 8, "2018-06-09 13:00:00", "2018-06-09 15:00:00", "Pending" ),
   ( 3, 9, 2, 9, "2018-06-09 15:00:01", "2018-06-09 17:00:00", "Pending" ),
   ( 3, 10, 1, 10, "2018-06-10 09:00:00", "2018-06-10 12:00:00", "Pending" );
