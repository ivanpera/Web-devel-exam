-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.1              
-- * Generator date: Dec 19 2018              
-- * Generation date: Fri Jan 24 19:09:41 2020 
-- * LUN file: /run/media/alex/DATA/DATAHome/Documents/Università/Altro/Progetto TecWeb/db/TECWEB.lun 
-- * Schema: DB/SQL 
-- ********************************************* 


-- Database Section
-- ________________ 

create database projtecweb CHARACTER SET utf16 COLLATE utf16_unicode_ci;
use projtecweb;


-- Tables Section
-- _____________ 

create table CATEGORIA_EVENTO (
     codCategoria int not null,
     nomeCategoria varchar(30) not null,
     constraint ID_CATEGORIA_EVENTO_ID primary key (codCategoria));

create table EVENTO (
     codEvento int not null,
     nomeEvento varchar(60) not null,
     dataEOra DATETIME not null,
     NSFC BIT(1) not null,
     descrizione varchar(150) not null,
     nomeImmagine varchar(30) not null,
     codLuogo int not null,
     emailOrganizzatore varchar(30) not null,
     constraint ID_EVENTO_ID primary key (codEvento));

create table EVENTO_HA_CATEGORIA (
     codCategoria int not null,
     codEvento int not null,
     constraint ID_EVENTO_HA_CATEGORIA_ID primary key (codEvento, codCategoria));

create table LUOGO (
     codLuogo int not null,
     nome varchar(60) not null,
     indirizzo varchar(50) not null,
     urlMaps varchar(128) not null,
     capienzaMassima int not null,
     constraint ID_LUOGO_ID primary key (codLuogo));

create table MODERAZIONE (
     emailModeratore varchar(30) not null,
     codEvento int not null,
     constraint ID_MODERAZIONE_ID primary key (codEvento, emailModeratore));

create table NOTIFICA (
     codEvento int not null,
     codNotificaEvento int not null,
     titolo varchar(100) not null,
     descrizione varchar(128) not null,
     letta BIT(1) not null,
     dataEOraInvio DATETIME not null,
     differenzaGiorni int,
     emailUtente varchar(30) not null,
     constraint ID_NOTIFICA_ID primary key (codEvento, codNotificaEvento));

create table OSSERVA (
     codEvento int not null,
     emailUtente varchar(30) not null,
     constraint ID_OSSERVA_ID primary key (codEvento, emailUtente));

create table POSTO (
     codEvento int not null,
     codPosto int not null,
     costo int not null,
     codTipologia int not null,
     codPrenotazione int,
     constraint ID_POSTO_ID primary key (codEvento, codPosto));

create table PRENOTAZIONE (
     codPrenotazione int not null,
     dataEOra DATETIME not null,
     costoTotale int not null,
     differenzaGiorni int not null,
     emailUtente varchar(30) not null,
     constraint ID_PRENOTAZIONE_ID primary key (codPrenotazione));

create table RECENSIONE (
     codEvento int not null,
     emailUtente varchar(30) not null,
     voto int not null,
     testo varchar(500) not null,
     anonima BIT(1) not null,
     dataScrittura date not null,
     constraint ID_RECENSIONE_ID primary key (emailUtente, codEvento));

create table TIPOLOGIA_POSTO (
     codTipologia int not null,
     nomeTipologia varchar(30) not null,
     constraint ID_TIPOLOGIA_POSTO_ID primary key (codTipologia));

create table UTENTE (
     email varchar(30) not null,
     userPassword varchar(128) not null,
     nome varchar(30) not null,
     cognome varchar(30) not null,
     dataNascita date not null,
     genere char(1) not null,
     dataIscrizione date not null,
     organizzatore BIT(1) not null,
     amministratore BIT(1) not null,
     constraint ID_UTENTE_ID primary key (email));


-- Constraints Section
-- ___________________ 

-- Not implemented
-- alter table EVENTO add constraint ID_EVENTO_CHK
--     check(exists(select * from EVENTO_HA_CATEGORIA
--                  where EVENTO_HA_CATEGORIA.codEvento = codEvento)); 

alter table EVENTO add constraint REF_EVENT_LUOGO_FK
     foreign key (codLuogo)
     references LUOGO (codLuogo);

alter table EVENTO add constraint REF_EVENT_UTENT_FK
     foreign key (emailOrganizzatore)
     references UTENTE (email);

alter table EVENTO_HA_CATEGORIA add constraint EQU_event_EVENT
     foreign key (codEvento)
     references EVENTO (codEvento);

alter table EVENTO_HA_CATEGORIA add constraint REF_event_CATEG_FK
     foreign key (codCategoria)
     references CATEGORIA_EVENTO (codCategoria);

alter table MODERAZIONE add constraint REF_moder_EVENT
     foreign key (codEvento)
     references EVENTO (codEvento);

alter table MODERAZIONE add constraint REF_moder_UTENT_FK
     foreign key (emailModeratore)
     references UTENTE (email);

alter table NOTIFICA add constraint REF_NOTIF_EVENT
     foreign key (codEvento)
     references EVENTO (codEvento);

alter table NOTIFICA add constraint REF_NOTIF_UTENT_FK
     foreign key (emailUtente)
     references UTENTE (email);

alter table OSSERVA add constraint REF_osser_UTENT_FK
     foreign key (emailUtente)
     references UTENTE (email);

alter table OSSERVA add constraint REF_osser_EVENT
     foreign key (codEvento)
     references EVENTO (codEvento);

alter table POSTO add constraint REF_POSTO_TIPOL_FK
     foreign key (codTipologia)
     references TIPOLOGIA_POSTO (codTipologia);

alter table POSTO add constraint REF_POSTO_EVENT
     foreign key (codEvento)
     references EVENTO (codEvento);

alter table POSTO add constraint EQU_POSTO_PRENO_FK
     foreign key (codPrenotazione)
     references PRENOTAZIONE (codPrenotazione);

-- Not implemented
-- alter table PRENOTAZIONE add constraint ID_PRENOTAZIONE_CHK
--     check(exists(select * from POSTO
--                  where POSTO.codPrenotazione = codPrenotazione)); 

alter table PRENOTAZIONE add constraint REF_PRENO_UTENT_FK
     foreign key (emailUtente)
     references UTENTE (email);

alter table RECENSIONE add constraint REF_RECEN_UTENT
     foreign key (emailUtente)
     references UTENTE (email);

alter table RECENSIONE add constraint REF_RECEN_EVENT_FK
     foreign key (codEvento)
     references EVENTO (codEvento);


-- Index Section
-- _____________ 

create unique index ID_CATEGORIA_EVENTO_IND
     on CATEGORIA_EVENTO (codCategoria);

create unique index ID_EVENTO_IND
     on EVENTO (codEvento);

create index REF_EVENT_LUOGO_IND
     on EVENTO (codLuogo);

create index REF_EVENT_UTENT_IND
     on EVENTO (emailOrganizzatore);

create unique index ID_EVENTO_HA_CATEGORIA_IND
     on EVENTO_HA_CATEGORIA (codEvento, codCategoria);

create index REF_event_CATEG_IND
     on EVENTO_HA_CATEGORIA (codCategoria);

create unique index ID_LUOGO_IND
     on LUOGO (codLuogo);

create unique index ID_MODERAZIONE_IND
     on MODERAZIONE (codEvento, emailModeratore);

create index REF_moder_UTENT_IND
     on MODERAZIONE (emailModeratore);

create unique index ID_NOTIFICA_IND
     on NOTIFICA (codEvento, codNotificaEvento);

create index REF_NOTIF_UTENT_IND
     on NOTIFICA (emailUtente);

create unique index ID_OSSERVA_IND
     on OSSERVA (codEvento, emailUtente);

create index REF_osser_UTENT_IND
     on OSSERVA (emailUtente);

create unique index ID_POSTO_IND
     on POSTO (codEvento, codPosto);

create index REF_POSTO_TIPOL_IND
     on POSTO (codTipologia);

create index EQU_POSTO_PRENO_IND
     on POSTO (codPrenotazione);

create unique index ID_PRENOTAZIONE_IND
     on PRENOTAZIONE (codPrenotazione);

create index REF_PRENO_UTENT_IND
     on PRENOTAZIONE (emailUtente);

create unique index ID_RECENSIONE_IND
     on RECENSIONE (emailUtente, codEvento);

create index REF_RECEN_EVENT_IND
     on RECENSIONE (codEvento);

create unique index ID_TIPOLOGIA_POSTO_IND
     on TIPOLOGIA_POSTO (codTipologia);

create unique index ID_UTENTE_IND
     on UTENTE (email);
