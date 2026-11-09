
CREATE DATABASE IF NOT EXISTS db_pokebier;
USE db_pokebier;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS Atks
(
  id_atk    INT         NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(50) NOT null ,
  dam       INT         NOT NULL DEFAULT 0,
  id_effect INT         NULL    ,
  PRIMARY KEY (id_atk),
  CONSTRAINT UQ_Atks_nome UNIQUE (nome),
  CONSTRAINT FK_Efeitos_TO_Atks
    FOREIGN KEY (id_effect)
    REFERENCES Efeitos (id_effect)
);

CREATE TABLE IF NOT EXISTS E_P
(
  id_equipe INT NOT NULL,
  id_p_u    INT NOT NULL,
  CONSTRAINT FK_P_U_TO_E_P
    FOREIGN KEY (id_p_u)
    REFERENCES P_U (id_p_u),
  CONSTRAINT FK_Equipe_TO_E_P
    FOREIGN KEY (id_equipe)
    REFERENCES Equipe (id_equipe)
);

CREATE TABLE IF NOT EXISTS Efeitos
(
  id_effect INT         NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(50) NOT null ,
  PRIMARY KEY (id_effect),
  CONSTRAINT UQ_Efeitos_nome UNIQUE (nome)
);

CREATE TABLE IF NOT EXISTS Equipe
(
  id_equipe  INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  PRIMARY KEY (id_equipe),
  CONSTRAINT FK_Usuario_TO_Equipe
    FOREIGN KEY (id_usuario)
    REFERENCES Usuario (id_usuario)
);

CREATE TABLE IF NOT EXISTS Itens_cura
(
  id_it_cura       INT         NOT NULL AUTO_INCREMENT,
  nome             VARCHAR(50) NOT null ,
  qnt_cura_hp      INT         NOT NULL DEFAULT 0,
  cura_virus       BOOLEAN     NOT NULL DEFAULT false,
  cura_metal       BOOLEAN     NOT NULL DEFAULT false,
  cura_planta      BOOLEAN     NOT NULL DEFAULT false,
  cura_omega       BOOLEAN     NOT NULL DEFAULT false,
  cura_hacker      BOOLEAN     NOT NULL DEFAULT false,
  cura_admin       BOOLEAN     NOT NULL DEFAULT false,
  cura_lutador     BOOLEAN     NOT NULL DEFAULT false,
  cura_ilusionista BOOLEAN     NOT NULL DEFAULT false,
  cura_fogo        BOOLEAN     NOT NULL DEFAULT false,
  cura_meme        BOOLEAN     NOT NULL DEFAULT false,
  cura_bizarro     BOOLEAN     NOT NULL DEFAULT false,
  cura_propaganda  BOOLEAN     NOT NULL DEFAULT false,
  cura_cartoon     BOOLEAN     NOT NULL DEFAULT false,
  cura_saudavel    BOOLEAN     NOT NULL DEFAULT false,
  cura_leader      BOOLEAN     NOT NULL DEFAULT false,
  cura_evil        BOOLEAN     NOT NULL DEFAULT false,
  cura_peak        BOOLEAN     NOT NULL DEFAULT false,
  cura_D           BOOLEAN     NOT NULL DEFAULT false,
  revive           BOOLEAN     NOT NULL DEFAULT false,
  PRIMARY KEY (id_it_cura),
  CONSTRAINT UQ_Itens_cura_nome UNIQUE (nome)
);

CREATE TABLE IF NOT EXISTS Itens_evos
(
  id_it_evo INT         NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(50) NOT null ,
  id_pb     INT         NOT NULL,
  PRIMARY KEY (id_it_evo),
  CONSTRAINT UQ_Itens_evos_nome UNIQUE (nome),
  CONSTRAINT FK_Pokebiers_TO_Itens_evos
    FOREIGN KEY (id_pb)
    REFERENCES Pokebiers (id_pb)
);

CREATE TABLE IF NOT EXISTS Its_Mochi
(
  id_mochi   INT NOT NULL,
  id_it_evo  INT ,
  id_it_cura INT ,
  CONSTRAINT FK_Mochila_TO_Its_Mochi
    FOREIGN KEY (id_mochi)
    REFERENCES Mochila (id_mochi),
  CONSTRAINT FK_Itens_evos_TO_Its_Mochi
    FOREIGN KEY (id_it_evo)
    REFERENCES Itens_evos (id_it_evo),
  CONSTRAINT FK_Itens_cura_TO_Its_Mochi
    FOREIGN KEY (id_it_cura)
    REFERENCES Itens_cura (id_it_cura)
);

CREATE TABLE IF NOT EXISTS Mochila
(
  id_mochi INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id_mochi)
);

CREATE TABLE IF NOT EXISTS P_U
(
  id_p_u     INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  id_pb      INT NOT NULL,
  PRIMARY KEY (id_p_u),
  CONSTRAINT FK_Usuario_TO_P_U
    FOREIGN KEY (id_usuario)
    REFERENCES Usuario (id_usuario),
  CONSTRAINT FK_Pokebiers_TO_P_U
    FOREIGN KEY (id_pb)
    REFERENCES Pokebiers (id_pb)
);

CREATE TABLE IF NOT EXISTS Pokebiers
(
  id_pb      INT          NOT NULL AUTO_INCREMENT,
  nome       VARCHAR(50)  NOT null ,
  hp         INT          NOT NULL DEFAULT 1,
  velocidade INT          NOT NULL DEFAULT 1,
  decs       VARCHAR(150) ,
  id_tipo    INT          NOT NULL,
  id_subtipo INT ,
  id_atk1    INT ,
  id_atk2    INT ,
  id_atk3    INT ,
  id_atk4    INT ,
  id_atk_s   INT ,
  PRIMARY KEY (id_pb),
  CONSTRAINT UQ_Pokebiers_nome UNIQUE (nome),
  CONSTRAINT FK_Tipos_TO_Pokebiers
    FOREIGN KEY (id_tipo)
    REFERENCES Tipos (id_tipo),
  CONSTRAINT FK_Tipos_TO_Pokebiers1
    FOREIGN KEY (id_subtipo)
    REFERENCES Tipos (id_tipo),
  CONSTRAINT FK_Atks_TO_Pokebiers
    FOREIGN KEY (id_atk1)
    REFERENCES Atks (id_atk),
  CONSTRAINT FK_Atks_TO_Pokebiers1
    FOREIGN KEY (id_atk2)
    REFERENCES Atks (id_atk),
  CONSTRAINT FK_Atks_TO_Pokebiers2
    FOREIGN KEY (id_atk3)
    REFERENCES Atks (id_atk),
  CONSTRAINT FK_Atks_TO_Pokebiers3
    FOREIGN KEY (id_atk4)
    REFERENCES Atks (id_atk),
  CONSTRAINT FK_Atks_TO_Pokebiers4
    FOREIGN KEY (id_atk_s)
    REFERENCES Atks (id_atk)
);

CREATE TABLE IF NOT EXISTS Tipos
(
  id_tipo INT         NOT NULL AUTO_INCREMENT,
  nome    VARCHAR(25) NOT NULL,
  forca   INT         NOT NULL,
  fraq    INT         NOT NULL,
  PRIMARY KEY (id_tipo),
  CONSTRAINT UQ_Tipos_nome UNIQUE (nome),
  CONSTRAINT FK_Tipos_TO_Tipos
    FOREIGN KEY (forca)
    REFERENCES Tipos (id_tipo),
  CONSTRAINT FK_Tipos_TO_Tipos1
    FOREIGN KEY (fraq)
    REFERENCES Tipos (id_tipo)
);

CREATE TABLE IF NOT EXISTS Usuario
(
  id_usuario INT         NOT NULL AUTO_INCREMENT,
  apelido    VARCHAR(25) NOT NULL,
  senha      VARCHAR(30) NOT NULL,
  dinheiro   INT         NOT NULL DEFAULT 0,
  id_mochi   INT         NOT NULL,
  PRIMARY KEY (id_usuario),
  CONSTRAINT UQ_Usuario_apelido UNIQUE (apelido),
  CONSTRAINT FK_Mochila_TO_Usuario
    FOREIGN KEY (id_mochi)
    REFERENCES Mochila (id_mochi)
);

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
