
/*-------Ingreso Sectorizacion------*/
insert into provincia values('PRO00001','Pichincha');
insert into canton values ('CAN00001','PRO00001','Quito');
insert into parroquia values ('PAR00001','CAN00001','Chillogallo');
insert into parroquia values ('PAR00002','CAN00001','Villaflora');
insert into barrio values ('BAR00001','PAR00001','Solanda');
insert into barrio values ('BAR00002','PAR00001','Chillogallo');
insert into barrio values ('BAR00003','PAR00002','Magdalena');
insert into calle values ('CAL00001','BAR00001','Transversal 1');
insert into calle values ('CAL00002','BAR00001','Transversal 2');
insert into calle values ('CAL00003','BAR00002','Transversal 3');
insert into calle values ('CAL00004','BAR00002','Transversal 4');
insert into calle values ('CAL00005','BAR00003','Transversal 5');

/*-------Ingreso Rubros---------*/
/*Rubros Fijos*/
insert into rubrofijo values (1,'Medio Ambiente',0.28);

/*Rubros Variables*/
insert into rubrovariable values (1,'Multa Asamblea');
insert into rubrovariable values (2,'Multa Mingas');
insert into rubrovariable values (3,'Otros Valores');
	 

/*-------------------Ingreso tarifas------------------------*/	  
insert into tarifa values(1,'Domestica');
insert into tarifa values(2,'Industrial');
insert into tarifa values(3,'Comercial');
insert into tarifa values(4,'Alcantarillado');
insert into tarifa values(5,'Desechos Solidos');

/*----------------Ingreso Costo Tarifas----------------------*/
/*Ingreso costos tarifa domestica*/
insert into costotarifa values(1,0,1);
insert into costotarifa values(1,31,2);
insert into costotarifa values(1,101,4);

/*Ingreso costos tarifa Industrial*/
insert into costotarifa values(2,0,4);

/*Ingreso costos tarifa Comercial*/
insert into costotarifa values(3,0,2);

/*Ingreso costos tarifa alcantarillado*/
insert into costotarifa values(4,0,0.3);

/*Ingreso costos tarifa desechos solidos*/
insert into costotarifa values(5,0,0.2);

/*----------------Ingreso excedentes tarifas-------------------*/
/*Ingreso excedente tarifa domestica*/
insert into excedentetarifa values(1,16,0.1);
insert into excedentetarifa values(1,31,0.2);
insert into excedentetarifa values(1,101,0.4);
insert into excedentetarifa values(1,16,0.1);

/*Ingreso excedente tarifa industrial*/
insert into excedentetarifa values(2,16,0.4);

/*Ingreso excedente tarifa comercial*/
insert into excedentetarifa values(3,16,0.2);

/*------Ingreso Cliente---------*/
insert into cliente values('1720276177',current_date,'RIOS CRIOLLO','CHRISTIAN MANUEL','2040186','20289062','0351446997','La calle de las estrellas','ejemplo@gmail.com');
insert into cliente values('1720276178',current_date,'VINUEZA CRIOLLO','LUIS MANUEL','2040186','20289062','0351446997','Av siempre viva','ejemplo@gmail.com');
insert into cliente values('1720276179',current_date,'DETAL','FULANO','2040146','20889062','0351446997','Av. Siempe alegre','ejemplo@gmail.com');

/*------Ingreso de medidores-----*/
insert into producto values('PRO00001','EL MEDIDOR',35.00,45.00,3);

/*------Ingreso de Suministros---*/
insert into suministro values (1,1,'CAL00001','1720276177','PRO00001','Av. del suministro 1','2157960');
insert into suministro values (2,2,'CAL00002','1720276178','PRO00001','Av. del suministro 2','2157961');
insert into suministro values (3,3,'CAL00003','1720276179','PRO00001','Av. del suministro 3','2157962');

/*-----Ingreso de lectura---------*/
insert into lectura values(1,1);

/*-----Ingreso de cobros--------*/
insert into cobroagua values(1,1,1);
insert into cobroagua values(2,2,1);
insert into cobroagua values(3,3,1)

