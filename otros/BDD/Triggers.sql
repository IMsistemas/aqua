/*TRIGGER CALCULA EL VALOR TOTAL EN LA TABLA COBRO AGUA*/
CREATE OR REPLACE FUNCTION calcularTotalPorFila() 
	RETURNS TRIGGER AS $triggerCalcularConsumoEnM3$
	DECLARE
	BEGIN
		IF (TG_OP = 'INSERT' OR TG_OP = 'UPDATE') THEN
			NEW.consumo := CAST(CAST(NEW.lecturaactual as INT)-CAST(NEW.lecturaanterior as int)AS TEXT);
			RETURN NEW;
		ELSEIF (TG_OP = 'DELETE') THEN
			RETURN NULL;
		END IF;
	END;
	
$triggerCalcularTotal$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER triggerCalcularConsumoEnM3 
	BEFORE INSERT OR UPDATE 
	ON cobroagua FOR EACH ROW 
	EXECUTE PROCEDURE calcularTotalPorFila(); 
	