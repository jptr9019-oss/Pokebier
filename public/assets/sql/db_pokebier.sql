
CREATE DATABASE IF NOT EXISTS db_pokebier;
USE db_pokebier;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS Atks;
DROP TABLE IF EXISTS Efeitos;
DROP TABLE IF EXISTS Equipe;
DROP TABLE IF EXISTS Itens;
DROP TABLE IF EXISTS Itens_cura;
DROP TABLE IF EXISTS Itens_evos;
DROP TABLE IF EXISTS Mochila;
DROP TABLE IF EXISTS Pokebiers;
DROP TABLE IF EXISTS Tipos;
DROP TABLE IF EXISTS Usuario;
drop table if exists p_u;
drop table if exists e_p;
drop table if exists its_mochi;

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;

CREATE TABLE Atks
(
  id_atk    INT         NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(50) NOT NULL DEFAULT ' ',
  dam       INT         NOT NULL DEFAULT 0,
  id_effect INT         NULL    ,
  PRIMARY KEY (id_atk)
);

ALTER TABLE Atks
  ADD CONSTRAINT UQ_Atks_nome UNIQUE (nome);

CREATE TABLE Efeitos
(
  id_effect INT         NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(50) NOT NULL DEFAULT ' ',
  PRIMARY KEY (id_effect)
);

ALTER TABLE Efeitos
  ADD CONSTRAINT UQ_Efeitos_nome UNIQUE (nome);

CREATE TABLE Equipe
(
  id_equipe INT NOT NULL AUTO_INCREMENT,
  id_pb     INT NOT NULL,
  id_pb_2   INT NOT NULL,
  id_pb_3   INT NOT NULL,
  id_pb_4   INT NOT NULL,
  id_pb_5   INT NOT NULL,
  id_pb_6   INT NOT NULL,
  PRIMARY KEY (id_equipe)
);

CREATE TABLE Itens
(
  id_itens   INT NOT NULL AUTO_INCREMENT,
  id_it_evo  INT NULL    ,
  id_it_cura INT NULL    ,
  PRIMARY KEY (id_itens)
);

CREATE TABLE Itens_cura
(
  id_it_cura       INT         NOT NULL AUTO_INCREMENT,
  nome             VARCHAR(50) NOT NULL DEFAULT ' ',
  qnt_cura_hp      INT         NOT NULL DEFAULT 0,
  cura_dor         BOOLEAN     NOT NULL DEFAULT false,
  cura_preso       BOOLEAN     NOT NULL DEFAULT false,
  cura_banido      BOOLEAN     NOT NULL DEFAULT false,
  cura_confuso     BOOLEAN     NOT NULL DEFAULT false,
  cura_fogo        BOOLEAN     NOT NULL DEFAULT false,
  cura_rindo       BOOLEAN     NOT NULL DEFAULT false,
  cura_atordoado   BOOLEAN     NOT NULL DEFAULT false,
  cura_chocado     BOOLEAN     NOT NULL DEFAULT false,
  cura_amedrontado BOOLEAN     NOT NULL DEFAULT false,
  revive           BOOLEAN     NOT NULL DEFAULT false,
  PRIMARY KEY (id_it_cura)
);

ALTER TABLE Itens_cura
  ADD CONSTRAINT UQ_Itens_cura_nome UNIQUE (nome);

CREATE TABLE Itens_evos
(
  id_it_evo INT         NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(50) NOT NULL DEFAULT ' ',
  id_pb     INT         NOT NULL,
  id_pb_evo INT         NOT NULL,
  PRIMARY KEY (id_it_evo)
);

ALTER TABLE Itens_evos
  ADD CONSTRAINT UQ_Itens_evos_nome UNIQUE (nome);

CREATE TABLE Mochila
(
  id_mochi   INT NOT NULL AUTO_INCREMENT,
  id_itens   INT NULL    ,
  id_itens_2 INT NULL    ,
  id_itens_3 INT NULL    ,
  id_itens_4 INT NULL    ,
  id_itens_5 INT NULL    ,
  PRIMARY KEY (id_mochi)
);

CREATE TABLE Pokebiers
(
  id_pb      INT          NOT NULL AUTO_INCREMENT,
  nome       VARCHAR(50)  NOT NULL DEFAULT ' ',
  hp         INT          NOT NULL DEFAULT 1,
  velocidade INT          NOT NULL DEFAULT 1,
  decs       VARCHAR(150) NULL    ,
  id_tipo    INT          NOT NULL,
  id_subtipo INT          NULL    ,
  id_atk1    INT          NULL    ,
  id_atk2    INT          NULL    ,
  id_atk3    INT          NULL    ,
  id_atk4    INT          NULL    ,
  id_atk_s   INT          NULL    ,
  endimagem  VARCHAR(100) NOT NULL,
  PRIMARY KEY (id_pb)
);

ALTER TABLE Pokebiers
  ADD CONSTRAINT UQ_Pokebiers_nome UNIQUE (nome);

CREATE TABLE Tipos
(
  id_tipo INT         NOT NULL AUTO_INCREMENT,
  nome    VARCHAR(25) NOT NULL,
  forca   INT         NOT NULL,
  fraq    INT         NOT NULL,
  PRIMARY KEY (id_tipo)
);

ALTER TABLE Tipos
  ADD CONSTRAINT UQ_Tipos_nome UNIQUE (nome);

CREATE TABLE Usuario
(
  id_usuario INT         NOT NULL AUTO_INCREMENT,
  apelido    VARCHAR(25) NOT NULL,
  senha      VARCHAR(30) NOT NULL,
  dinheiro   INT         NOT NULL DEFAULT 0,
  id_mochi   INT         NOT NULL,
  id_equipe  INT         NOT NULL,
  PRIMARY KEY (id_usuario)
);

ALTER TABLE Usuario
  ADD CONSTRAINT UQ_Usuario_apelido UNIQUE (apelido);

ALTER TABLE Usuario
  ADD CONSTRAINT FK_Mochila_TO_Usuario
    FOREIGN KEY (id_mochi)
    REFERENCES Mochila (id_mochi);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Tipos_TO_Pokebiers
    FOREIGN KEY (id_tipo)
    REFERENCES Tipos (id_tipo);

ALTER TABLE Tipos
  ADD CONSTRAINT FK_Tipos_TO_Tipos
    FOREIGN KEY (forca)
    REFERENCES Tipos (id_tipo);

ALTER TABLE Tipos
  ADD CONSTRAINT FK_Tipos_TO_Tipos1
    FOREIGN KEY (fraq)
    REFERENCES Tipos (id_tipo);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Tipos_TO_Pokebiers1
    FOREIGN KEY (id_subtipo)
    REFERENCES Tipos (id_tipo);

ALTER TABLE Atks
  ADD CONSTRAINT FK_Efeitos_TO_Atks
    FOREIGN KEY (id_effect)
    REFERENCES Efeitos (id_effect);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Atks_TO_Pokebiers
    FOREIGN KEY (id_atk1)
    REFERENCES Atks (id_atk);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Atks_TO_Pokebiers1
    FOREIGN KEY (id_atk2)
    REFERENCES Atks (id_atk);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Atks_TO_Pokebiers2
    FOREIGN KEY (id_atk3)
    REFERENCES Atks (id_atk);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Atks_TO_Pokebiers3
    FOREIGN KEY (id_atk4)
    REFERENCES Atks (id_atk);

ALTER TABLE Pokebiers
  ADD CONSTRAINT FK_Atks_TO_Pokebiers4
    FOREIGN KEY (id_atk_s)
    REFERENCES Atks (id_atk);

ALTER TABLE Itens
  ADD CONSTRAINT FK_Itens_evos_TO_Itens
    FOREIGN KEY (id_it_evo)
    REFERENCES Itens_evos (id_it_evo);

ALTER TABLE Itens
  ADD CONSTRAINT FK_Itens_cura_TO_Itens
    FOREIGN KEY (id_it_cura)
    REFERENCES Itens_cura (id_it_cura);

ALTER TABLE Itens_evos
  ADD CONSTRAINT FK_Pokebiers_TO_Itens_evos
    FOREIGN KEY (id_pb)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Mochila
  ADD CONSTRAINT FK_Itens_TO_Mochila
    FOREIGN KEY (id_itens)
    REFERENCES Itens (id_itens);

ALTER TABLE Mochila
  ADD CONSTRAINT FK_Itens_TO_Mochila1
    FOREIGN KEY (id_itens_2)
    REFERENCES Itens (id_itens);

ALTER TABLE Mochila
  ADD CONSTRAINT FK_Itens_TO_Mochila2
    FOREIGN KEY (id_itens_3)
    REFERENCES Itens (id_itens);

ALTER TABLE Mochila
  ADD CONSTRAINT FK_Itens_TO_Mochila3
    FOREIGN KEY (id_itens_4)
    REFERENCES Itens (id_itens);

ALTER TABLE Mochila
  ADD CONSTRAINT FK_Itens_TO_Mochila4
    FOREIGN KEY (id_itens_5)
    REFERENCES Itens (id_itens);

ALTER TABLE Usuario
  ADD CONSTRAINT FK_Equipe_TO_Usuario
    FOREIGN KEY (id_equipe)
    REFERENCES Equipe (id_equipe);

ALTER TABLE Equipe
  ADD CONSTRAINT FK_Pokebiers_TO_Equipe
    FOREIGN KEY (id_pb)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Equipe
  ADD CONSTRAINT FK_Pokebiers_TO_Equipe1
    FOREIGN KEY (id_pb_2)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Equipe
  ADD CONSTRAINT FK_Pokebiers_TO_Equipe2
    FOREIGN KEY (id_pb_3)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Equipe
  ADD CONSTRAINT FK_Pokebiers_TO_Equipe3
    FOREIGN KEY (id_pb_4)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Equipe
  ADD CONSTRAINT FK_Pokebiers_TO_Equipe4
    FOREIGN KEY (id_pb_5)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Equipe
  ADD CONSTRAINT FK_Pokebiers_TO_Equipe5
    FOREIGN KEY (id_pb_6)
    REFERENCES Pokebiers (id_pb);

ALTER TABLE Itens_evos
  ADD CONSTRAINT FK_Pokebiers_TO_Itens_evos1
    FOREIGN KEY (id_pb_evo)
    REFERENCES Pokebiers (id_pb);