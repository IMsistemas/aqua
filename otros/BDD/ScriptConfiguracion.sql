/*------CONFIGURACION AYORA-----------*/


/*==============================================================*/
/* RUBROS CONFIGURACION                                         */
/*==============================================================*/

insert into configuracion (porcentajeinteres,dividendos,aaguapotable,alcantarillado,garantiaaperturacalle,nombrejunta,estaEnProduccion) values
(0.10,6,120,120,50,'Ayora',false);


/*==============================================================*/
/* SECTORES                                                     */
/*==============================================================*/

insert into provincia values('PRO00001','Pichincha');
insert into canton values ('CAN00001','PRO00001','Cayambe');
insert into parroquia values ('PAR00001','CAN00001','Ayora');

/*==============================================================*/
/* MEDIDORES                                                    */
/*==============================================================*/

insert into producto values('PRO00001','EL MEDIDOR',35.00,45.00,100);

/*==============================================================*/
/* TARIFAS                                                      */
/*==============================================================*/

/*-------------------Ingreso tarifas------------------------*/	  
insert into tarifa values(1,'Domestica');
insert into tarifa values(2,'Industrial');
insert into tarifa values(3,'Comercial');



/*Ingreso costos tarifa domestica*/
insert into costotarifa values(1,0,1);
insert into costotarifa values(1,31,2);
insert into costotarifa values(1,101,4);

/*Ingreso costos tarifa Industrial*/
insert into costotarifa values(2,0,4);

/*Ingreso costos tarifa Comercial*/
insert into costotarifa values(3,0,2);

/*Ingreso excedente tarifa domestica*/
insert into excedentetarifa values(1,16,0.1);
insert into excedentetarifa values(1,31,0.2);
insert into excedentetarifa values(1,101,0.4);
insert into excedentetarifa values(1,16,0.1);

/*Ingreso excedente tarifa industrial*/
insert into excedentetarifa values(2,16,0.4);

/*Ingreso excedente tarifa comercial*/
insert into excedentetarifa values(3,16,0.2);

/*==============================================================*/
/* RUBROS FIJOS                                                 */
/*==============================================================*/

insert into rubrofijo (nombrerubrofijo,costorubro) values('Alcantarillado',0.3);
insert into rubrofijo (nombrerubrofijo,costorubro) values('Desechos Solidos',0.2);

/*==============================================================*/
/* RUBROS VARIABLES                                             */
/*==============================================================*/

insert into rubrovariable (nombrerubrovariable) values('Multa Mingas');
insert into rubrovariable (nombrerubrovariable) values('Multa Asamblea');