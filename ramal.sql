use ramais;
create table ramal (
	id_contato int(10) NOT NULL, 
	id int(10) NOT NULL, 
	tipo varchar(150), 
	ramal varchar(50),
	CONSTRAINT pk_id PRIMARY KEY (id_contato, id),
	CONSTRAINT fk_id_contato FOREIGN KEY (id_contato) REFERENCES contato (id)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
)
ENGINE=InnoDB;
