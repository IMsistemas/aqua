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

insert into barrio values ('BAR00001','PAR00001','Imbabura');
insert into calle values ('CAL00001','BAR00001','Imbabura');

insert into barrio values ('BAR00002','PAR00001','Galapagos');
insert into calle values ('CAL00002','BAR00002','Galapagos y Carchi');

insert into barrio values ('BAR00003','PAR00001','Ayora Nuevo');
insert into calle values ('CAL00003','BAR00003','Neptali Bonifaz');

insert into barrio values ('BAR00004','PAR00001','Esmeraldas');
insert into calle values ('CAL00004','BAR00004','Esmeraldas');

insert into barrio values ('BAR00005','PAR00001','Oriente');
insert into calle values ('CAL00005','BAR00005','Guayas');

insert into barrio values ('BAR00006','PAR00001','Central ');
insert into calle values ('CAL00006','BAR00006','Pichincha');

insert into barrio values ('BAR00007','PAR00001','Residencial');
insert into calle values ('CAL00007','BAR00007','Pichincha ');

insert into barrio values ('BAR00008','PAR00001','Segundo Duran');
insert into calle values ('CAL00008','BAR00008','Segundo Duran y UNOPAC');

insert into barrio values ('BAR00009','PAR00001','Los Lotes');
insert into calle values ('CAL00009','BAR00009','Tungurahua');
insert into calle values ('CAL00010','BAR00009','Latacunga');
insert into calle values ('CAL00011','BAR00009','Otavalo');
insert into calle values ('CAL00012','BAR00009','Bolivar');
insert into calle values ('CAL00013','BAR00009','Cotopaxi y Sucumbios');


/*
Barrio Imbabura 
Calle : Imbabura 

Barrio Galapagos
Calles: Galapagos y Carchi

Barrio Ayora Nuevo
Calle : Neptali Bonifaz

Barrio Esmeraldas 
Calle : Esmeraldas 

Barrio Oriente 
Calle: Guayas 

Barrio Central 
Calle Pichincha 

Barrio Residencial
Calle Pichincha 

Barrio Segundo Duran 
Calles: Segundo Duran y UNOPAC

Barrio Los Lotes
Calles: Latacunga, Tungurahua, Otavalo, Bolivar, Cotopaxi y Sucumbios 
*/

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