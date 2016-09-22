drop table if exists barrio;

drop table if exists calle;

drop table if exists canton;

drop table if exists cargo;

drop table if exists cliente;

drop table if exists cobroagua;

drop table if exists configuracion;

drop table if exists costotarifa;

drop table if exists cuentaporcobrarsuministro;

drop table if exists cuentasporpagarclientes;

drop table if exists empleado;

drop table if exists excedentetarifa;

drop table if exists lectura;

drop table if exists parroquia;

drop table if exists producto;

drop table if exists provincia;

drop table if exists rubrofijo;

drop table if exists rubrosfijoscuenta;

drop table if exists rubrosvariablescuenta;

drop table if exists rubrovariable;

drop table if exists solicitud;

drop table if exists suministro;

drop table if exists tarifa;

/*==============================================================*/
/* table: barrio                                                */
/*==============================================================*/
create table barrio
(
   idbarrio             char(8) not null,
   idparroquia          char(8) not null,
   nombrebarrio         char(16),
   primary key (idbarrio)
);

/*==============================================================*/
/* table: calle                                                 */
/*==============================================================*/
create table calle
(
   idcalle              char(8) not null,
   idbarrio             char(8) not null,
   nombrecalle          char(32),
   primary key (idcalle)
);

/*==============================================================*/
/* table: canton                                                */
/*==============================================================*/
create table canton
(
   idcanton             char(8) not null,
   idprovincia          char(8) not null,
   nombrecanton         char(32),
   primary key (idcanton)
);

/*==============================================================*/
/* table: cargo                                                 */
/*==============================================================*/
create table cargo
(
   idcargo              char(8) not null,
   nombrecargo          char(16),
   primary key (idcargo)
);

/*==============================================================*/
/* table: cliente                                               */
/*==============================================================*/
create table cliente
(
   documentoidentidad   varchar(32) not null,
   fechaingreso         date,
   apellido             varchar(32),
   nombre               varchar(32),
   telefonoprincipal    char(16),
   telefonosecundario   char(16),
   celular              char(16),
   direccion            varchar(32),
   correo               varchar(32),
   primary key (documentoidentidad)
);

/*==============================================================*/
/* table: cobroagua                                             */
/*==============================================================*/
create table cobroagua
(
   idcuenta             int not null auto_increment,
   numerosuministro     bigint not null,
   idlectura            bigint null,
   fechaperiodo         date,
   valorconsumo         decimal(9,2),
   valorexcedente       decimal(9,2),
   mesesatrasados       int,
   valormesesatrasados  decimal(9,2),
   total                decimal(9,2),
   estapagada           boolean,
   consumom3            bigint,
   primary key (idcuenta)
);

/*==============================================================*/
/* table: configuracion                                         */
/*==============================================================*/
create table configuracion
(
   porcentajeinteres    decimal(9,2),
   dividendos           int,
   aaguapotable         decimal(9,2),
   alcantarillado       decimal(9,2),
   garantiaaperturacalle decimal(9,2),
   nombrejunta          varchar(1512),
   logojunta            varchar(1024),
   estaenproduccion     boolean
);

/*==============================================================*/
/* table: costotarifa                                           */
/*==============================================================*/
create table costotarifa
(
   idtarifa             int not null,
   apartirdenm3         int,
   valorconsumo         decimal(9,2)
);

/*==============================================================*/
/* table: cuentaporcobrarsuministro                             */
/*==============================================================*/
create table cuentaporcobrarsuministro
(
   cuotainicial         decimal(9,2),
   dividendos           int,
   pagototal            decimal(9,2),
   pagoporcadadividendo decimal(9,2),
   idcxc                bigint not null auto_increment,
   documentoidentidad   varchar(32) not null,
   numerosuministro     bigint not null,
   idsolicitud          bigint not null,
   fecha                date,
   primary key (idcxc)
);

/*==============================================================*/
/* table: cuentasporpagarclientes                               */
/*==============================================================*/
create table cuentasporpagarclientes
(
   valor                decimal(9,2),
   idcxp                bigint not null auto_increment,
   documentoidentidad   varchar(32) not null,
   fecha                date,
   primary key (idcxp)
);

/*==============================================================*/
/* table: empleado                                              */
/*==============================================================*/
create table empleado
(
   documentoidentidadempleado varchar(32) not null,
   idcargo              char(8) not null,
   fechaingreso         date,
   apellido             varchar(32),
   nombre               varchar(32),
   telefonoprincipal    char(16),
   telefonosecundario   char(16),
   celular              char(16),
   direccion            varchar(32),
   correo               varchar(32),
   foto                 varchar(1024),
   salario              decimal(9,2),
   primary key (documentoidentidadempleado)
);

/*==============================================================*/
/* table: excedentetarifa                                       */
/*==============================================================*/
create table excedentetarifa
(
   idtarifa             int not null,
   desdenm3             int,
   valorconsumo         decimal(9,2)
);

/*==============================================================*/
/* table: lectura                                               */
/*==============================================================*/
create table lectura
(
   idlectura            bigint not null auto_increment,
   numerosuministro     bigint not null,
   fechalectura         date,
   lecturaanterior      bigint,
   lecturaactual        bigint,
   consumo              bigint,
   observacion          varchar(512),
   primary key (idlectura)
);

/*==============================================================*/
/* table: parroquia                                             */
/*==============================================================*/
create table parroquia
(
   idparroquia          char(8) not null,
   idcanton             char(8) not null,
   nombreparroquia      char(32),
   primary key (idparroquia)
);

/*==============================================================*/
/* table: producto                                              */
/*==============================================================*/
create table producto
(
   idproducto           char(8) not null,
   nombreproducto       varchar(32),
   costoproducto        decimal(9,2),
   precioproducto       decimal(9,2),
   cantidadproducto     int,
   primary key (idproducto)
);

/*==============================================================*/
/* table: provincia                                             */
/*==============================================================*/
create table provincia
(
   idprovincia          char(8) not null,
   nombreprovincia      char(32),
   primary key (idprovincia)
);

/*==============================================================*/
/* table: rubrofijo                                             */
/*==============================================================*/
create table rubrofijo
(
   idrubrofijo          int not null auto_increment,
   nombrerubrofijo      varchar(32),
   costorubro           decimal(9,2),
   primary key (idrubrofijo)
);

/*==============================================================*/
/* table: rubrosfijoscuenta                                     */
/*==============================================================*/
create table rubrosfijoscuenta
(
   idrubrofijo          int not null,
   idcuenta             int not null,
   costorubro           decimal(9,2),
   primary key (idrubrofijo, idcuenta)
);

/*==============================================================*/
/* table: rubrosvariablescuenta                                 */
/*==============================================================*/
create table rubrosvariablescuenta
(
   idrubrovariable      int not null,
   idcuenta             int not null,
   costorubro           decimal(9,2),
   primary key (idrubrovariable, idcuenta)
);

/*==============================================================*/
/* table: rubrovariable                                         */
/*==============================================================*/
create table rubrovariable
(
   idrubrovariable      int not null auto_increment,
   nombrerubrovariable  varchar(32),
   primary key (idrubrovariable)
);

/*==============================================================*/
/* table: solicitud                                             */
/*==============================================================*/
create table solicitud
(
   idsolicitud          bigint not null auto_increment,
   documentoidentidad   varchar(32) not null,
   fechasolicitud       date,
   direccionsuministro  varchar(32),
   telefonosuministro   char(16),
   estaprocesada        boolean,
   primary key (idsolicitud)
);

/*==============================================================*/
/* table: suministro                                            */
/*==============================================================*/
create table suministro
(
   numerosuministro     bigint not null auto_increment,
   idtarifa             int not null,
   idcalle              char(8) not null,
   documentoidentidad   varchar(32) not null,
   idproducto           varchar(8) not null,
   direccionsuministro  varchar(32),
   telefonosuministro   char(16),
   fechainstalacionsuministro date,
   primary key (numerosuministro)
);

/*==============================================================*/
/* table: tarifa                                                */
/*==============================================================*/
create table tarifa
(
   idtarifa             int not null auto_increment,
   nombretarifa         varchar(32),
   primary key (idtarifa)
);

alter table barrio add constraint fk_parroquiaabariio foreign key (idparroquia)
      references parroquia (idparroquia) on delete restrict on update restrict;

alter table calle add constraint fk_barrioacalle foreign key (idbarrio)
      references barrio (idbarrio) on delete restrict on update restrict;

alter table canton add constraint fk_provinciaacanton foreign key (idprovincia)
      references provincia (idprovincia) on delete restrict on update restrict;

alter table cobroagua add constraint fk_lecturaacxcclientes foreign key (idlectura)
      references lectura (idlectura) on delete restrict on update restrict;

alter table cobroagua add constraint fk_suministroacxcclientes foreign key (numerosuministro)
      references suministro (numerosuministro) on delete restrict on update restrict;

alter table costotarifa add constraint fk_tarifaacostotarifa foreign key (idtarifa)
      references tarifa (idtarifa) on delete restrict on update restrict;

alter table cuentaporcobrarsuministro add constraint fk_clienteacuentasporcobrar foreign key (documentoidentidad)
      references cliente (documentoidentidad) on delete restrict on update restrict;

alter table cuentaporcobrarsuministro add constraint fk_solicitudacuentaporcobrar foreign key (idsolicitud)
      references solicitud (idsolicitud) on delete restrict on update restrict;

alter table cuentaporcobrarsuministro add constraint fk_suministroacxcsuministro foreign key (numerosuministro)
      references suministro (numerosuministro) on delete restrict on update restrict;

alter table cuentasporpagarclientes add constraint fk_clienteacuentas foreign key (documentoidentidad)
      references cliente (documentoidentidad) on delete restrict on update restrict;

alter table empleado add constraint fk_cargoaempleado foreign key (idcargo)
      references cargo (idcargo) on delete restrict on update restrict;

alter table excedentetarifa add constraint fk_tarifaaexcedentes foreign key (idtarifa)
      references tarifa (idtarifa) on delete restrict on update restrict;

alter table lectura add constraint fk_suministroalectura foreign key (numerosuministro)
      references suministro (numerosuministro) on delete restrict on update restrict;

alter table parroquia add constraint fk_cantonaparroquia foreign key (idcanton)
      references canton (idcanton) on delete restrict on update restrict;

alter table rubrosfijoscuenta add constraint fk_rubrosfijoscuenta foreign key (idrubrofijo)
      references rubrofijo (idrubrofijo) on delete restrict on update restrict;

alter table rubrosfijoscuenta add constraint fk_rubrosfijoscuenta2 foreign key (idcuenta)
      references cobroagua (idcuenta) on delete restrict on update restrict;

alter table rubrosvariablescuenta add constraint fk_rubrosvariablescuenta foreign key (idrubrovariable)
      references rubrovariable (idrubrovariable) on delete restrict on update restrict;

alter table rubrosvariablescuenta add constraint fk_rubrosvariablescuenta2 foreign key (idcuenta)
      references cobroagua (idcuenta) on delete restrict on update restrict;

alter table solicitud add constraint fk_clienteasolicitud foreign key (documentoidentidad)
      references cliente (documentoidentidad) on delete restrict on update restrict;

alter table suministro add constraint fk_calleasuministro foreign key (idcalle)
      references calle (idcalle) on delete restrict on update restrict;

alter table suministro add constraint fk_clienteasuministro foreign key (documentoidentidad)
      references cliente (documentoidentidad) on delete restrict on update restrict;

alter table suministro add constraint fk_productoasuministro foreign key (idproducto)
      references producto (idproducto) on delete restrict on update restrict;

alter table suministro add constraint fk_tarifaasuministro foreign key (idtarifa)
      references tarifa (idtarifa) on delete restrict on update restrict;
