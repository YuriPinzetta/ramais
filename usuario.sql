use ramais;
create table usuario (id int(10) auto_increment primary key, login varchar(100), senha varchar(100),  perm_usuario int(10), perm_contato int(10)) engine=InnoDB;
