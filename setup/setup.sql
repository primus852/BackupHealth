CREATE TABLE app_projects
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE  NULL
);
ALTER TABLE app_projects COMMENT = 'Main Table for projects';

CREATE TABLE projects_mysql
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  hostname VARCHAR(200) NOT NULL,
  db VARCHAR(200) NOT NULL,
  username VARCHAR(100) NOT NULL,
  pass VARCHAR(200),
  description VARCHAR(200),
  port INT DEFAULT 3306 NULL,
  project_id INT,
  CONSTRAINT projects_mysql_app_projects_id_fk FOREIGN KEY (project_id) REFERENCES app_projects (id)
);

CREATE TABLE projects_ping
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  url VARCHAR(200) NOT NULL,
  description VARCHAR(200),
  port INT DEFAULT 80,
  project_id INT,
  CONSTRAINT projects_ping_app_projects_id_fk FOREIGN KEY (project_id) REFERENCES app_projects (id)
);

CREATE TABLE projects_sftp
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  url VARCHAR(200) NOT NULL,
  port INT NOT NULL,
  type ENUM('sftp', 'ftp') NOT NULL,
  description VARCHAR(200),
  project_id INT,
  CONSTRAINT projects_sftp_app_projects_id_fk FOREIGN KEY (project_id) REFERENCES app_projects (id)
);