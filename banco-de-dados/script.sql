CREATE TABLE contatos (
	idcontato int not null primary key auto_increment,
	nome varchar(50) not null,
	sobrenome varchar(50) not null,
	endereco varchar(100) not null,
	cep varchar (9) not null,
	bairro varchar(50) not null,
	cidade varchar(50) not null,
	dt_criacao datetime not null,
	dt_alteracao datetime not null
);

CREATE TABLE telefones (
	idtelefone int not null primary key auto_increment,
	numero varchar(11) not null,
	tipo varchar(11) not null,
	contato_id int not null,
	foreign key (contato_id) references contatos(idcontato) on delete cascade
);

CREATE TABLE emails (
	idemail int not null primary key auto_increment,
	email varchar(50) not null,
	contato_id int not null,
	foreign key (contato_id) references contatos(idcontato) on delete cascade
);

CREATE TABLE empresas (
	idempresa int not null primary key auto_increment,
	nome varchar(50) not null,
	telefone varchar(11) not null
);

CREATE TABLE contato_empresa (
	contato_id int not null,
	empresa_id int not null,
	foreign key (contato_id) references contatos(idcontato) on delete cascade,
	foreign key (empresa_id) references empresas(idempresa) on delete cascade
);