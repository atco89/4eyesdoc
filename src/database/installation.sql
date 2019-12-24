SET @start_time := now();

CREATE SCHEMA IF NOT EXISTS `4eyesdoc_rs` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `4eyesdoc_rs`;

SET FOREIGN_KEY_CHECKS = 0;
# =========== enumeration tables ===========
DROP TABLE IF EXISTS `enum_dial_numbers`;
DROP TABLE IF EXISTS `enum_examination_status`;
DROP TABLE IF EXISTS `enum_eye_diseases`;
DROP TABLE IF EXISTS `enum_groups`;
DROP TABLE IF EXISTS `enum_medical_examinations`;
DROP TABLE IF EXISTS `enum_professions`;
DROP TABLE IF EXISTS `enum_recommendation_type`;
DROP TABLE IF EXISTS `enum_roles`;
DROP TABLE IF EXISTS `enum_templates`;
DROP TABLE IF EXISTS `enum_web_recommendation`;
# =========== tables ===========
DROP TABLE IF EXISTS `tbl_appointments`;
DROP TABLE IF EXISTS `tbl_associates`;
DROP TABLE IF EXISTS `tbl_contact_persons`;
DROP TABLE IF EXISTS `tbl_examination_price`;
DROP TABLE IF EXISTS `tbl_examination_reports_icd`;
DROP TABLE IF EXISTS `tbl_examination_reports`;
DROP TABLE IF EXISTS `tbl_group_role`;
DROP TABLE IF EXISTS `tbl_patients`;
DROP TABLE IF EXISTS `tbl_recommendations`;
DROP TABLE IF EXISTS `tbl_user`;
DROP TABLE IF EXISTS `tbl_user_followers`;
DROP TABLE IF EXISTS `tbl_user_may_do_examinations`;
DROP TABLE IF EXISTS `tbl_user_work_schedule`;
SET FOREIGN_KEY_CHECKS = 1;

# ===============================================================
#   CREATING TABLES
# ===============================================================

CREATE TABLE `enum_dial_numbers`
(
    `id`               SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `dial_number`      VARCHAR(10)       NOT NULL,
    `alias`            VARCHAR(5)        NOT NULL,
    `is_mobile_number` BOOLEAN           NOT NULL,
    `active`           BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`dial_number`),
    UNIQUE KEY (`alias`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_dial_numbers` (`is_mobile_number`, `dial_number`, `alias`)
VALUES (FALSE, '+381', '000'),
       (TRUE, '+38160', '060'),
       (TRUE, '+38161', '061'),
       (TRUE, '+38162', '062'),
       (TRUE, '+38163', '063'),
       (TRUE, '+38164', '064'),
       (TRUE, '+38165', '065'),
       (TRUE, '+38166', '066'),
       (TRUE, '+38267', '067'),
       (TRUE, '+38168', '068'),
       (TRUE, '+38169', '069'),
       (FALSE, '+38124', '024'),
       (FALSE, '+38137', '037'),
       (FALSE, '+38118', '018'),
       (FALSE, '+38113', '013'),
       (FALSE, '+38125', '025'),
       (FALSE, '+38134', '034'),
       (FALSE, '+38131', '031'),
       (FALSE, '+38110', '010'),
       (FALSE, '+38121', '021'),
       (FALSE, '+38111', '011'),
       (FALSE, '+38127', '027'),
       (FALSE, '+38115', '015'),
       (FALSE, '+38116', '016'),
       (FALSE, '+38130', '030'),
       (FALSE, '+38117', '017'),
       (FALSE, '+38132', '032'),
       (FALSE, '+381230', '0230'),
       (FALSE, '+38135', '035'),
       (FALSE, '+381390', '0390'),
       (FALSE, '+381280', '0280'),
       (FALSE, '+38112', '012'),
       (FALSE, '+38122', '022'),
       (FALSE, '+38119', '019'),
       (FALSE, '+38128', '028'),
       (FALSE, '+38136', '036'),
       (FALSE, '+38114', '014'),
       (FALSE, '+38123', '023'),
       (FALSE, '+38133', '033'),
       (FALSE, '+38120', '020'),
       (FALSE, '+38139', '039'),
       (FALSE, '+38138', '038'),
       (FALSE, '+38129', '029'),
       (FALSE, '+38126', '026'),
       (FALSE, '+381290', '0290');

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_groups`
(
    `id`     TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`   VARCHAR(255)     NOT NULL,
    `active` BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_groups` (`name`)
VALUES ('Doktor'),
       ('Medicinski tehničar'),
       ('Menadžer'),
       ('Načelnik ordinacije'),
       ('Operater'),
       ('Profesor'),
       ('Tehnička podrška');

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_roles`
(
    `id`                  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                VARCHAR(255)     NOT NULL,
    `title`               VARCHAR(45)               DEFAULT NULL,
    `may_do_examinations` BOOLEAN          NOT NULL DEFAULT FALSE,
    `active`              BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_roles` (`name`, `title`, `may_do_examinations`)
VALUES ('Docent', 'Doc.dr.', TRUE),
       ('Profesor', 'Prof.dr.', TRUE),
       ('Klinički subspecijalista', 'dr.', TRUE),
       ('Specijalista oftamolog', 'dr.', TRUE),
       ('Doktor medicine na specijalizaciji', 'dr.', TRUE),
       ('Doktor medicine na edukaciji', NULL, TRUE),
       ('Medicinski tehničar', NULL, FALSE),
       ('Operater', NULL, FALSE),
       ('Menadžer', NULL, FALSE),
       ('Tehnička podrška', NULL, FALSE),
       ('Načelnik ordinacije', NULL, FALSE);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_group_role`
(
    `id`       TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `group_id` TINYINT UNSIGNED NOT NULL,
    `role_id`  TINYINT UNSIGNED NOT NULL,
    `active`   BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`group_id`, `role_id`),
    CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES `enum_groups` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`role_id`) REFERENCES `enum_roles` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `tbl_group_role` (`role_id`, `group_id`)
VALUES (1, 6),
       (2, 6),
       (3, 6),
       (4, 1),
       (5, 1),
       (6, 1),
       (7, 2),
       (8, 5),
       (9, 3),
       (10, 7),
       (11, 4);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_user`
(
    `id`             INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255)      NOT NULL,
    `surname`        VARCHAR(255)      NOT NULL,
    `group_role_id`  TINYINT UNSIGNED  NOT NULL,
    `username`       VARCHAR(255)      NOT NULL,
    `password`       CHAR(40)          NOT NULL,
    `user_color`     CHAR(7)           NOT NULL,
    `email`          VARCHAR(255)               DEFAULT NULL,
    `dial_number_id` SMALLINT UNSIGNED NOT NULL,
    `phone_number`   VARCHAR(10)       NOT NULL,
    `updated_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by`     INT UNSIGNED      NOT NULL,
    `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`     INT UNSIGNED      NOT NULL,
    `active`         BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`username`, `user_color`),
    UNIQUE KEY (`id`),
    CONSTRAINT FOREIGN KEY (`group_role_id`) REFERENCES `tbl_group_role` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`dial_number_id`) REFERENCES `enum_dial_numbers` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `tbl_user` (`name`, `surname`, `group_role_id`, `username`, `password`, `user_color`, `email`,
                        `dial_number_id`, `phone_number`, `created_by`, `updated_by`)
VALUES ('The', 'Admin', 1, 'admin', sha1('admin'), '#804000', 'contact@aleksandarrakic.com', 6, '0904606', 1, 1);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_user_followers`
(
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `follower_id`  INT UNSIGNED NOT NULL,
    `following_id` INT UNSIGNED NOT NULL,
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`   INT UNSIGNED NOT NULL,
    `active`       BOOLEAN      NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    CONSTRAINT FOREIGN KEY (`follower_id`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`following_id`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_medical_examinations`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL,
    `description` TEXT         NOT NULL,
    `active`      BOOLEAN      NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_medical_examinations` (`name`, `description`)
VALUES ('Kompletan oftalmološki pregled',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pregled prednjeg segmenta oka, pregled očnog dna'),
       ('Kompletan oftalmološki pregled kod univerzitetskog profesora i docenta',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pregled prednjeg segmenta oka, pregled očnog dna'),
       ('Pregled prednjeg segmenta oka',
        'Određivanje vidne oštrine i dioptrije, pregled na biomikroskopu, stanje očnog sočiva (katarakta i sl.)'),
       ('Prvi pregled prednjeg segmena oka za kontaktna sočiva',
        'Određivanje vidne oštrine i dioptrije, pregled na biomikroskopu, stanje očnog sočiva (katarakta i sl.), ispitivanje suznog filma'),
       ('Pregled prednjeg segmenta oka za kontaktna sočiva',
        'Određivanje vidne oštrine i dioptrije, pregled na biomikroskopu, stanje očnog sočiva (katarakta i sl.), ispitivanje suznog filma'),
       ('Merenje očnog pritiska', 'Merenje očnog pritiska (kontaktna metoda)'),
       ('Pregled očnog dna (fundus)', 'Na zahtev interniste, neurologa i sl.'),
       ('Određivanje dioptrije, bez drugih oftalmološkog pregleda',
        'Određivanje dioptrije, bez drugih oftalmološkog pregleda'),
       ('Oftalmološki pregled u sklopu sistematskog pregleda – uslužno',
        'Određivanje vidne oštrine i dioptrije, pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, Ishihara, stereo vid, motilitet'),
       ('Ultrawave analajzer – celokupni bezkontaktni pregled prednjeg segmenta oka',
        'Merenje očnog pritiska bezkontaktno, merenje debljine rožnjače uz korekcioni faktor za očni pritisak, merenje zakrivljenosti rožnjače, određivanje dioptrije (uslovi dana i noći), dubina prednje očne komore, širina komornog ugla, izgled očnog s...'),
       ('Kompletan oftalmološki pregled uključujući OCT makule ili očnog živca kod univerzitetskog profesora, docent ili kliničkog retinologa',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, optička koherentna tomografija žute mrlje ili očnog živca, Amsler ili Ishihara'),
       ('Kompletan oftalmološki pregled uključujući OCT makule ili očnog živca i foto fundus dokumentaciju očnog dna / univerzitetski profesor, docent, klinički retinolog',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pregled prednjeg segmenta oka, pahimetrija, stanje očnog sočiva, pregled očnog dna, autofluorescencija očnog dna, pregled retinalnih nervnih vlakana i krvnih sudova uz pomoć specijaln...'),
       ('Pregled za glaukom / univerzitetski profesor, docent, klinički glaukomatolog',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pahimetrija, pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, kompjuterizovano vidno polje, pahimetrija, optička koherentna tomografija očnog živca'),
       ('Pregled za glaukom',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pahimetrija, pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, kompjuterizovano vidno polje, pahimetrija, optička koherentna tomografija očnog živca'),
       ('Osnovni neurooftalmološki pregled sa neurološkim vidnm poljem',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska, pahimetrija, pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, pokretljivost očne jabučice, reakcija zenica, test raspoznavanja boja-Ishihara, procena izgleda pap...'),
       ('Osnovni strabološki pregled',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska (gde je moguće), pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, pokretljivost očne jabučice, cikloplegija, skrining za strabizam'),
       ('Osnovni strabološki pregled / univerzitetski profesor, docent, klinički strabolog',
        'Određivanje vidne oštrine i dioptrije, merenje očnog pritiska (gde je moguće), pregled prednjeg segmenta oka, stanje očnog sočiva, pregled očnog dna, pokretljivost očne jabučice, cikloplegija, skrining za strabizam'),
       ('Pregled za teleskopska pomagala (low vision aid) / univerzitetski professor, docent',
        'Određivanje jačine teleskopa, prizmi ili elektronskih pomagala za slabovide, bez širenja zenica, na osnovu indikacije drugog oftalmologa ili zbog lične potrebe pacijenta'),
       ('Tuširanje i ekspresija kapaka', 'Tuširanje i ekspresija kapaka'),
       ('Ispiranje suznih puteva', 'Ispiranje suznih puteva'),
       ('Subkonjunktivalna injekcija', 'Subkonjunktivalna injekcija'),
       ('Bris konjunktive oka', 'Uzimanje Brisa konjunktive i/ili rubova kapaka'),
       ('Citološki razmaz konjunktive i rožnjače /skreping',
        'Uzimanje specijalnog brisa ćelija konjunktive (spojnice) ili rožnjače oka'),
       ('Ispitivanje suznog filma (shirmer, BUT)', 'Merenje količine i kvaliteta suza'),
       ('Instrumentalno vađenje stranog tela na konjunktivi i rožnjači',
        'Vađenje stranog tela na spojnici oka ili rožnjači, lokalna anestezija, aplikacija kapi i masti, zavoj'),
       ('Hertel egzoftlmolmetrija', 'Hertel egzoftlmolmetrija'),
       ('Kornealna topografija', 'Određivanje zakrivljenosti rožnjače'),
       ('Merenje očnog pritiska – aplanaciona tonometrija', 'Merenje očnog pritiska (kontaktna metoda)'),
       ('Bezkontakno merenje očnog pritiska', 'Merenje očnog pritiska bez dodirivanja oka i ukapavanja anestetika'),
       ('Dnevna kriva očnog pritiska', 'Pet merenja očnog pritiska u toku jednog dana'),
       ('Dnevna kriva očnog pritiska – beskontaktno',
        'Pet merenja očnog pritiska u toku jednog dana bez dodirivanja oka i ukapavanja anestetika'),
       ('Merenje debljine rožnjače – pahimetrija',
        'Određivanje debljine rožnjače, bez kontakta uz određivanje korekcionog faktora visine očnog pritiska'),
       ('OCT-optička koherentna tomografija',
        'Pregled očnog živca i žute mrlje posebnim laserom, sa opisom i u midrijazi, bez oftalmološkog pregleda u ordinaciji „Macula“'),
       ('OCT-optička koherentna tomografija, kontrolni pregled',
        'Kontrolni pregled očnog živca i žute mrlej posebnim laserom, sa opisom i u midrijazi, bez oftalmološkog pregleda, obavljen u prva dva meseca nakon prvog pregleda'),
       ('OCT-optička koherentna tomografija,uz oftalmološki pregled',
        'Pregled očnog živca i žute mrlje posebnim laserom, sa opisom uz oftalmološki pregled u ordinaciji „Macula“'),
       ('Kompjuterizovano vidno polje',
        'Provera širine vidnog polja i eventualnih ispada u vidnom polju: program z aglaukom, neurološki program, program za makulu idr.'),
       ('Digitalna fundoskopija',
        'Snimak očnog dna (snimak u koloru, zatim morfologija retinalnih nervnih vlakana, krvnih sudova, merenje dijametra retinalnih krvnih sudova, praćenje veličine nevusa na očnom dnu, ožiljaka idr promena)'),
       ('Digitalni prikaz prednjeg segmenta oka', 'Snimak promena na oku, sa i bez bojenja fluoresceinom'),
       ('Meibomografija', 'Snimak lojnih žlezda kapaka');

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_examination_price`
(
    `id`                     INT UNSIGNED            NOT NULL AUTO_INCREMENT,
    `medical_examination_id` INT UNSIGNED            NOT NULL,
    `price`                  DECIMAL(10, 2) UNSIGNED NOT NULL,
    `created_at`             TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`             INT UNSIGNED            NOT NULL,
    `active`                 BOOLEAN                 NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    CONSTRAINT FOREIGN KEY (`medical_examination_id`) REFERENCES `enum_medical_examinations` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `tbl_examination_price` (`medical_examination_id`, `price`, `created_by`)
VALUES (1, 3000.00, 1),
       (2, 4000.00, 1),
       (3, 1500.00, 1),
       (4, 1500.00, 1),
       (5, 700.00, 1),
       (6, 500.00, 1),
       (7, 2000.00, 1),
       (8, 1500.00, 1),
       (9, 1500.00, 1),
       (10, 2000.00, 1),
       (11, 5500.00, 1),
       (12, 6500.00, 1),
       (13, 6500.00, 1),
       (14, 5000.00, 1),
       (15, 4000.00, 1),
       (16, 3000.00, 1),
       (17, 4000.00, 1),
       (18, 4000.00, 1),
       (19, 1000.00, 1),
       (20, 2500.00, 1),
       (21, 1000.00, 1),
       (22, 500.00, 1),
       (23, 1000.00, 1),
       (24, 800.00, 1),
       (25, 2000.00, 1),
       (26, 1000.00, 1),
       (27, 2000.00, 1),
       (28, 500.00, 1),
       (29, 500.00, 1),
       (30, 1500.00, 1),
       (31, 1500.00, 1),
       (32, 1000.00, 1),
       (33, 3000.00, 1),
       (34, 1000.00, 1),
       (35, 2000.00, 1),
       (36, 2000.00, 1),
       (37, 2000.00, 1),
       (38, 1000.00, 1),
       (39, 2000.00, 1);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_user_may_do_examinations`
(
    `id`                     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `doctor_id`              INT UNSIGNED NOT NULL,
    `medical_examination_id` INT UNSIGNED NOT NULL,
    `created_at`             TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`             INT UNSIGNED NOT NULL,
    `active`                 BOOLEAN      NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    CONSTRAINT FOREIGN KEY (`doctor_id`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`medical_examination_id`) REFERENCES `enum_medical_examinations` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_examination_status`
(
    `id`        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`      VARCHAR(255)     NOT NULL,
    `style`     VARCHAR(45),
    `treatment` TINYINT          NOT NULL,
    `visible`   BOOLEAN          NOT NULL,
    `active`    BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_examination_status` (`name`, `style`, `treatment`, `visible`, `active`)
VALUES ('Zakazan pregled', 'scheduled-appointment', 1, FALSE, TRUE),
       ('Obavešten putem mail-a', NULL, 1, FALSE, FALSE),
       ('Potvrđen dolazak', 'confirmed', 1, TRUE, TRUE),
       ('Otkazan dolazak', 'canceled', 0, TRUE, TRUE),
       ('Pacijent čeka na pregled', 'examination-ready-to-start', 1, TRUE, TRUE),
       ('Pregled obavljen', 'done', 2, FALSE, TRUE);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_eye_diseases`
(
    `id`     SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code`   VARCHAR(45)       NOT NULL,
    `name`   VARCHAR(255)      NOT NULL,
    `active` BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_eye_diseases` (`code`, `name`, `active`)
VALUES ('H00', 'Hordeolum et chalazion', 1),
       ('H00.0', 'Hordeolum et inflammationes palpebrae profuodae aliae', 1),
       ('H00.1', 'Chalazion', 1),
       ('H01', 'Morbi palpebrae inflammatorii alii', 1),
       ('H01.0', 'Blepharitis', 1),
       ('H01.1', 'Dermatosis palpebrae non infectiva', 1),
       ('H01.8', 'Morbi palpebrae inflammatorii alii, specificati', 1),
       ('H01.9', 'Morbus palpebrae inflammatorius, non specificatus', 1),
       ('H02', 'Morbi palpebrae alii', 1),
       ('H02.0', 'Entropiumet et trichiasis pelpebrae', 1),
       ('H02.1', 'Ectropium palpebrae', 1),
       ('H02.2', 'Lagophthalmus', 1),
       ('H02.3', 'Blepharochalasis', 1),
       ('H02.4', 'Ptosis palpebrae', 1),
       ('H02.5', 'Disordines palpebrae alii', 1),
       ('H02.6', 'Xanthelasma palpebrae', 1),
       ('H02.7', 'Degenerationes palpebrae et areae periocularis aliae', 1),
       ('H02.8', 'Morbi palpebrae specificati, alii', 1),
       ('H02.9', 'Morbus palpebrae, non specificatus', 1),
       ('H03', 'Morbi palpebrae in morbis aliis', 1),
       ('H03.0', 'Parasitosis palpebrae in morbis aliis', 1),
       ('H03.1', 'Morbus palpebrae in morbis infectivis aliis', 1),
       ('H03.8', 'Morbi palpebrae in morbis aliis', 0),
       ('H04', 'Morbi apparatus lacrimalis', 1),
       ('H04.0', 'Dacryoadenitis', 1),
       ('H04.1', 'Morbi glandulae lacrimalis alii', 1),
       ('H04.2', 'Epiphora', 1),
       ('H04.3', 'Dacryocystitis acuta, non specificata', 1),
       ('H04.4', 'Dacryocystitis chronica', 1),
       ('H04.5', 'Stenosis et insufficientia ductuum lacrimalium', 1),
       ('H04.6', 'Mutationes canalis lacrimalis aliae', 1),
       ('H04.8', 'Morbi apparatus lacrimalis alii', 1),
       ('H04.9', 'Morbus apparatus lacrimalis,non specificatus', 1),
       ('H05', 'Morbi orbitae', 1),
       ('H05.0', 'Inflammatio orbitae acuta', 1),
       ('H05.1', 'Inflammatio orbitae chronica', 1),
       ('H05.2', 'Exophthalmus', 1),
       ('H05.3', 'Deformatio orbitae', 1),
       ('H05.4', 'Enophthalmus', 1),
       ('H05.5', 'Corpus alienum orbitae', 1),
       ('H05.8', 'Morbi orbitae alii', 1),
       ('H05.9', 'Morbus orbitae, non specificatus', 1),
       ('H06', 'Morbi apparatus lacrimalis et morbi orbitae in morbis aliis', 1),
       ('H10', 'Conjunctivitis', 1),
       ('H10.0', 'Conjunctivitis mucopurulenta', 1),
       ('H10.1', 'Conjunctivitis allergica acuta', 1),
       ('H10.2', 'Conjunctivitis acuta alia', 1),
       ('H10.3', 'Conjunctivitis acuta, non specificata', 1),
       ('H10.4', 'Conjunctivitis chronica', 1),
       ('H10.5', 'Blepharoconjunctivitis', 1),
       ('H10.8', 'Conjunctivitis alia', 1),
       ('H10.9', 'Conjunctivitis, non specificata', 1),
       ('H11', 'Morbi conjunctivae alii', 1),
       ('H11.0', 'Pterygium', 1),
       ('H11.1', 'Degenerationes et deposita conjunctivae', 1),
       ('H11.2', 'Cicatrix conjunctivae', 1),
       ('H11.3', 'Haemorrhagia conjunctivae', 1),
       ('H11.4', 'Morbi vasorum et cystis conjunctivae alii', 1),
       ('H11.8', 'Morbi conjunctivae specificati, alii', 1),
       ('H11.9', 'Morbus conjunctivae alius, non specificatus', 1),
       ('H13', 'Morbi conjunctivae in morbis aliis', 1),
       ('H15', 'Morbi sclerae', 1),
       ('H15.0', 'Scleritis', 1),
       ('H15.1', 'Episcleritis', 1),
       ('H15.8', 'Morbi sclerae alii', 1),
       ('H15.9', 'Morbus sclerae, non specificatus', 1),
       ('H16', 'Keratitis', 1),
       ('H16.0', 'Ulcus corneae', 1),
       ('H16.1', 'Keratitis superficialis alia, sine conjunctivitide', 1),
       ('H16.2', 'Keratoconjunctivitis', 1),
       ('H16.3', 'Keratitis profunda interstitialis', 1),
       ('H16.4', 'Neovascularisatio cornealis', 1),
       ('H16.8', 'Keratitis alia', 1),
       ('H16.9', 'Keratitis,non specificata', 1),
       ('H17', 'Cicatrix et opacitas corneae', 1),
       ('H17.0', 'Leucoma adhaerens', 1),
       ('H17.1', 'Opacitas corneae centralis alia', 1),
       ('H17.8', 'Cicatrix et opacitas corneae alia', 1),
       ('H18', 'Morbi corneae alii', 1),
       ('H18.0', 'Pigmentationes et deposita corneae', 1),
       ('H18.1', 'Keratopathia bullosa', 1),
       ('H18.2', 'Oedema corneae aliud', 1),
       ('H18.3', 'Mutationes membranae cornealis', 1),
       ('H18.4', 'Degeneratio corneae', 1),
       ('H18.5', 'Dystrophia corneae congenita', 1),
       ('H18.6', 'Keratoconus', 1),
       ('H18.7', 'Deformationes corneales aliae', 1),
       ('H18.8', 'Morbi corneae specificati, alii', 1),
       ('H18.9', 'Morbus corneae, non specificatus', 1),
       ('H19', 'Morbi sclerae et morbi corneae in morbis aliis', 1),
       ('H19.0', 'Scleritis et episcleritis in morbis aliis', 1),
       ('H19.1', 'Keratitis et keratoconjunctivitis herpetica (B00.5+)', 1),
       ('H19.2', 'Keratitis et keratoconjunctivitis in morbis infectivis et morbis parasitariis aliis', 1),
       ('H19.3', 'Keratitis et keratoconjunctivitis in morbis aliis', 1),
       ('H19.8', 'Morbis sclerae et corneae alii in morbis aliis', 1),
       ('H20', 'Iridocyclitis', 1),
       ('H20.0', 'Iridocyclitis acuta et subacuta', 1),
       ('H20.1', 'Iridocyclitis chronica', 1),
       ('H20.2', 'Iridocyclitis lentica', 1),
       ('H20.8', 'Iridocylitis alia', 1),
       ('H20.9', 'Iridocyclitis, non specificata', 1),
       ('H21', 'Morbi iridis et morbi corporis ciliaris alii', 1),
       ('H21.0', 'Hyphaema', 1),
       ('H21.1', 'Morbi iridis et morbi corporis ciliaris vasculares alii', 1),
       ('H21.2', 'Degeneratio iridis et degeneratio corporis ciliaris', 1),
       ('H21.3', 'Cystis iridis, corporis ciliaris et camerae oculi anterioris', 1),
       ('H21.4', 'Membranae pupillae', 1),
       ('H21.5', 'Synechiae et rupturae iridis et corporis ciliaris aliae', 1),
       ('H21.8', 'Morbi iridis et morbi corporis ciliaris specificati, alii', 1),
       ('H21.9', 'Morbus iridis et morbus corporis ciliaris, non specificatus', 1),
       ('H22', 'Morbi iridis et morbi corporis ciliaris in morbis aliis', 1),
       ('H25', 'Cataracta senilis', 1),
       ('H25.0', 'Cataracta senilis incipiens', 1),
       ('H25.1', 'Cataracta senilis nuclearis', 1),
       ('H25.2', 'Cataracta senilis Morgagni', 1),
       ('H25.8', 'Cataracta senills alia', 1),
       ('H25.9', 'Cataracta senilis, non specificata', 1),
       ('H26', 'Cataracta alia', 1),
       ('H26.0', 'Cataracta infantilis, juverilis et praesenilis', 1),
       ('H26.1', 'Cataracta traumatica', 1),
       ('H26.2', 'Cataracta complicata', 1),
       ('H26.3', 'Cataracta medicamentosa', 1),
       ('H26.4', 'Cataracta secundaria', 1),
       ('H26.8', 'Cataracta specificata, alia', 1),
       ('H26.9', 'Cataracta, non specificata', 1),
       ('H27', 'Morbi lentis alii', 1),
       ('H27.0', 'Aphakia', 1),
       ('H27.1', 'Luxatio et subluxatio lentis', 1),
       ('H27.8', 'Morbi lentis specificati, alii', 1),
       ('H27.9', 'Morbus lentis, non specificatus', 1),
       ('H28', 'Cataracta et morbi lentis oculi alii in morbis aliis', 1),
       ('H30', 'Chorioretinitis', 1),
       ('H30.0', 'Chorioretinitis focalis', 1),
       ('H30.1', 'Chorioretinitis disseminata', 1),
       ('H30.2', 'Cyclitis posterior', 1),
       ('H30.8', 'Chorioretinitis alia', 1),
       ('H30.9', 'Chorioretinitis, non specificata', 1),
       ('H31', 'Morbi chorioideae alii', 1),
       ('H31.0', 'Cicatrix chorioretinalis', 1),
       ('H31.1', 'Degeneratio chorioidealis', 1),
       ('H31.2', 'Dystrophia chorioidealis hereditaria', 1),
       ('H31.3', 'Haemorrhagla et ruptura chorioideae', 1),
       ('H31.4', 'Ablatio chorioideae', 1),
       ('H31.8', 'Morbi chorioideae specificatus,alii', 1),
       ('H31.9', 'Morbus chorioideae, non specificatus', 1),
       ('H32', 'Morbi chorioideae et morbi retinae in morbis aliis', 1),
       ('H32.0', 'Chorioretinitis in morbis infectivis et morbis parasitariis', 1),
       ('H32.8', 'Morbi chorioideae et morbi retinae alii in morbis aliis', 1),
       ('H33', 'Ablatio retinae et ruptura retinae', 1),
       ('H33.0', 'Ablatio retinae cum ruptura', 1),
       ('H33.1', 'Retinoschisis et cystis retinae', 1),
       ('H33.2', 'Ablatio retinae serosa', 1),
       ('H33.3', 'Ruptura retinae sine ablatione', 1),
       ('H33.4', 'Ablatio retinae tractionalis', 1),
       ('H33.5', 'Ablationes retinae aliae', 1),
       ('H34', 'Occlusio retinae vascularis', 1),
       ('H34.0', 'Occlusio arteriae retinae transitiva', 1),
       ('H34.1', 'Occlusio arteriae retinae centralis', 1),
       ('H34.2', 'Occlusio arteriae retinae alia', 1),
       ('H34.8', 'Occlusiones retinae vasculares aliae', 1),
       ('H34.9', 'Occlusio retinae vascularis, non specificata', 1),
       ('H35', 'Morbi retinae alii', 1),
       ('H35.0', 'Retinopathia et angiopathia retinae', 1),
       ('H35.1', 'Retinopathia praematuri', 1),
       ('H35.2', 'Retinopathia proliferativa alia', 1),
       ('H35.3', 'Degeneratio maculae luteae et poli retinae posteriores', 1),
       ('H35.4', 'Degeneratio retinae peripherica', 1),
       ('H35.5', 'Dystrophia retinae hereditaria', 1),
       ('H35.6', 'Haemorrhagia retinae', 1),
       ('H35.7', 'Separatio stratorum retinae', 1),
       ('H35.8', 'Morbi retinae specificati, alii', 1),
       ('H35.9', 'Morbus retinae, non specificatus', 1),
       ('H36', 'Morbi retinae in morbis aliis', 1),
       ('H40', 'Glaucoma', 1),
       ('H40.1', 'Glaucoma anguli aperti primarium', 1),
       ('H40.2', 'Glaucoma anguli clausi primarium', 1),
       ('H40.3', 'Glaucoma oculi traumaticum secundarium', 1),
       ('H40.4', 'Glaucoma secundarium post inflammationem oculi', 1),
       ('H40.5', 'Glaucoma secundarium post morbus oculi alios', 1),
       ('H40.6', 'Glaucoma medicamentosum', 1),
       ('H40.8', 'Glaucoma aliud', 1),
       ('H40.9', 'Glaucoma, non specificatum', 1),
       ('H42', 'Glaucoma in morbis aliis', 1),
       ('H43', 'Morbi corporis vitrei', 1),
       ('H43.0', 'Prolapsus corporis vitrei', 1),
       ('H43.1', 'Hemorrhagia corporis vitrei', 1),
       ('H43.2', 'Deposita corporis vitrei crystallinae', 1),
       ('H43.3', 'Opacitates corporis vitrei aliae', 1),
       ('H43.8', 'Morbi corporis vitrei alii', 1),
       ('H43.9', 'Morbus corporis vitrei, non specificatus', 1),
       ('H44', 'Morbi bulbi oculi', 1),
       ('H44.0', 'Endophthalmitis purulenta', 1),
       ('H44.1', 'Endophthalmitides aliae', 1),
       ('H44.2', 'Myopia degenerativa', 1),
       ('H44.3', 'Morbi bulbi oculi degenerativi alii', 1),
       ('H44.4', 'Hypothonia oculi', 1),
       ('H44.5', 'Degenerationes bulbi oculi', 1),
       ('H44.6', 'Corpus alienum magneticum intraoculare chronicum', 1),
       ('H44.7', 'Corpus alienum non magneticum intraoculare chronicum', 1),
       ('H44.8', 'Morbi bulbi oculi alii', 1),
       ('H44.9', 'Morbi bulbi oculi, non specificatus', 1),
       ('H45', 'Morbi corporis vitrei morbi bulbi oculi in morbis aliis', 1),
       ('H46', 'Neuritis nervi optici', 1),
       ('H47', 'Morbi nervi optici et morbi tractuum opticorum alii', 1),
       ('H47.0', 'Morbi nervi optici', 1),
       ('H47.1', 'Oedema papillae nervi optici, non specificatum', 1),
       ('H47.2', 'Atrophia nervi optici', 1),
       ('H47.3', 'Morbi disci nervi optici alii', 1),
       ('H47.4', 'Morbi chiasmatis nervi optici', 1),
       ('H47.5', 'Morbi tractus optici alii', 1),
       ('H47.6', 'Morbi corticis optici lobi occipitalis', 1),
       ('H47.7', 'Morbus tractus optici, non specificatus', 1),
       ('H48', 'Morbi nervi optici et morbi tractuum opticorum in morbi aliis', 1),
       ('H49', 'Strabismus paralyticus', 1),
       ('H49.0', 'Paralysis nervi oculomotorii', 1),
       ('H49.1', 'Paralysis nervi trochlearis', 1),
       ('H49.2', 'Paralysis nervi abducentis', 1),
       ('H49.3', 'Ophthalmoplegia totalis (externa)', 1),
       ('H49.4', 'Ophthalmoplegla externa progressiva', 1),
       ('H49.8', 'Strabismus paralyticus alius', 1),
       ('H49.9', 'Strabismus paralyticus, non specificatus', 1),
       ('H50', 'Strabismus alius', 1),
       ('H50.0', 'Strabismus concomitans convergens', 1),
       ('H50.1', 'Strabismus concomitans divergens', 1),
       ('H50.2', 'Strabismus verticalis', 1),
       ('H50.3', 'Heterotropia temporalis', 1),
       ('H50.4', 'Heterotropia alia, non specificata', 1),
       ('H50.5', 'Heterophoria', 1),
       ('H50.6', 'Strabismus mechanicus', 1),
       ('H50.8', 'Strabismus specificatus, alius', 1),
       ('H50.9', 'Strabismus, non specificatus', 1),
       ('H51', 'Anomaliae motuum binocularium aliae', 1),
       ('H51.0', 'Paralysis aspectus conjuncti', 1),
       ('H51.1', 'Insufficientia sive excessus convergentiae', 1),
       ('H51.2', 'Ophthalmoplegla internuclearis', 1),
       ('H51.8', 'Anomaliae motuum binocularium aliae,specificatae', 1),
       ('H51.9', 'Anomalia motuum binocularium, non specificata', 1),
       ('H52', 'Anomaliae refractionis et anomaliae accommodationis', 1),
       ('H53', 'Disordines visuales', 1),
       ('H53.0', 'Amblyopia sine anopsia', 1),
       ('H53.1', 'Disordines visus subjectivi', 1),
       ('H53.2', 'Diplopia', 1),
       ('H53.3', 'Disordines visus binocularis alii', 1),
       ('H53.4', 'Defectus regionis visualis', 1),
       ('H53.5', 'Dyschromatopsiae', 1),
       ('H53.6', 'Nyctalopia', 1),
       ('H53.8', 'Disordines visuales alii', 1),
       ('H53.9', 'Disordo visualis, non specificatus', 1),
       ('H54', 'Amaurosis et amblyopia', 1),
       ('H55', 'Nystagmus et motus oculi inaequales alii', 1),
       ('H57', 'Morbi oculi et adnexorurn alii', 1),
       ('H57.0', 'Anomaliae functionis pupillae', 1),
       ('H57.1', 'Dolor oculi', 1),
       ('H57.8', 'Morbi oculi et adnexorum specificati,alii', 1),
       ('H57.9', 'Morbus oculi et adnexorum, non specificatus', 1),
       ('H58', 'Morbi okuli et adnexorum alii in morbis aliis', 1),
       ('H58.0', 'Anomaliae functionis pupillae in morbis aliis', 1),
       ('H58.1', 'Disordines in morbis aliis', 1),
       ('H58.8', 'Morbi okuli et adnexorum specificati,alii in morbis aliis', 1),
       ('H59', 'Disordines oculi et adnexorum post intervetiones', 1),
       ('H54', 'Amblyopia', 1),
       ('H52.2', 'Astigmatismus myopicus', 1),
       ('H52.2', 'Astigmatismus mixtus', 1),
       ('H52.4', 'Presbyopia', 1),
       ('H11.41', 'Cystits conjunctivae', 1),
       ('H40.0', 'Glaucoma suspectum', 1),
       ('H52.0', 'Hypermetropia', 1),
       ('Z96.1', 'Pseudophakia', 1),
       ('H40.22', 'Glaucoma angulare chronicum', 1),
       ('H16.200', 'Keratoconjunctivitis sicca', 1),
       ('H52.1', 'Myopia', 1),
       ('H52.1', 'Myopia', 0),
       ('H52.2', 'Astigmatismus', 1),
       ('H18.511', 'Dystophia corneae (map, dot, fingerprint)', 1),
       ('H18.512', 'Erosio corneae recidivans congenita/hereditaria', 1),
       ('H10.415', 'Conjunctivitis gigantopapillaris', 1),
       ('H02.4', 'Ptosis palpebrae superioris', 1),
       ('H11.3', 'Suffusio conjunctivae', 1),
       ('H52.2', 'Astigmatismus hypermetropicus', 1),
       ('Z00-Z13', 'Lica koja traže zdravstvene usluge radi pregleda i ispitivanja', 1),
       ('Z00-Z13', 'Sine morbo ophthalmologico', 1),
       ('T15.0', 'Corpus alienum corneae', 1),
       ('H35.35', 'Maculopathia', 1),
       ('Z97.353', 'Conjunctivitis gigantopapillaris propter lentem contactam', 1),
       ('H35.38', 'Drusae maculae luteae', 1),
       ('S05.03', 'Erosio corneae', 1),
       ('T26.6', 'Causoma corneae et conjunctivae', 1),
       ('H02.81', 'Cystis palpebrae', 1),
       ('H11.11', 'Pinguecula', 1),
       ('H35', 'Maculopathia', 0),
       ('H16.27', 'Infiltratio cornee propter blepharoconjuntivitidem', 1),
       ('Z97.00', 'Prothesis ocularis', 1),
       ('Z96.81', 'Keratoprothesis', 1),
       ('T26.6', 'Causoma conjunctivae', 1),
       ('H33.0', 'Ablatio retinae', 1),
       ('H02.01', 'Entropium senile', 1),
       ('H26.0', 'Cataracta praesenilis incipiens', 1),
       ('H16.2', 'Keratoconjunctivitis allergica', 1),
       ('H16', 'Infiltratio cornee propter KCE', 1),
       ('H18.531', 'Dystrophio corneae endoepithelialis sec. Fuchs', 1),
       ('H 18.531', 'Dystrophio corneae endoepithelialis sec. Fuchs', 0),
       ('H10', 'Conjunctivitis allergica', 1),
       ('H04.56', 'Stenosis ductus lacrimonasalis', 1),
       ('H04.561', 'Stenosis ductus lacrimonasalis incompleta', 1),
       ('H17.18', 'Macula corneae centralis', 1),
       ('H44', 'Corpus alienum subtarsale', 1),
       ('H18.05', 'Erosio corneae recidivans', 1),
       ('H 50.0', 'Esotropia', 1),
       ('H 26.0', 'Cataracta congenita', 1),
       ('C 44.18', 'Tu palpebrae superior', 1),
       ('T 15.01', 'Corpus alienum limbi corneae cum', 1),
       ('T 15.05', 'Siderosis corneae post extractionem corpori alieni corneae', 1),
       ('H 54', 'Amaurosis', 1),
       ('H16', 'Infiltratio corneae', 1);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_professions`
(
    `id`              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `profession_name` VARCHAR(255)      NOT NULL,
    `active`          BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`profession_name`),
    UNIQUE KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_professions` (`profession_name`)
VALUES ('Nepoznata');

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_recommendation_type`
(
    `id`     TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`   VARCHAR(255)     NOT NULL,
    `hash`   VARCHAR(255)              DEFAULT NULL,
    `active` BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_recommendation_type` (`name`, `hash`)
VALUES ('Saradnik', '#associates'),
       ('Internet', '#internet-recommendation'),
       ('Pacijent ordinacije', '#patient-recommendation'),
       ('Drugo', NULL);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_templates`
(
    `id`             TINYINT UNSIGNED AUTO_INCREMENT,
    `template_name`  VARCHAR(255) NOT NULL,
    `twig_file_name` VARCHAR(255) NOT NULL,
    `active`         BOOLEAN      NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`template_name`, `twig_file_name`),
    UNIQUE KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_templates` (`template_name`, `twig_file_name`)
VALUES ('Specijalistički izveštaj', 'specialist-examination.html.twig'),
       ('Dnevna kriva', 'daily-curve.html.twig'),
       ('KVP', 'kvp.html.twig'),
       ('Refrakcija', 'refraction.html.twig'),
       ('LVA', 'lva.html.twig'),
       ('Prazan nalaz', 'empty-findings.html.twig');

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `enum_web_recommendation`
(
    `id`     TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`   VARCHAR(255)     NOT NULL,
    `active` BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `enum_web_recommendation` (`name`)
VALUES ('Facebook'),
       ('Web site'),
       ('Reklama');

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_contact_persons`
(
    `id`             INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255)      NOT NULL,
    `surname`        VARCHAR(255)      NOT NULL,
    `dial_number_id` SMALLINT UNSIGNED NOT NULL,
    `phone_number`   VARCHAR(10)       NOT NULL,
    `updated_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by`     INT UNSIGNED      NOT NULL,
    `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`     INT UNSIGNED      NOT NULL,
    `active`         BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`name`, `surname`, `dial_number_id`, `phone_number`),
    UNIQUE KEY (`id`),
    CONSTRAINT FOREIGN KEY (`dial_number_id`) REFERENCES `enum_dial_numbers` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

INSERT INTO `tbl_contact_persons` (`name`, `surname`, `dial_number_id`, `phone_number`, `updated_by`, `created_by`)
VALUES ('N', 'N', 1, '0000000', 1, 1);

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_patients`
(
    `id`                INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(255)      NOT NULL,
    `surname`           VARCHAR(255)      NOT NULL,
    `personal_id`       CHAR(13),
    `date_of_birth`     DATE                       DEFAULT NULL,
    `sex_id`            BOOLEAN           NOT NULL,
    `email`             VARCHAR(255)               DEFAULT NULL,
    `dial_number_id`    SMALLINT UNSIGNED NOT NULL,
    `phone_number`      VARCHAR(10)       NOT NULL,
    `address`           VARCHAR(255)               DEFAULT NULL,
    `profession_id`     SMALLINT UNSIGNED NOT NULL,
    `contact_person_id` INT UNSIGNED      NOT NULL,
    `user_comment`      TEXT,
    `updated_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by`        INT UNSIGNED      NOT NULL,
    `created_at`        TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`        INT UNSIGNED      NOT NULL,
    `active`            BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`name`, `surname`, `sex_id`, `dial_number_id`, `phone_number`),
    UNIQUE KEY (`id`),
    CONSTRAINT FOREIGN KEY (`dial_number_id`) REFERENCES `enum_dial_numbers` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`profession_id`) REFERENCES `enum_professions` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`contact_person_id`) REFERENCES `tbl_contact_persons` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_appointments`
(
    `id`                     INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `patient_id`             INT UNSIGNED     NOT NULL,
    `medical_examination_id` INT UNSIGNED     NOT NULL,
    `doctor_id`              INT UNSIGNED     NOT NULL,
    `start_date_time`        TIMESTAMP        NULL,
    `end_date_time`          TIMESTAMP        NULL,
    `examination_status_id`  TINYINT UNSIGNED NOT NULL,
    `updated_at`             TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by`             INT UNSIGNED     NOT NULL,
    `created_at`             TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`             INT UNSIGNED     NOT NULL,
    `active`                 BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    CONSTRAINT FOREIGN KEY (`patient_id`) REFERENCES `tbl_patients` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`medical_examination_id`) REFERENCES `enum_medical_examinations` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`doctor_id`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`examination_status_id`) REFERENCES `enum_examination_status` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_associates`
(
    `id`             INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255)      NOT NULL,
    `email`          VARCHAR(255)               DEFAULT NULL,
    `dial_number_id` SMALLINT UNSIGNED NOT NULL,
    `phone_number`   VARCHAR(10)                DEFAULT NULL,
    `created_at`     TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`     INT UNSIGNED      NOT NULL,
    `active`         BOOLEAN           NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`, `email`),
    CONSTRAINT FOREIGN KEY (`dial_number_id`) REFERENCES `enum_dial_numbers` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_examination_reports`
(
    `id`                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `template_id`                      TINYINT UNSIGNED NOT NULL,
    `appointment_id`                   INT UNSIGNED     NOT NULL,
    `anamnesis`                        TEXT,
    `vod`                              VARCHAR(45),
    `vos`                              VARCHAR(45),
    `tod`                              VARCHAR(45),
    `tos`                              VARCHAR(45),
    `tod_c`                            VARCHAR(45),
    `tod_s`                            VARCHAR(45),
    `rod`                              VARCHAR(45),
    `ros`                              VARCHAR(45),
    `cct_od`                           VARCHAR(45),
    `cct_os`                           VARCHAR(45),
    `kf_od`                            VARCHAR(45),
    `kf_os`                            VARCHAR(45),
    `ar_od`                            VARCHAR(45),
    `ar_os`                            VARCHAR(45),
    `ar_od_ar_os_measurement_method`   TEXT,
    `kr_od`                            VARCHAR(45),
    `kr_os`                            VARCHAR(45),
    `motilitet_ou`                     TEXT,
    `foko_f_od`                        VARCHAR(45),
    `foko_f_os`                        VARCHAR(45),
    `foko_k_od`                        VARCHAR(45),
    `foko_k_os`                        VARCHAR(45),
    `foko_n_od`                        VARCHAR(45),
    `foko_n_os`                        VARCHAR(45),
    `add_od`                           VARCHAR(45),
    `add_os`                           VARCHAR(45),
    `slod`                             TEXT,
    `slos`                             TEXT,
    `slou`                             TEXT,
    `fod`                              TEXT,
    `fos`                              TEXT,
    `fou`                              TEXT,
    `kvp`                              TEXT,
    `kvp_conclusion`                   TEXT,
    `kvp_control`                      TEXT,
    `oct_pno`                          TEXT,
    `oct_pno_conclusion`               TEXT,
    `oct_pno_control`                  TEXT,
    `eye_ultrasound`                   TEXT,
    `meibomography`                    TEXT,
    `corneal_topography`               TEXT,
    `examination_conclusion`           TEXT,
    `examination_advice`               TEXT,
    `control_with_the_ophthalmologist` TEXT,
    `therapy_at_home`                  TEXT,
    `diagnosis`                        TEXT,
    `lva`                              TEXT,
    `created_at`                       TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`                       INT UNSIGNED     NOT NULL,
    `active`                           BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    FULLTEXT KEY (`diagnosis`),
    CONSTRAINT FOREIGN KEY (`template_id`) REFERENCES `enum_templates` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`appointment_id`) REFERENCES `tbl_appointments` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_examination_reports_icd`
(
    `id`                    INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `examination_report_id` INT UNSIGNED      NOT NULL,
    `eye_diseases_id`       SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (`examination_report_id`, `eye_diseases_id`),
    UNIQUE KEY (`id`),
    CONSTRAINT FOREIGN KEY (`examination_report_id`) REFERENCES `tbl_examination_reports` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`eye_diseases_id`) REFERENCES `enum_eye_diseases` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_recommendations`
(
    `id`                     INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `patient_id`             INT UNSIGNED     NOT NULL,
    `recommendation_type_id` TINYINT UNSIGNED NOT NULL,
    `associate_id`           INT UNSIGNED              DEFAULT NULL,
    `updated_at`             TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by`             INT UNSIGNED     NOT NULL,
    `created_at`             TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`             INT UNSIGNED     NOT NULL,
    `active`                 BOOLEAN          NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    CONSTRAINT FOREIGN KEY (`patient_id`) REFERENCES `tbl_patients` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`recommendation_type_id`) REFERENCES `enum_recommendation_type` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
# ===============================================================
# ===============================================================

CREATE TABLE `tbl_user_work_schedule`
(
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`         INT UNSIGNED NOT NULL,
    `start_date_time` DATETIME     NOT NULL,
    `end_date_time`   DATETIME     NOT NULL,
    `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_by`      INT UNSIGNED NOT NULL,
    `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`      INT UNSIGNED NOT NULL,
    `active`          BOOLEAN      NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`id`),
    CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES `tbl_user` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_unicode_ci;

# ===============================================================
#   FINISH AND SHOW EXECUTION TIME
# ===============================================================

SELECT timediff(now(), @start_time) AS finished
FROM DUAL;