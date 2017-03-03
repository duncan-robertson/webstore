use web;

create table users (
username varchar(15) primary key,
email varchar(320) unique not null,
password varchar(15) not null,
first_name varchar(35) not null,
last_name varchar(35) not null,
phone char(10),
address varchar(255) not null,
address2 varchar(255),
city varchar(255) not null,
province char(2) not null,
postal varchar(6) not null,
locked tinyint(1) unsigned not null default '0',
remove tinyint(1) unsigned not null default '0'
);

create table admins (
username varchar(15) primary key,
password varchar(15) not null
);

create table catalog (
name varchar(60) primary key,
image_path varchar(255) not null,
description varchar(255) not null,
price float not null,
quantity int not null
);

create table product_reviews (
item varchar(60) not null,
user varchar(15) not null,
foreign key (item) references catalog(name),
foreign key (user) references users(username),
rating tinyint(1) not null,
review varchar(512),
date timestamp
);

create table site_reviews (
user varchar(60) not null,
foreign key (user) references users(username),
rating tinyint(1) not null,
review varchar(512),
date timestamp
);

create table purchases (
item varchar(60) not null,
user varchar(15) not null,
quantity int not null,
status tinyint unsigned default '0',
puchase_id int not null,
date timestamp,
foreign key (item) references catalog(name),
foreign key (user) references users(username)
);
