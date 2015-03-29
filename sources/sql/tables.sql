CREATE TABLE Users (
  UserRecordID  bigint(10) unsigned   NOT NULL AUTO_INCREMENT,
  UserID        bigint(10) unsigned   DEFAULT NULL,
  Login         varchar(32)           NOT NULL,
  PasswordHash  varchar(60)           NOT NULL,
  FirstName     varchar(255)          NOT NULL,
  LastName      varchar(255)          NOT NULL,
  Email         varchar(255)          DEFAULT NULL,
  Phone         varchar(32)           DEFAULT NULL,
  Birthday      date                  DEFAULT NULL,
  TraceID       bigint(10) unsigned   NOT NULL,
  TraceOn       datetime              NOT NULL,
  PRIMARY KEY (UserRecordID),
  UNIQUE KEY uxLogin_TraceID (Login,TraceID),
  UNIQUE KEY uxUserID_TraceID (UserID,TraceID),
  KEY ixPasswordHash (PasswordHash),
  KEY ixUserID (UserID)
) ENGINE=InnoDB;

CREATE TABLE Files (
  FileID        bigint(10) unsigned   NOT NULL AUTO_INCREMENT,
  UserID        bigint(10) unsigned   NOT NULL,
  FileName      varchar(255)          NOT NULL,
  Size          bigint(10) unsigned   NOT NULL,
  ContentType   varchar(255)          DEFAULT NULL,
  UploadedOn    datetime              NOT NULL,
  DeletedOn     datetime              DEFAULT NULL,
  PRIMARY KEY (FileID),
  KEY ixUserID (UserID),
  KEY ixUploadedOn (UploadedOn),
  KEY ixDeletedOn (DeletedOn),
  CONSTRAINT fkFiles_Users FOREIGN KEY (UserID) REFERENCES Users (UserID)
) ENGINE=InnoDB;


# SET FOREIGN_KEY_CHECKS = 0;
# TRUNCATE `Files`;
# TRUNCATE `Users`;
# SET FOREIGN_KEY_CHECKS = 1;