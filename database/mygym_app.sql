DROP DATABASE mygym_app;

CREATE DATABASE mygym_app;

use mygym_app;

CREATE TABLE `utilizador` (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL DEFAULT '---',
  nomeUtilizador varchar(100) NOT NULL DEFAULT '---',
  email varchar(50) NOT NULL DEFAULT '---',
  contacto varchar(10) NOT NULL DEFAULT '---',
  pass varchar(100) NOT NULL DEFAULT '---',
  PRIMARY KEY (id)
);

INSERT INTO `utilizador` (`id`, `nome`, `nomeUtilizador`, `email`, `contacto`, `pass`) VALUES
(1, 'Cristiana', 'cris', 'cristiana-campos@hotmail.com', '99999888', '123');

CREATE TABLE `dadoscorporais` (
  id int(11) NOT NULL AUTO_INCREMENT,
  idUtilizador int(11) NOT NULL,
  peso double DEFAULT 0,
  altura double DEFAULT 0,
  massaMagra double DEFAULT 0,
  massaGorda double DEFAULT 0,
  massaHidrica double DEFAULT 0,
  PRIMARY KEY (id),
  FOREIGN KEY (idUtilizador) REFERENCES utilizador(id)
);

INSERT INTO `dadoscorporais` (`id`, `idUtilizador`, `peso`, `altura`, `massaMagra`, `massaGorda`, `massaHidrica`) VALUES
(1, 1, 56, 155, 45, 20, 50);

CREATE TABLE `aulagrupo` (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(50) NOT NULL DEFAULT '---',
  diaSemana varchar(20) NOT NULL DEFAULT '---',
  idUtilizador int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idUtilizador) REFERENCES utilizador(id)
);

INSERT INTO `aulagrupo` (`id`, `nome`, `diaSemana`, `idUtilizador`) VALUES
(1, 'Power Jump', 'Quinta-Feira', 1),
(2, 'Body Pump', 'Terça-Feira', 1),
(3, 'Zumba', 'Terça-Feira', 1),
(4, 'Combat', 'Domingo', 1);

CREATE TABLE `exercicio` (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL DEFAULT '---',
  zonaMuscular varchar(50) NOT NULL DEFAULT '---',
  idUtilizador int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idUtilizador) REFERENCES utilizador(id)
);

INSERT INTO `exercicio` (`id`, `nome`, `zonaMuscular`, `idUtilizador`) VALUES
(1, 'Agachamento', 'Perna', 1),
(2, 'Remada', 'Costas', 1),
(3, 'Shoulder Press', 'Ombro', 1);

CREATE TABLE `planotreino` (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL DEFAULT '---',
  diaSemana varchar(50) NOT NULL DEFAULT '---',
  idEx1 int(11) NOT NULL DEFAULT 0,
  idEx2 int(11) NOT NULL DEFAULT 0,
  idEx3 int(11) NOT NULL DEFAULT 0,
  idAula1 int(11) NOT NULL DEFAULT 0,
  idAula2 int(11) NOT NULL DEFAULT 0,
  idUtilizador int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idUtilizador) REFERENCES utilizador(id)
);

INSERT INTO `planotreino` (`id`, `nome`, `diaSemana`, `idEx1`, `idEx2`, `idEx3`, `idAula1`, `idAula2`, `idUtilizador`) VALUES
(1, 'Plano 1', 'Segunda-Feira', 2, 1, 0, 1, 0, 1),
(2, 'Plano 2', 'Sexta-Feira', 3, 2, 1, 2, 4, 1);