use db_pokebier;

insert into efeitos (nome) values ('dor');
insert into efeitos (nome) values ('preso');
insert into efeitos (nome) values ('banido');
insert into efeitos (nome) values ('confuso');
insert into efeitos (nome) values ('fogo');
insert into efeitos (nome) values ('rindo');
insert into efeitos (nome) values ('atordoado');
insert into efeitos (nome) values ('chocado');
insert into efeitos (nome) values ('amedrontado');

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

insert into tipos (id_tipo, nome, forca, fraq) values (1, 'virus',6 ,5 );
insert into tipos (id_tipo,nome, forca, fraq) values (2, 'metal',11 ,16 );
insert into tipos (id_tipo, nome, forca, fraq) values (3, 'planta',13 ,9 );
insert into tipos (id_tipo, nome, forca, fraq) values (4, 'omega',18 ,11 );
insert into tipos (id_tipo, nome, forca, fraq) values (5, 'hacker',1 ,6 );
insert into tipos (id_tipo, nome, forca, fraq) values (6, 'admin',5 ,1 );
insert into tipos (id_tipo, nome, forca, fraq) values (7, 'lutador',14 ,8 );
insert into tipos (id_tipo, nome, forca, fraq) values (8, 'ilusionista',7 ,15 );
insert into tipos (id_tipo, nome, forca, fraq) values (9, 'fogo',3 ,17 );
insert into tipos (id_tipo, nome, forca, fraq) values (10, 'meme',12 ,13 );
insert into tipos (id_tipo, nome, forca, fraq) values (11, 'bizarro',4 ,2 );
insert into tipos (id_tipo, nome, forca, fraq) values (12, 'propaganda',17 ,10 );
insert into tipos (id_tipo, nome, forca, fraq) values (13, 'cartoon',10 ,3 );
insert into tipos (id_tipo, nome, forca, fraq) values (14, 'saudavel',16 ,7 );
insert into tipos (id_tipo, nome, forca, fraq) values (15, 'leader',8 ,18 );
insert into tipos (id_tipo, nome, forca, fraq) values (16, 'evil',2 ,14 );
insert into tipos (id_tipo, nome, forca, fraq) values (17, 'peak',9 ,12 );
insert into tipos (id_tipo, nome, forca, fraq) values (18, 'D',15 ,4 );

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;

insert into atks (id_atk, nome, dam) values (1, 'Email de Spam', 10);
insert into atks (id_atk, nome, dam) values (2, 'Pop-up', 15);
insert into atks (id_atk, nome, dam) values (3, 'Pétalas Douradas', 10);
insert into atks (id_atk, nome, dam) values (4, 'Dança do Musgo', 15);
insert into atks (id_atk, nome, dam) values (5, 'Dancinha Aleatória', 10);
insert into atks (id_atk, nome, dam) values (6, 'Ataque Bacanudo', 15);
insert into atks (id_atk, nome, dam) values (7, 'Bola de Fogo', 13);
insert into atks (id_atk, nome, dam) values (8, 'Sopro de Faíscas', 13);
insert into atks (id_atk, nome, dam) values (9, 'Mata-Cobra', 8);
insert into atks (id_atk, nome, dam) values (10, 'Golpe de Pá', 20);
insert into atks (id_atk, nome, dam) values (11, 'Tornado Animado', 15);
insert into atks (id_atk, nome, dam) values (12, 'Taco de Beisebol', 17);
insert into atks (id_atk, nome, dam) values (13, 'Interface Hacker', 15);
insert into atks (id_atk, nome, dam) values (14, 'Man-In-The-Middle', 18);
insert into atks (id_atk, nome, dam) values (15, 'Auge da Ficção', 17);
insert into atks (id_atk, nome, dam) values (16, 'Absolute Cinema', 20);
insert into atks (id_atk, nome, dam) values (17, 'Truque de Líder', 10);
insert into atks (id_atk, nome, dam) values (18, 'Soco', 15);

insert into atks (id_atk, nome, dam) values (19, 'Ataque Narigudo', 20);
insert into atks (id_atk, nome, dam) values (20, 'Mãos Telefônicas', 25);
insert into atks (id_atk, nome, dam) values (21, 'BIG SHOT', 30);

insert into atks (id_atk, nome, dam) values (22, 'Laser-OMEGA', 25);
insert into atks (id_atk, nome, dam) values (23, 'OMEGA-3', 27);
insert into atks (id_atk, nome, dam) values (24, 'Jarona Final', 35);

insert into atks (id_atk, nome, dam) values (25, 'Anúncio Inskippável', 26);
insert into atks (id_atk, nome, dam) values (26, 'Publicidade Chata', 30);
insert into atks (id_atk, nome, dam) values (27, 'Meme Assinatura', 37);

insert into atks (id_atk, nome, dam) values (28, 'Hipnopompia', 19);
insert into atks (id_atk, nome, dam) values (29, 'Devaneio', 25);
insert into atks (id_atk, nome, dam) values (30, 'Magia Ardente', 28);
insert into atks (id_atk, nome, dam) values (31, 'Golpes Consecutivos', 35);

insert into atks (id_atk, nome, dam) values (32, 'Refeição Completa', 25);
insert into atks (id_atk, nome, dam) values (33, 'Mercadão do Frangão', 27);
insert into atks (id_atk, nome, dam) values (34, 'Soco do Feijão', 30);

insert into atks (id_atk, nome, dam) values (35, 'Banimento', 20);
insert into atks (id_atk, nome, dam) values (36, 'Dancinha do ADM', 25);
insert into atks (id_atk, nome, dam) values (37, 'Última Pergunta', 38);

insert into atks (id_atk, nome, dam) values (38, 'Haki', 25);
insert into atks (id_atk, nome, dam) values (39, 'Corte com Haki', 27);
insert into atks (id_atk, nome, dam) values (40, 'Vontade dos D.', 40);

insert into atks (id_atk, nome, dam) values (41, 'Pose Bizarra', 20);
insert into atks (id_atk, nome, dam) values (42, 'Bizarrice Cotidiana', 22);

insert into atks (id_atk, nome, dam) values (43, 'Ataque DO MAU', 25);
insert into atks (id_atk, nome, dam) values (44, 'Chute DO MAU', 29);
insert into atks (id_atk, nome, dam) values (45, 'Truque de Líder só que DO MAU', 32);


insert into itens_cura (id_it_cura, nome, qnt_cura_hp) values (1, 'Shildinho', 25);
insert into itens_cura (id_it_cura, nome, qnt_cura_hp) values (2, 'Shild', 50);
insert into itens_cura (id_it_cura, nome, qnt_cura_hp) values (3, 'Shildão', 75);
insert into itens_cura (id_it_cura, nome, qnt_cura_hp) values (4, 'Super shild', 100);
insert into itens_cura (id_it_cura, nome, qnt_cura_hp, revive) values (5, 'Levantador', 15, 1);

insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (1, 'Spamton', 60, 8, 1, 1, 2, '../public/assets/imagens/spamton_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (2, 'Flowery', 70, 7, 3, 3, 4, '../public/assets/imagens/flowery_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (3, 'Barriguinha Mole', 60, 6, 10, 5, 6, '../public/assets/imagens/barriguinha_mole_default.gif');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (4, 'Dark Magician', 70, 7, 9, 7, 8, '../public/assets/imagens/dark_magician_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (5, 'Mesmer', 70, 8, 7, 9, 10, '../public/assets/imagens/mesmer_default.gif');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (6, 'Chiquinho Gavião', 80, 5, 13, 11, 12, '../public/assets/imagens/chiquinho_gaviao_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (7, 'Verity', 60, 7, 5, 13, 14, '../public/assets/imagens/verity_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (8, 'Inspetor Shepherd', 60, 6, 17, 15, 16, '../public/assets/imagens/inspetor_shepherd_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (9, 'Giorno Giovanna', 60, 7, 10, 5, 6, '../public/assets/imagens/giorno_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_atk1, id_atk2, endimagem) values (10, 'Eric', 65, 7, 15, 17, 18, '../public/assets/imagens/imagem_para_teste.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (11, 'Spamton NEO', 110, 10, 1, 2, 1, 2, 19, 20, 21, '../public/assets/imagens/spamton_neo_default.gif');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (12, 'OMEGA Flowery', 120, 9, 3, 4, 3, 4, 22, 23, 24, '../public/assets/imagens/omega_flowery_default.gif');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (13, 'Barriguinha Mole e Dollynho', 105, 20, 10, 12, 5, 6, 25, 26, 27, '../public/assets/imagens/barriguinha_mole_e_dollynho_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (14, 'Dark Magician of Chaos', 100, 12, 9, 8, 7, 8, 28, 29, 30, '../public/assets/imagens/dark_magician_of_chaos_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (15, 'Mesmer (Despertada)', 90, 21, 7, 8, 9, 10, 28, 29, 31, '../public/assets/imagens/mesmer_despertada_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (16, 'Chiquinho Gavião Satisfeito', 150, 6, 13, 14, 11, 12, 32, 33, 34, '../public/assets/imagens/chiquinho_gaviao_satisfeito_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (17, 'Lord Verity', 115, 11, 5, 6, 13, 14, 35, 36, 37, '../public/assets/imagens/lord_verity_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (18, 'Con D. Oriano', 120, 13, 17, 18, 15, 16, 38, 39, 40, '../public/assets/imagens/con_d_oriano_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (19, 'Giorno Giovanna (Requiem)', 135, 25, 10, 11, 5, 6, 41, 42, 27, '../public/assets/imagens/giorno_requiem_default.png');
insert into pokebiers (id_pb, nome, hp, velocidade, id_tipo, id_subtipo, id_atk1, id_atk2, id_atk3, id_atk4, id_atk_s, endimagem) values (20, 'Evil Eric', 120, 11, 15, 16, 17, 18, 43, 44, 45, '../public/assets/imagens/imagem_para_teste.png');

INSERT INTO Itens_evos (nome, id_pb, id_pb_evo)
VALUES
('DiscoCarregado', 1, 11),
('PoderDaAmizade', 2, 12),
('NúmeroDoDollynho', 3, 13),
('CartaMisteriosa', 4, 14),
('EspelhoQuebrado', 5, 15),
('PratoDeFeijão', 6, 16),
('ChapéuDescolado', 7, 17),
('EspadaManeira', 8, 18),
('FlechaBizarra', 9, 19),
('ArrozDoMau', 10, 20);

INSERT INTO Itens (id_it_evo, preco, imagem)
VALUES
(1, 25, '../public/assets/imagens/discocarregado.png'),
(2, 25, '../public/assets/imagens/poderdaamizade.png'),
(3, 25, '../public/assets/imagens/numerododollynho.png'),
(4, 25, '../public/assets/imagens/cartamisteriosa.png'),
(5, 25, '../public/assets/imagens/espelhoquebrado.png'),
(6, 25, '../public/assets/imagens/pratodefeijao.png'),
(7, 25, '../public/assets/imagens/chapeudescolado.png'),
(8, 25, '../public/assets/imagens/espadamaneira.png'),
(9, 25, '../public/assets/imagens/flechabizarra.png'),
(10, 25, '../public/assets/imagens/arrozdomau.png');

INSERT INTO Itens (id_it_cura, preco, imagem)
VALUES
(1, 1, '../public/assets/imagens/shildinho.png'),
(2, 3, '../public/assets/imagens/shild.png'),
(3, 5, '../public/assets/imagens/shildao.png'),
(4, 8, '../public/assets/imagens/supershild.png'),
(5, 10, '../public/assets/imagens/levantador.png');



-- Cria uma mochila vazia
INSERT INTO Mochila () VALUES ();

-- Guarda o ID da mochila recém-criada
SET @id_mochi = LAST_INSERT_ID();

-- Cria uma equipe com os seis Pokébiers padrão
INSERT INTO Equipe (id_pb, id_pb_2, id_pb_3, id_pb_4, id_pb_5, id_pb_6) VALUES (1, 2, 3, 4, 5, 6);

-- Guarda o ID da equipe recém-criada
SET @id_equipe = LAST_INSERT_ID();

-- Confere os IDs criados
SELECT @id_mochi, @id_equipe;


ALTER TABLE Usuario MODIFY senha VARCHAR(255) NOT NULL;


SELECT * FROM Usuario;

ALTER TABLE Itens
ADD COLUMN preco INT NOT NULL DEFAULT 0;

SELECT * FROM Itens_cura;

SELECT
    i.id_itens,
    c.id_it_cura,
    c.nome,
    i.preco
FROM Itens i
INNER JOIN Itens_cura c
    ON i.id_it_cura = c.id_it_cura;

describe Itens;

SELECT * FROM Itens;

DESCRIBE Itens;
DESCRIBE Itens_cura;
DESCRIBE Itens_evos;

DESCRIBE p_u;

UPDATE Usuario
SET dinheiro = dinheiro + 1000
WHERE id_usuario = 1;

UPDATE Usuario
SET dinheiro = dinheiro + 1500
WHERE id_usuario = 2;

SELECT id_usuario, apelido, dinheiro
FROM Usuario;

SELECT *
FROM p_u
WHERE id_usuario = 2;

SELECT
    i.id_itens,
    ie.id_it_evo,
    ie.nome,
    ie.id_pb,
    ie.id_pb_evo
FROM Itens i
INNER JOIN Itens_evos ie ON i.id_it_evo = ie.id_it_evo;