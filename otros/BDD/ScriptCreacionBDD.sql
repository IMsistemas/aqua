drop index PARROQUIAABARIIO_FK;

drop index BARRIO_PK;

drop table BARRIO;

drop index BARRIOACALLE_FK;

drop index CALLE_PK;

drop table CALLE;

drop index PROVINCIAACANTON_FK;

drop index CANTON_PK;

drop table CANTON;

drop index CARGO_PK;

drop table CARGO;

drop index CLIENTE_PK;

drop table CLIENTE;

drop index LECTURAACXCCLIENTES_FK;

drop index SUMINISTROACXCCLIENTES_FK;

drop index COBROAGUA_PK;

drop table COBROAGUA;

drop table CONFIGURACION;

drop index TARIFAACOSTOTARIFA_FK;

drop table COSTOTARIFA;

drop index SUMINISTROACXCSUMINISTRO_FK;

drop index CLIENTEACUENTASPORCOBRAR_FK;

drop table CUENTAPORCOBRARSUMINISTRO;

drop index CLIENTEACUENTAS_FK;

drop table CUENTASPORPAGARCLIENTES;

drop index CARGOAEMPLEADO_FK;

drop index EMPLEADO_PK;

drop table EMPLEADO;

drop index TARIFAAEXCEDENTES_FK;

drop table EXCEDENTETARIFA;

drop index SUMINISTROALECTURA_FK;

drop index LECTURA_PK;

drop table LECTURA;

drop index CANTONAPARROQUIA_FK;

drop index PARROQUIA_PK;

drop table PARROQUIA;

drop index PRODUCTO_PK;

drop table PRODUCTO;

drop index PROVINCIA_PK;

drop table PROVINCIA;

drop index RUBROFIJO_PK;

drop table RUBROFIJO;

drop index RUBROSFIJOSCUENTA_FK;

drop index RUBROSFIJOSCUENTA2_FK;

drop index RUBROSFIJOSCUENTA_PK;

drop table RUBROSFIJOSCUENTA;

drop index RUBROSVARIABLESCUENTA_FK;

drop index RUBROSVARIABLESCUENTA2_FK;

drop index RUBROSVARIABLESCUENTA_PK;

drop table RUBROSVARIABLESCUENTA;

drop index RUBROVARIABLE_PK;

drop table RUBROVARIABLE;

drop index CLIENTEASOLICITUD_FK;

drop index SOLICITUD_PK;

drop table SOLICITUD;

drop index PRODUCTOASUMINISTRO_FK;

drop index CLIENTEASUMINISTRO_FK;

drop index CALLEASUMINISTRO_FK;

drop index TARIFAASUMINISTRO_FK;

drop index SUMINISTRO_PK;

drop table SUMINISTRO;

drop index TARIFA_PK;

drop table TARIFA;

/*==============================================================*/
/* Table: BARRIO                                                */
/*==============================================================*/
create table BARRIO (
   IDBARRIO             CHAR(8)              not null,
   IDPARROQUIA          CHAR(8)              not null,
   NOMBREBARRIO         CHAR(16)             null,
   constraint PK_BARRIO primary key (IDBARRIO)
);

/*==============================================================*/
/* Index: BARRIO_PK                                             */
/*==============================================================*/
create unique index BARRIO_PK on BARRIO (
IDBARRIO
);

/*==============================================================*/
/* Index: PARROQUIAABARIIO_FK                                   */
/*==============================================================*/
create  index PARROQUIAABARIIO_FK on BARRIO (
IDPARROQUIA
);

/*==============================================================*/
/* Table: CALLE                                                 */
/*==============================================================*/
create table CALLE (
   IDCALLE              CHAR(8)              not null,
   IDBARRIO             CHAR(8)              not null,
   NOMBRECALLE          CHAR(32)             null,
   constraint PK_CALLE primary key (IDCALLE)
);

/*==============================================================*/
/* Index: CALLE_PK                                              */
/*==============================================================*/
create unique index CALLE_PK on CALLE (
IDCALLE
);

/*==============================================================*/
/* Index: BARRIOACALLE_FK                                       */
/*==============================================================*/
create  index BARRIOACALLE_FK on CALLE (
IDBARRIO
);

/*==============================================================*/
/* Table: CANTON                                                */
/*==============================================================*/
create table CANTON (
   IDCANTON             CHAR(8)              not null,
   IDPROVINCIA          CHAR(8)              not null,
   NOMBRECANTON         CHAR(32)             null,
   constraint PK_CANTON primary key (IDCANTON)
);

/*==============================================================*/
/* Index: CANTON_PK                                             */
/*==============================================================*/
create unique index CANTON_PK on CANTON (
IDCANTON
);

/*==============================================================*/
/* Index: PROVINCIAACANTON_FK                                   */
/*==============================================================*/
create  index PROVINCIAACANTON_FK on CANTON (
IDPROVINCIA
);

/*==============================================================*/
/* Table: CARGO                                                 */
/*==============================================================*/
create table CARGO (
   IDCARGO              CHAR(8)              not null,
   NOMBRECARGO          CHAR(16)             null,
   constraint PK_CARGO primary key (IDCARGO)
);

/*==============================================================*/
/* Index: CARGO_PK                                              */
/*==============================================================*/
create unique index CARGO_PK on CARGO (
IDCARGO
);

/*==============================================================*/
/* Table: CLIENTE                                               */
/*==============================================================*/
create table CLIENTE (
   DOCUMENTOIDENTIDAD   VARCHAR(32)          not null,
   FECHAINGRESO         DATE                 null,
   APELLIDO             VARCHAR(32)          null,
   NOMBRE               VARCHAR(32)          null,
   TELEFONOPRINCIPAL    CHAR(16)             null,
   TELEFONOSECUNDARIO   CHAR(16)             null,
   CELULAR              CHAR(16)             null,
   DIRECCION            VARCHAR(32)          null,
   CORREO               VARCHAR(32)          null,
   constraint PK_CLIENTE primary key (DOCUMENTOIDENTIDAD)
);

/*==============================================================*/
/* Index: CLIENTE_PK                                            */
/*==============================================================*/
create unique index CLIENTE_PK on CLIENTE (
DOCUMENTOIDENTIDAD
);

/*==============================================================*/
/* Table: COBROAGUA                                             */
/*==============================================================*/
create table COBROAGUA (
   IDCUENTA             SERIAL                 not null,
   NUMEROSUMINISTRO     INT8                 not null,
   IDLECTURA            INT8                 null,
   FECHAPERIODO         DATE                 null,
   VALORCONSUMO         DECIMAL(9,2)         null,
   VALOREXCEDENTE       DECIMAL(9,2)         null,
   MESESATRASADOS       INT4                 null,
   VALORMESESATRASADOS  DECIMAL(9,2)         null,
   TOTAL                DECIMAL(9,2)         null,
   ESTAPAGADA           BOOL                 not null default false,
   CONSUMOM3            INT8                 null,
   constraint PK_COBROAGUA primary key (IDCUENTA)
);

/*==============================================================*/
/* Index: COBROAGUA_PK                                          */
/*==============================================================*/
create unique index COBROAGUA_PK on COBROAGUA (
IDCUENTA
);

/*==============================================================*/
/* Index: SUMINISTROACXCCLIENTES_FK                             */
/*==============================================================*/
create  index SUMINISTROACXCCLIENTES_FK on COBROAGUA (
NUMEROSUMINISTRO
);

/*==============================================================*/
/* Index: LECTURAACXCCLIENTES_FK                                */
/*==============================================================*/
create  index LECTURAACXCCLIENTES_FK on COBROAGUA (
IDLECTURA
);

/*==============================================================*/
/* Table: CONFIGURACION                                         */
/*==============================================================*/
create table CONFIGURACION (
   PORCENTAJEINTERES    DECIMAL(9,2)         null,
   DIVIDENDOS           INT4                 null,
   AAGUAPOTABLE         DECIMAL(9,2)         null,
   ALCANTARILLADO       DECIMAL(9,2)         null,
   GARANTIAAPERTURACALLE DECIMAL(9,2)         null,
   NOMBREJUNTA          VARCHAR(1512)        null,
   LOGOJUNTA            VARCHAR(1024)        null,
   ESTAENPRODUCCION     BOOL                 null
);

/*==============================================================*/
/* Table: COSTOTARIFA                                           */
/*==============================================================*/
create table COSTOTARIFA (
   IDTARIFA             SERIAL                 not null,
   APARTIRDENM3         INT4                 null,
   VALORCONSUMO         DECIMAL(9,2)         null
);

/*==============================================================*/
/* Index: TARIFAACOSTOTARIFA_FK                                 */
/*==============================================================*/
create  index TARIFAACOSTOTARIFA_FK on COSTOTARIFA (
IDTARIFA
);

/*==============================================================*/
/* Table: CUENTAPORCOBRARSUMINISTRO                             */
/*==============================================================*/
create table CUENTAPORCOBRARSUMINISTRO (
   id 					SERIAL				 NOT NULL,	
   DOCUMENTOIDENTIDAD   VARCHAR(32)          not null,
   NUMEROSUMINISTRO     INT8                 null,
   FECHA         DATE                 null,
   DIVIDENDOS           INT4                 null,
   PAGOTOTAL            DECIMAL(9,2)         null,
   PAGOPORCADADIVIDENDO DECIMAL(9,2)         null
);

/*==============================================================*/
/* Index: CLIENTEACUENTASPORCOBRAR_FK                           */
/*==============================================================*/
create  index CLIENTEACUENTASPORCOBRAR_FK on CUENTAPORCOBRARSUMINISTRO (
DOCUMENTOIDENTIDAD
);

/*==============================================================*/
/* Index: SUMINISTROACXCSUMINISTRO_FK                           */
/*==============================================================*/
create  index SUMINISTROACXCSUMINISTRO_FK on CUENTAPORCOBRARSUMINISTRO (
NUMEROSUMINISTRO
);

/*==============================================================*/
/* Table: CUENTASPORPAGARCLIENTES                               */
/*==============================================================*/
create table CUENTASPORPAGARCLIENTES (
   id 					SERIAL				 NOT NULL, 	
   DOCUMENTOIDENTIDAD   VARCHAR(32)          not null,
   FECHA         		DATE                 null,
   VALOR                DECIMAL(9,2)         null
);

/*==============================================================*/
/* Index: CLIENTEACUENTAS_FK                                    */
/*==============================================================*/
create  index CLIENTEACUENTAS_FK on CUENTASPORPAGARCLIENTES (
DOCUMENTOIDENTIDAD
);

/*==============================================================*/
/* Table: EMPLEADO                                              */
/*==============================================================*/
create table EMPLEADO (
   DOCUMENTOIDENTIDADEMPLEADO VARCHAR(32)    not null,
   IDCARGO              CHAR(8)              not null,
   FECHAINGRESO         DATE                 null,
   APELLIDO             VARCHAR(32)          null,
   NOMBRE               VARCHAR(32)          null,
   TELEFONOPRINCIPAL    CHAR(16)             null,
   TELEFONOSECUNDARIO   CHAR(16)             null,
   CELULAR              CHAR(16)             null,
   DIRECCION            VARCHAR(32)          null,
   CORREO               VARCHAR(32)          null,
   FOTO                 VARCHAR(1024)        null,
   SALARIO              DECIMAL(9,2)         null,
   constraint PK_EMPLEADO primary key (DOCUMENTOIDENTIDADEMPLEADO)
);

/*==============================================================*/
/* Index: EMPLEADO_PK                                           */
/*==============================================================*/
create unique index EMPLEADO_PK on EMPLEADO (
DOCUMENTOIDENTIDADEMPLEADO
);

/*==============================================================*/
/* Index: CARGOAEMPLEADO_FK                                     */
/*==============================================================*/
create  index CARGOAEMPLEADO_FK on EMPLEADO (
IDCARGO
);

/*==============================================================*/
/* Table: EXCEDENTETARIFA                                       */
/*==============================================================*/
create table EXCEDENTETARIFA (
   IDTARIFA             SERIAL               not null,
   DESDENM3             INT4                 null,
   VALORCONSUMO         DECIMAL(9,2)         null
);

/*==============================================================*/
/* Index: TARIFAAEXCEDENTES_FK                                  */
/*==============================================================*/
create  index TARIFAAEXCEDENTES_FK on EXCEDENTETARIFA (
IDTARIFA
);

/*==============================================================*/
/* Table: LECTURA                                               */
/*==============================================================*/
create table LECTURA (
   IDLECTURA            SERIAL               not null,
   NUMEROSUMINISTRO     INT8                 not null,
   FECHALECTURA         DATE                 null,
   LECTURAANTERIOR      INT8                 null,
   LECTURAACTUAL        INT8                 null,
   CONSUMO              INT8                 null,
   OBSERVACION          VARCHAR(512)         null,
   constraint PK_LECTURA primary key (IDLECTURA)
);

/*==============================================================*/
/* Index: LECTURA_PK                                            */
/*==============================================================*/
create unique index LECTURA_PK on LECTURA (
IDLECTURA
);

/*==============================================================*/
/* Index: SUMINISTROALECTURA_FK                                 */
/*==============================================================*/
create  index SUMINISTROALECTURA_FK on LECTURA (
NUMEROSUMINISTRO
);

/*==============================================================*/
/* Table: PARROQUIA                                             */
/*==============================================================*/
create table PARROQUIA (
   IDPARROQUIA          CHAR(8)              not null,
   IDCANTON             CHAR(8)              not null,
   NOMBREPARROQUIA      CHAR(32)             null,
   constraint PK_PARROQUIA primary key (IDPARROQUIA)
);

/*==============================================================*/
/* Index: PARROQUIA_PK                                          */
/*==============================================================*/
create unique index PARROQUIA_PK on PARROQUIA (
IDPARROQUIA
);

/*==============================================================*/
/* Index: CANTONAPARROQUIA_FK                                   */
/*==============================================================*/
create  index CANTONAPARROQUIA_FK on PARROQUIA (
IDCANTON
);

/*==============================================================*/
/* Table: PRODUCTO                                              */
/*==============================================================*/
create table PRODUCTO (
   IDPRODUCTO           CHAR(8)              not null,
   NOMBREPRODUCTO       VARCHAR(32)          null,
   COSTOPRODUCTO        DECIMAL(9,2)         null,
   PRECIOPRODUCTO       DECIMAL(9,2)         null,
   CANTIDADPRODUCTO     INT4                 null,
   constraint PK_PRODUCTO primary key (IDPRODUCTO)
);

/*==============================================================*/
/* Index: PRODUCTO_PK                                           */
/*==============================================================*/
create unique index PRODUCTO_PK on PRODUCTO (
IDPRODUCTO
);

/*==============================================================*/
/* Table: PROVINCIA                                             */
/*==============================================================*/
create table PROVINCIA (
   IDPROVINCIA          CHAR(8)              not null,
   NOMBREPROVINCIA      CHAR(32)             null,
   constraint PK_PROVINCIA primary key (IDPROVINCIA)
);

/*==============================================================*/
/* Index: PROVINCIA_PK                                          */
/*==============================================================*/
create unique index PROVINCIA_PK on PROVINCIA (
IDPROVINCIA
);

/*==============================================================*/
/* Table: RUBROFIJO                                             */
/*==============================================================*/
create table RUBROFIJO (
   IDRUBROFIJO          SERIAL               not null,
   NOMBRERUBROFIJO      VARCHAR(32)          null,
   COSTORUBRO           DECIMAL(9,2)         null,
   constraint PK_RUBROFIJO primary key (IDRUBROFIJO)
);

/*==============================================================*/
/* Index: RUBROFIJO_PK                                          */
/*==============================================================*/
create unique index RUBROFIJO_PK on RUBROFIJO (
IDRUBROFIJO
);

/*==============================================================*/
/* Table: RUBROSFIJOSCUENTA                                     */
/*==============================================================*/
create table RUBROSFIJOSCUENTA (
   IDRUBROFIJO          INT4                 not null,
   IDCUENTA             INT4                 not null,
   COSTORUBRO           DECIMAL(9,2)         not null default 0.00,
   constraint PK_RUBROSFIJOSCUENTA primary key (IDRUBROFIJO, IDCUENTA)
);

/*==============================================================*/
/* Index: RUBROSFIJOSCUENTA_PK                                  */
/*==============================================================*/
create unique index RUBROSFIJOSCUENTA_PK on RUBROSFIJOSCUENTA (
IDRUBROFIJO,
IDCUENTA
);

/*==============================================================*/
/* Index: RUBROSFIJOSCUENTA2_FK                                 */
/*==============================================================*/
create  index RUBROSFIJOSCUENTA2_FK on RUBROSFIJOSCUENTA (
IDCUENTA
);

/*==============================================================*/
/* Index: RUBROSFIJOSCUENTA_FK                                  */
/*==============================================================*/
create  index RUBROSFIJOSCUENTA_FK on RUBROSFIJOSCUENTA (
IDRUBROFIJO
);

/*==============================================================*/
/* Table: RUBROSVARIABLESCUENTA                                 */
/*==============================================================*/
create table RUBROSVARIABLESCUENTA (
   IDRUBROVARIABLE      INT4                 not null,
   IDCUENTA             INT4                 not null,
   COSTORUBRO           DECIMAL(9,2)         not null default 0.00,
   constraint PK_RUBROSVARIABLESCUENTA primary key (IDRUBROVARIABLE, IDCUENTA)
);

/*==============================================================*/
/* Index: RUBROSVARIABLESCUENTA_PK                              */
/*==============================================================*/
create unique index RUBROSVARIABLESCUENTA_PK on RUBROSVARIABLESCUENTA (
IDRUBROVARIABLE,
IDCUENTA
);

/*==============================================================*/
/* Index: RUBROSVARIABLESCUENTA2_FK                             */
/*==============================================================*/
create  index RUBROSVARIABLESCUENTA2_FK on RUBROSVARIABLESCUENTA (
IDCUENTA
);

/*==============================================================*/
/* Index: RUBROSVARIABLESCUENTA_FK                              */
/*==============================================================*/
create  index RUBROSVARIABLESCUENTA_FK on RUBROSVARIABLESCUENTA (
IDRUBROVARIABLE
);

/*==============================================================*/
/* Table: RUBROVARIABLE                                         */
/*==============================================================*/
create table RUBROVARIABLE (
   IDRUBROVARIABLE      SERIAL                 not null,
   NOMBRERUBROVARIABLE  VARCHAR(32)          null,
   constraint PK_RUBROVARIABLE primary key (IDRUBROVARIABLE)
);

/*==============================================================*/
/* Index: RUBROVARIABLE_PK                                      */
/*==============================================================*/
create unique index RUBROVARIABLE_PK on RUBROVARIABLE (
IDRUBROVARIABLE
);

/*==============================================================*/
/* Table: SOLICITUD                                             */
/*==============================================================*/
create table SOLICITUD (
   IDSOLICITUD          SERIAL                 not null,
   DOCUMENTOIDENTIDAD   VARCHAR(32)          not null,
   FECHASOLICITUD       DATE                 null,
   DIRECCIONSUMINISTRO  VARCHAR(32)          null,
   TELEFONOSUMINISTRO   CHAR(256)            null,
   ESTAPROCESADA        BOOL                 null,
   constraint PK_SOLICITUD primary key (IDSOLICITUD)
);

/*==============================================================*/
/* Index: SOLICITUD_PK                                          */
/*==============================================================*/
create unique index SOLICITUD_PK on SOLICITUD (
IDSOLICITUD
);

/*==============================================================*/
/* Index: CLIENTEASOLICITUD_FK                                  */
/*==============================================================*/
create  index CLIENTEASOLICITUD_FK on SOLICITUD (
DOCUMENTOIDENTIDAD
);

/*==============================================================*/
/* Table: SUMINISTRO                                            */
/*==============================================================*/
create table SUMINISTRO (
   NUMEROSUMINISTRO     SERIAL                 not null,
   IDTARIFA             INT4                 not null,
   IDCALLE              CHAR(8)              not null,
   DOCUMENTOIDENTIDAD   VARCHAR(32)          not null,
   IDPRODUCTO           CHAR(8)              not null,
   DIRECCIONSUMNISTRO   VARCHAR(32)          null,
   TELEFONOSUMINISTRO   CHAR(256)            null,
   FECHAINSTALACIONSUMINISTRO DATE                 null,
   constraint PK_SUMINISTRO primary key (NUMEROSUMINISTRO)
);

/*==============================================================*/
/* Index: SUMINISTRO_PK                                         */
/*==============================================================*/
create unique index SUMINISTRO_PK on SUMINISTRO (
NUMEROSUMINISTRO
);

/*==============================================================*/
/* Index: TARIFAASUMINISTRO_FK                                  */
/*==============================================================*/
create  index TARIFAASUMINISTRO_FK on SUMINISTRO (
IDTARIFA
);

/*==============================================================*/
/* Index: CALLEASUMINISTRO_FK                                   */
/*==============================================================*/
create  index CALLEASUMINISTRO_FK on SUMINISTRO (
IDCALLE
);

/*==============================================================*/
/* Index: CLIENTEASUMINISTRO_FK                                 */
/*==============================================================*/
create  index CLIENTEASUMINISTRO_FK on SUMINISTRO (
DOCUMENTOIDENTIDAD
);

/*==============================================================*/
/* Index: PRODUCTOASUMINISTRO_FK                                */
/*==============================================================*/
create  index PRODUCTOASUMINISTRO_FK on SUMINISTRO (
IDPRODUCTO
);

/*==============================================================*/
/* Table: TARIFA                                                */
/*==============================================================*/
create table TARIFA (
   IDTARIFA             SERIAL                 not null,
   NOMBRETARIFA         VARCHAR(32)          null,
   constraint PK_TARIFA primary key (IDTARIFA)
);

/*==============================================================*/
/* Index: TARIFA_PK                                             */
/*==============================================================*/
create unique index TARIFA_PK on TARIFA (
IDTARIFA
);

alter table BARRIO
   add constraint FK_BARRIO_PARROQUIA_PARROQUI foreign key (IDPARROQUIA)
      references PARROQUIA (IDPARROQUIA)
      on delete restrict on update restrict;

alter table CALLE
   add constraint FK_CALLE_BARRIOACA_BARRIO foreign key (IDBARRIO)
      references BARRIO (IDBARRIO)
      on delete restrict on update restrict;

alter table CANTON
   add constraint FK_CANTON_PROVINCIA_PROVINCI foreign key (IDPROVINCIA)
      references PROVINCIA (IDPROVINCIA)
      on delete restrict on update restrict;

alter table COBROAGUA
   add constraint FK_COBROAGU_LECTURAAC_LECTURA foreign key (IDLECTURA)
      references LECTURA (IDLECTURA)
      on delete restrict on update restrict;

alter table COBROAGUA
   add constraint FK_COBROAGU_SUMINISTR_SUMINIST foreign key (NUMEROSUMINISTRO)
      references SUMINISTRO (NUMEROSUMINISTRO)
      on delete restrict on update restrict;

alter table COSTOTARIFA
   add constraint FK_COSTOTAR_TARIFAACO_TARIFA foreign key (IDTARIFA)
      references TARIFA (IDTARIFA)
      on delete restrict on update restrict;

alter table CUENTAPORCOBRARSUMINISTRO
   add constraint FK_CUENTAPO_CLIENTEAC_CLIENTE foreign key (DOCUMENTOIDENTIDAD)
      references CLIENTE (DOCUMENTOIDENTIDAD)
      on delete restrict on update restrict;

alter table CUENTAPORCOBRARSUMINISTRO
   add constraint FK_CUENTAPO_SUMINISTR_SUMINIST foreign key (NUMEROSUMINISTRO)
      references SUMINISTRO (NUMEROSUMINISTRO)
      on delete restrict on update restrict;

alter table CUENTASPORPAGARCLIENTES
   add constraint FK_CUENTASP_CLIENTEAC_CLIENTE foreign key (DOCUMENTOIDENTIDAD)
      references CLIENTE (DOCUMENTOIDENTIDAD)
      on delete restrict on update restrict;

alter table EMPLEADO
   add constraint FK_EMPLEADO_CARGOAEMP_CARGO foreign key (IDCARGO)
      references CARGO (IDCARGO)
      on delete restrict on update restrict;

alter table EXCEDENTETARIFA
   add constraint FK_EXCEDENT_TARIFAAEX_TARIFA foreign key (IDTARIFA)
      references TARIFA (IDTARIFA)
      on delete restrict on update restrict;

alter table LECTURA
   add constraint FK_LECTURA_SUMINISTR_SUMINIST foreign key (NUMEROSUMINISTRO)
      references SUMINISTRO (NUMEROSUMINISTRO)
      on delete restrict on update restrict;

alter table PARROQUIA
   add constraint FK_PARROQUI_CANTONAPA_CANTON foreign key (IDCANTON)
      references CANTON (IDCANTON)
      on delete restrict on update restrict;

alter table RUBROSFIJOSCUENTA
   add constraint FK_RUBROSFI_RUBROSFIJ_RUBROFIJ foreign key (IDRUBROFIJO)
      references RUBROFIJO (IDRUBROFIJO)
      on delete restrict on update restrict;

alter table RUBROSFIJOSCUENTA
   add constraint FK_RUBROSFI_RUBROSFIJ_COBROAGU foreign key (IDCUENTA)
      references COBROAGUA (IDCUENTA)
      on delete restrict on update restrict;

alter table RUBROSVARIABLESCUENTA
   add constraint FK_RUBROSVA_RUBROSVAR_RUBROVAR foreign key (IDRUBROVARIABLE)
      references RUBROVARIABLE (IDRUBROVARIABLE)
      on delete restrict on update restrict;

alter table RUBROSVARIABLESCUENTA
   add constraint FK_RUBROSVA_RUBROSVAR_COBROAGU foreign key (IDCUENTA)
      references COBROAGUA (IDCUENTA)
      on delete restrict on update restrict;

alter table SOLICITUD
   add constraint FK_SOLICITU_CLIENTEAS_CLIENTE foreign key (DOCUMENTOIDENTIDAD)
      references CLIENTE (DOCUMENTOIDENTIDAD)
      on delete restrict on update restrict;

alter table SUMINISTRO
   add constraint FK_SUMINIST_CALLEASUM_CALLE foreign key (IDCALLE)
      references CALLE (IDCALLE)
      on delete restrict on update restrict;

alter table SUMINISTRO
   add constraint FK_SUMINIST_CLIENTEAS_CLIENTE foreign key (DOCUMENTOIDENTIDAD)
      references CLIENTE (DOCUMENTOIDENTIDAD)
      on delete restrict on update restrict;

alter table SUMINISTRO
   add constraint FK_SUMINIST_PRODUCTOA_PRODUCTO foreign key (IDPRODUCTO)
      references PRODUCTO (IDPRODUCTO)
      on delete restrict on update restrict;

alter table SUMINISTRO
   add constraint FK_SUMINIST_TARIFAASU_TARIFA foreign key (IDTARIFA)
      references TARIFA (IDTARIFA)
      on delete restrict on update restrict;
