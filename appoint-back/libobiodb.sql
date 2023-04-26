use libobio;
create table customer (
    id INT(6) unsigned auto_increment primary key,
    c_name varchar(30),
    e_name varchar(60),
    email varchar(60),
    birthday datetime,
    p_id varchar(10),
    passport varchar(10),
    phone varchar(20),
    address varchar(200),
    reg_date timestamp default current_timestamp on update current_timestamp
)