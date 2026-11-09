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