/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  16/08/2024 12:29:30                      */
/*==============================================================*/

drop table if exists AUDIT_LOGS;
drop table if exists CATEGORIES;
drop table if exists CONTENIR;
drop table if exists DOCUMENT;
drop table if exists DOCUMENT_VERSION;
drop table if exists PERMISSIONS;
drop table if exists USER_PERMISSION;
drop table if exists UTILISATEUR;

/*==============================================================*/
/* Table : UTILISATEUR                                          */
/*==============================================================*/
create table UTILISATEUR
(
   ID_UTILISATEUR       int not null AUTO_INCREMENT,
   MOT_DE_PASSE         varchar(200),
   EMAIL                varchar(200),
   ROLE                 varchar(50),   -- Taille réduite
   NOM_UTIL             varchar(100),  -- Taille réduite
   primary key (ID_UTILISATEUR)
);

/*==============================================================*/
/* Table : DOCUMENT                                             */
/*==============================================================*/
create table DOCUMENT
(
   ID_DOCUMENT          int not null AUTO_INCREMENT,
   ID_UTILISATEUR       int not null,
   TITRE                varchar(200),
   DESCRIPTION          varchar(200),
   FILE_PATH            varchar(200),
   CREATED_AT           DATETIME,      -- Changement de type
   UPDATED_AT           DATETIME,      -- Changement de type
   VERSION              varchar(50),   -- Taille réduite
   primary key (ID_DOCUMENT)
);

/*==============================================================*/
/* Table : CATEGORIES                                           */
/*==============================================================*/
create table CATEGORIES
(
   ID_CATEGORIES        int not null AUTO_INCREMENT,
   NOM                  varchar(100),  -- Taille réduite
   DESCRIPTION_C        varchar(200),
   primary key (ID_CATEGORIES)
);

/*==============================================================*/
/* Table : AUDIT_LOGS                                           */
/*==============================================================*/
create table AUDIT_LOGS
(
   ID_AUDIT_LOGS        int not null AUTO_INCREMENT,
   ID_UTILISATEUR       int not null,
   ACTION               varchar(200),
   TIMESTAMP            DATETIME,      -- Changement de type
   primary key (ID_AUDIT_LOGS)
);

/*==============================================================*/
/* Table : CONTENIR                                             */
/*==============================================================*/
create table CONTENIR
(
   ID_DOCUMENT          int not null,
   ID_CATEGORIES        int not null,
   primary key (ID_DOCUMENT, ID_CATEGORIES)
);

/*==============================================================*/
/* Table : DOCUMENT_VERSION                                     */
/*==============================================================*/
create table DOCUMENT_VERSION
(
   ID_DOCUMENT_VERSION  int not null AUTO_INCREMENT,
   ID_DOCUMENT          int not null,
   VERSION_NUMBER       varchar(50),   -- Taille réduite
   FILE_PATH_D          varchar(200),
   CREATED_AT_D         DATETIME,      -- Changement de type
   UPDATED_BY           int,           -- Ajout potentiel de clé étrangère
   primary key (ID_DOCUMENT_VERSION),
   foreign key (UPDATED_BY) references UTILISATEUR(ID_UTILISATEUR) on delete set null on update cascade  -- Clé étrangère potentielle
);

/*==============================================================*/
/* Table : PERMISSIONS                                          */
/*==============================================================*/
create table PERMISSIONS
(
   ID_PERMISSIONS       int not null AUTO_INCREMENT,
   CAN_VIEW             varchar(5),    -- Taille réduite
   CAN_EDIT             varchar(5),    -- Taille réduite
   CAN_DELETE           varchar(5),    -- Taille réduite
   primary key (ID_PERMISSIONS)
);

/*==============================================================*/
/* Table : USER_PERMISSION                                      */
/*==============================================================*/
create table USER_PERMISSION
(
   ID_UTILISATEUR       int not null,
   ID_PERMISSIONS       int not null,
   primary key (ID_UTILISATEUR, ID_PERMISSIONS)
);

/*==============================================================*/
/* Contraintes de clé étrangère                                 */
/*==============================================================*/
alter table AUDIT_LOGS add constraint FK_GERE foreign key (ID_UTILISATEUR)
      references UTILISATEUR (ID_UTILISATEUR) on delete cascade on update cascade;

alter table CONTENIR add constraint FK_CONTENIR foreign key (ID_CATEGORIES)
      references CATEGORIES (ID_CATEGORIES) on delete cascade on update cascade;

alter table CONTENIR add constraint FK_CONTENIR2 foreign key (ID_DOCUMENT)
      references DOCUMENT (ID_DOCUMENT) on delete cascade on update cascade;

alter table DOCUMENT add constraint FK_APPARTIENT foreign key (ID_UTILISATEUR)
      references UTILISATEUR (ID_UTILISATEUR) on delete cascade on update cascade;

alter table DOCUMENT_VERSION add constraint FK_AVOIR foreign key (ID_DOCUMENT)
      references DOCUMENT (ID_DOCUMENT) on delete cascade on update cascade;

alter table USER_PERMISSION add constraint FK_USER_PERMISSION foreign key (ID_PERMISSIONS)
      references PERMISSIONS (ID_PERMISSIONS) on delete cascade on update cascade;

alter table USER_PERMISSION add constraint FK_USER_PERMISSION2 foreign key (ID_UTILISATEUR)
      references UTILISATEUR (ID_UTILISATEUR) on delete cascade on update cascade;
