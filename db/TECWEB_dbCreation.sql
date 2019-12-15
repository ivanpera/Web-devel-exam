-- *********************************************
-- * Standard SQL generation                   
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.1              
-- * Generator date: Dec 19 2018              
-- * Generation date: Sun Dec 15 12:04:41 2019 
-- * LUN file: /run/media/alex/DATA/DATAHome/Documents/Università/Altro/Progetto TecWeb/db/TECWEB.lun 
-- * Schema: DB/SQL 
-- ********************************************* 


-- Database Section
-- ________________ 

create database DB;


-- DBSpace Section
-- _______________


-- Tables Section
-- _____________ 

create table CATEGORIA_EVENTO (
     codCategoria INT unsigned not null,
     nomeCategoria varchar(30) not null,
     constraint ID_CATEGORIA_EVENTO_ID primary key (codCategoria));

create table NOTIFICA_EVENTO (
     codEvento int unsigned not null,
     codNotificaEvento int unsigned not null,
     descrizione varchar(128) not null,
     letta char not null,
     dataEOraInvio DATETIME not null,
     constraint ID_NOTIFICA_EVENTO_ID primary key (codEvento, codNotificaEvento));

create table NOTIFICA_PRENOTAZIONE (
     codPrenotazione int unsigned not null,
     codNotificaPrenotazione int unsigned not null,
     descrizione varchar(128) not null,
     letta char not null,
     dataEOraInvio DATETIME not null,
     constraint ID_NOTIFICA_PRENOTAZIONE_ID primary key (codPrenotazione, codNotificaPrenotazione));

create table EVENTO (
     codEvento int unsigned not null,
     nomeEvento varchar(60) not null,
     dataEOra DATETIME not null,
     indirizzo varchar(60) not null,
     NSFC char not null,
     immagine varchar(50) not null,
     emailOrganizzatore varchar(30) not null,
     constraint ID_EVENTO_ID primary key (codEvento));

create table RECENSIONE (
     codEvento int unsigned not null,
     emailUtente varchar(30) not null,
     voto int unsigned not null,
     testo varchar(500) not null,
     anonima char not null,
     dataScrittura date not null,
     constraint ID_RECENSIONE_ID primary key (emailUtente, codEvento));

create table POSTO (
     codEvento int unsigned not null,
     codPosto int unsigned not null,
     costo int unsigned not null,
     codPrenotazione int unsigned,
     codTipologia int unsigned not null,
     constraint ID_POSTO_ID primary key (codEvento, codPosto));

create table PRENOTAZIONE (
     codPrenotazione int unsigned not null,
     dataEOra DATETIME not null,
     costoTotale int unsigned not null,
     emailUtente varchar(30) not null,
     constraint ID_PRENOTAZIONE_ID primary key (codPrenotazione));

create table evento_ha_categoria (
     codCategoria int unsigned not null,
     codEvento int unsigned not null,
     constraint ID_evento_ha_categoria_ID primary key (codEvento, codCategoria));

create table moderazione (
     email varchar(30) not null,
     codEvento int unsigned not null,
     constraint ID_moderazione_ID primary key (codEvento, email));

create table TIPOLOGIA_POSTO (
     codTipologia int unsigned not null,
     nomeTipologia varchar(30) not null,
     constraint ID_TIPOLOGIA_POSTO_ID primary key (codTipologia));

create table UTENTE (
     email varchar(30) not null,
     userPassword varchar(64) not null,
     nomeCompleto varchar(60) not null,
     eta int unsigned not null,
     genere char(1) not null,
     dataIscrizione date not null,
     isOrganizer char not null,
     isAdmin char not null,
     constraint ID_UTENTE_ID primary key (email),
     constraint SID_UTENTE_ID unique (nomeCompleto));


-- Constraints Section
-- ___________________ 

alter table NOTIFICA_EVENTO add constraint REF_NOTIF_EVENT
     foreign key (codEvento)
     references EVENTO;

alter table NOTIFICA_PRENOTAZIONE add constraint REF_NOTIF_PRENO
     foreign key (codPrenotazione)
     references PRENOTAZIONE;

alter table EVENTO add constraint ID_EVENTO_CHK
     check(exists(select * from evento_ha_categoria
                  where evento_ha_categoria.codEvento = codEvento)); 

alter table EVENTO add constraint REF_EVENT_UTENT_FK
     foreign key (emailOrganizzatore)
     references UTENTE;

alter table RECENSIONE add constraint REF_RECEN_UTENT
     foreign key (emailUtente)
     references UTENTE;

alter table RECENSIONE add constraint REF_RECEN_EVENT_FK
     foreign key (codEvento)
     references EVENTO;

alter table POSTO add constraint EQU_POSTO_PRENO_FK
     foreign key (codPrenotazione)
     references PRENOTAZIONE;

alter table POSTO add constraint REF_POSTO_TIPOL_FK
     foreign key (codTipologia)
     references TIPOLOGIA_POSTO;

alter table POSTO add constraint REF_POSTO_EVENT
     foreign key (codEvento)
     references EVENTO;

alter table PRENOTAZIONE add constraint ID_PRENOTAZIONE_CHK
     check(exists(select * from POSTO
                  where POSTO.codPrenotazione = codPrenotazione)); 

alter table PRENOTAZIONE add constraint REF_PRENO_UTENT_FK
     foreign key (emailUtente)
     references UTENTE;

alter table evento_ha_categoria add constraint EQU_event_EVENT
     foreign key (codEvento)
     references EVENTO;

alter table evento_ha_categoria add constraint REF_event_CATEG_FK
     foreign key (codCategoria)
     references CATEGORIA_EVENTO;

alter table moderazione add constraint REF_moder_EVENT
     foreign key (codEvento)
     references EVENTO;

alter table moderazione add constraint REF_moder_UTENT_FK
     foreign key (email)
     references UTENTE;


-- Index Section
-- _____________ 

create unique index ID_CATEGORIA_EVENTO_IND
     on CATEGORIA_EVENTO (codCategoria);

create unique index ID_NOTIFICA_EVENTO_IND
     on NOTIFICA_EVENTO (codEvento, codNotificaEvento);

create unique index ID_NOTIFICA_PRENOTAZIONE_IND
     on NOTIFICA_PRENOTAZIONE (codPrenotazione, codNotificaPrenotazione);

create unique index ID_EVENTO_IND
     on EVENTO (codEvento);

create index REF_EVENT_UTENT_IND
     on EVENTO (emailOrganizzatore);

create unique index ID_RECENSIONE_IND
     on RECENSIONE (emailUtente, codEvento);

create index REF_RECEN_EVENT_IND
     on RECENSIONE (codEvento);

create unique index ID_POSTO_IND
     on POSTO (codEvento, codPosto);

create index EQU_POSTO_PRENO_IND
     on POSTO (codPrenotazione);

create index REF_POSTO_TIPOL_IND
     on POSTO (codTipologia);

create unique index ID_PRENOTAZIONE_IND
     on PRENOTAZIONE (codPrenotazione);

create index REF_PRENO_UTENT_IND
     on PRENOTAZIONE (emailUtente);

create unique index ID_evento_ha_categoria_IND
     on evento_ha_categoria (codEvento, codCategoria);

create index REF_event_CATEG_IND
     on evento_ha_categoria (codCategoria);

create unique index ID_moderazione_IND
     on moderazione (codEvento, email);

create index REF_moder_UTENT_IND
     on moderazione (email);

create unique index ID_TIPOLOGIA_POSTO_IND
     on TIPOLOGIA_POSTO (codTipologia);

create unique index ID_UTENTE_IND
     on UTENTE (email);

create unique index SID_UTENTE_IND
     on UTENTE (nomeCompleto);

