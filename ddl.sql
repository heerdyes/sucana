create table if not exists su_users (
  uid int not null auto_increment,
  uname varchar(50) not null,
  primary key (uid)
);

create table if not exists su_chatrooms (
  crid int not null auto_increment,
  name varchar(64) not null,
  description varchar(256),
  primary key (crid)
);

create table if not exists su_chatroomusers (
  cruid int not null auto_increment,
  roomid int,
  userid int,
  primary key (cruid),
  foreign key (roomid) references su_chatrooms(crid),
  foreign key (userid) references su_users(uid)
);

create table if not exists su_messages (
  mid int not null auto_increment,
  txt varchar(256) not null,
  mkrid int not null,
  born timestamp not null default current_timestamp,
  primary key (mid),
  foreign key (mkrid) references su_users(uid)
);

create table if not exists su_chatroomuserinbox (
  iid int not null auto_increment,
  cruser int,
  msgid int,
  isread int default 0,
  primary key (iid),
  foreign key (cruser) references su_chatroomusers(cruid),
  foreign key (msgid) references su_messages(mid)
);
