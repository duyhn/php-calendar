CREATE TABLE events (

  id int(11) NOT NULL AUTO_INCREMENT,

  start_date datetime not null,

  end_date datetime not NULL,

  name text COLLATE utf8mb4_unicode_ci ,

  status int,

  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

CREATE TABLE event_status(

  id int(11) NOT NULL AUTO_INCREMENT,
  name text,
  value int,
  color text,
  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

INSERT INTO event_status (name, value, color) values("PLANNING", 1, "#3b75d3");
INSERT INTO event_status (name, value, color) values("DOING", 2, "#3dd23a");
INSERT INTO event_status (name, value, color) values("COMPLETE", 3, "#d16138");
