<?php
  use app\components\UtilitiesHelper;
?>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    * { color: #000000 !important; background-color: #ffffff !important; }
    body { font-family: Times; font-size: 12px; }
  </style>
</head>
<body>
<table border="0" width="100%" style="font-family: Times; font-size: 12px;">
  <tr>
  	<td style="font-size: 14px; text-align: center;"><strong>CONTRATO DE ADHESIÓN A FREELANCE SOCIEDAD COOPERATIVA MADRILEÑA</strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>
  		<table border="0" width="100%">
  			<tr>
  				<td width="100">
  					<strong>DE UNA PARTE.</strong>
  				</td>
  				<td>&nbsp;</td>
  			</tr>
  			<tr>
  				<td>&nbsp;</td>
  				<td>
  					<table width="100%">
					<tr>
  							<td width="105" style="text-align: right;"><strong>Número de socio:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_numero;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Nombre:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_nombre;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Apellidos:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_apellido;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>DNI-NIE:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_numdocide;?></td>
  						</tr>
						<tr>
  							<td width="105" style="text-align: right;"><strong>Fecha de Nacimiento:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo UtilitiesHelper::db2date($model->soc_fecnacimiento);?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>N° Seguridad Social:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_numsegsocial;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Domicilio:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_direccion;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>CP:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_codpostal;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Población:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_poblacion;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Provincia:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php if(isset($model->provincia->prv_nombre)) echo $model->province->prv_nombre;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Teléfono:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_telmovil;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Email:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_email;?></td>
  						</tr>
							<tr>
  							<td width="105" style="text-align: right;"><strong>Cuenta Iban (24 dígitos):</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_ctabancaria;?></td>
  						</tr>
  						<tr>
  							<td width="105" style="text-align: right;"><strong>Profesión:</strong></td>
  							<td width="368" height="15" style="border: 1;"><?php echo $model->soc_ocupacion;?></td>
  						</tr>
  					</table>
  				</td>
  			</tr>
  			<tr>
  				<td>&nbsp;</td>
  				<td width="485">En adelante EL NUEVO SOCIO, que actúa en su propio nombre y representación.</td>
  			</tr>
  		</table>
  	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>
  		<table border="0" width="100%">
  			<tr>
  				<td width="100" valign="top">
  					<strong>Y DE OTRA.</strong>
  				</td>
  				<td width="485" style="text-align: justify;">Javier Moro Talón, DNI 43151278n, dirección profesional en Avenida Monasterio  de  Silos   13   Local   28049   Madrid,   email   info@freelance.es, Tel y Fax 91 383 96 52, actuando como representante legal y en nombre de FREELANCE   SOCIEDAD   COOPERATIVA   MADRILEÑA,   en   lo   sucesivo FREELANCE SCM entidad domiciliada en Avenida Monasterio de Silos 13 Local 28049 Madrid y en posesión de C.I.F: F84278266
          </td>
  			</tr>
  		</table>
  	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>Ambas partes se reconocen la capacidad jurídica y de obrar necesaria para obligarse, y a tal efecto,</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td style="text-align: center; font-size: 14px;"><strong><u>EXPONEN</u></strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;">I. Que FREELANCE SCM está constituida como Sociedad Cooperativa de Trabajo Madrileña de acuerdo a la Ley 4/1999, de 30 de marzo, de Cooperativas de trabajo de la Comunidad de Madrid, e inscrita en el Registro de Cooperativas de esta Comunidad Autónoma. Tomo 34, folio 4269, nº inscripción 28/CM4269.<br /><br />Se enmarca dentro de las sociedades cooperativas de trabajo asociado, constituida por personas que se asocian, en régimen de libre adhesión y baja voluntaria, con estructura y funcionamiento democrático, para la realización de servicios profesionales del mundo audiovisual, la comunicación, el periodismo, la fotografía y el diseño, que tienen una actividad de  carácter  eventual  y  un volumen de facturación inferior a 18.000 euros anuales.<br /><br />Su finalidad es satisfacer las necesidades y aspiraciones económicas y sociales de sus socios, ocuparse de la gestión administrativa, orientar y facilitar el acceso al mundo laboral de  sus asociados que comienzan una actividad o no pueden acceder económicamente al Régimen Especial de Autónomos.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;">II.	Que EL NUEVO SOCIO conoce los Estatutos de FRELANCE SCM, ha solicitado el ingreso en esta y cumple con los requisitos establecidos en art. 6. Realiza sus trabajos por cuenta propia, es decir, bajo su propia dirección, utilizando sus propios medios, con total independencia, rigiéndose en todo momento por sus propios criterios en lo que a jornada, jerarquía y actividad profesional se refiere.

    El socio declara que la relación establecida con sus clientes es una relación por cuenta propia y de carácter eventual, enmarcada dentro de las relaciones de trabajador autónomo en cuanto al marco laboral y contractual. Será responsabilidad del socio trabajador la obtención de clientes, la ejecución de los trabajos, no responsabilizándose en ningún caso la Cooperativa por los daños causados a terceros ni de los posibles impagos. Se establece por tanto una relación mercantil y no laboral con sus clientes. Igualmente declara que el trabajo que se realiza no tiene la consideración de subcontrata, por lo que los servicios que realiza se dirigen al cliente final.
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;">III.	El Nuevo socio tiene nacionalidad española, la condición de ciudadano comunitario o de extranjero con permiso de trabajo en vigor, DNI o NIE y Nº de la Seguridad Social y que no está afecto a incompatibilidades de personal al servicio de las Administraciones Públicas o cualquier otra incompatibilidad que le impida trabajar como socio de FREELANCE SCM.<br /><br />

    Que se establece una relación societaria entre el socio de trabajo y la cooperativa que se regirá por las siguientes</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td style="text-align: center; font-size: 14px;"><strong><u>ESTIPULACIONES</u></strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>1	Normativa Aplicable:</strong> Las partes se someten a las condiciones establecidas en los estatutos de FREELANCE SCM, a la Ley 4/1999, de 30 de marzo, de Cooperativas de trabajo de la Comunidad de Madrid, a la Ley 27/1999, de 16 de julio, de Cooperativas y al resto de normativa a la normativa de trabajadores por cuenta propia, legislación mercantil y a la Ley 20/2007, de 11 de julio, del Estatuto del trabajo autónomo.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>2. Alta de Socio: </strong>FREELANCE SCM admite como socio trabajador de la Cooperativa a EL NUEVO SOCIO con un período de prueba de 18 meses, pasado el cual se convertirá en socio de pleno derecho.<br /><br />Entrega en este acto la cantidad de 100 euros, de los cuales 80 euros corresponden a su participación en FREELANCE SCM. Se le asignan los títulos números <?php echo $model->soc_participacion_desde;?> a <?php echo $model->soc_participacion_hasta;?> que le confieren la condición de socio. Sirve este documento como título nominativo al que hace mención el artículo 17 de los estatutos. Los 20 euros restantes se entregan como gastos de inscripción.<br /><br />

    La admisión del nuevo socio como miembro de la sociedad cooperativa no inicia el vínculo societario a efectos laborales hasta que se produce el alta en la seguridad social.
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>3. Vínculo societario:</strong> Se considera que el socio está vinculado a la sociedad durante los períodos de actividad en los que está dado de alta en la Seguridad Social. En los períodos en los que no se encuentre en situación de alta en seguridad social no se considerará socio trabajador a efectos laborales. En caso de inspección de trabajo, accidente laboral o cualquier otra situación que se hubiera producido fuera de los días de alta en la seguridad social solicitados por el socio, será responsabilidad personal del socio, eximiendo a esta sociedad cooperativa.
    La relación que se crea entre los socios y la cooperativa no es ni laboral ni del Régimen de Trabajadores Autónomos, sino societaria, por lo que las normas laborales, sustantivas y procesales le serán de aplicación en la medida en que estén expresa y específicamente contempladas en la normativa reguladora del Régimen jurídico de la relación corporativa. (STS 23 de octubre de 2009, Sección 1ª).
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>4. Seguridad Social: </strong> La Cooperativa ha optado a efectos de Seguridad Social de sus socios trabajadores por el régimen especial para socios de cooperativas como asimilados a trabajadores por cuenta ajena, integrado en el Régimen General, de acuerdo a RD-L. 1/1994 de Ley General de la Seguridad Social. Disposición adicional cuarta.
    Será obligación del socio informar, al menos un día antes hasta las seis de la tarde por correo electrónico, de las fechas que precisa de alta y baja para que desde la administración de FREELANCE SCM pueda tramitar en su caso, los correspondientes procesos de afiliación, alta y baja, en la Seguridad Social. El socio deberá solicitar el alta para los días que duren los servicios a realizar y al menos un día por cada 220 euros de facturación. Dicha cantidad se determinará anualmente conforme a los límites establecidos por la Orden Ministerial de Cotización vigente, realizando la cotización correspondiente conforme al Reglamento de Cotización. Una vez finalizado el trabajo y gestionada la correspondiente baja en la seguridad social se considerará dado por finalizado el vínculo societario a efectos laborales.<br /><br /></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>5.Facturación: </strong> Por tratarse una sociedad de trabajo, la facturación a clientes se limitará a servicios profesionales y gastos suplidos (en el caso de que se adjunten los correspondientes comprobantes de estos últimos). Las dietas y otros gastos justificados, se ajustarán a lo establecido en el artículo 23 del Real Decreto 2064/1995, de 22 de diciembre, por el que se aprueba el Reglamento General sobre Cotización y Liquidación de otros derechos de la Seguridad Social.<br /><br />

    Las dietas y otros gastos no justificados con facturas o tickets serán considerados como servicios profesionales sujetos a IVA e IRPF. No se podrán facturar a través de Freelance SCM otros conceptos diferentes a los anteriores como pueden ser venta de productos, instalaciones técnicas, alquileres, licencias, software, derechos, etc. <br /><br />   

    Los trabajos se facturarán por el importe que nos indique el socio más el IVA que corresponda. En caso de estar exento de IVA se hará mención al artículo que lo regule.<br /><br />

    El importe de las facturas de los clientes se ingresará en la cuenta de la cooperativa. Una vez cobradas se procederá a liquidarlas a los socios.
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>6. Liquidación de los trabajos:</strong> De acuerdo a la normativa de sociedades cooperativas las remuneraciones de los socios no se consideran salarios sino que se trata de participaciones sociales que se liquidarán el 90% al cobrarse el trabajo, denominándose esta liquidación a cuenta <strong>“anticipos societarios”</strong> y el resto en la <strong>“liquidación de final de año”</strong> con los ajustes contables, fiscales o por días de seguridad social y en su caso aumentado o disminuido por los beneficios o pérdidas extrasocietarias que así lo hubiera aprobado la Asamblea General.<br /><br />

    Para el cálculo de los anticipos societarios se deducirá un importe equivalente a los importes de los seguros sociales, la retención que les sea de aplicación del impuesto sobre la renta, un 10% para la liquidación de final de año, un 6% de comisión de gestión y una cuota mensual de 6 euros que se descontará de una única liquidación en el mes. Los gastos suplidos, siempre que estén justificados con facturas o tickets, se les aplicará una comisión de gestión del 3%. 
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>7. Baja de la Cooperativa:</strong> El socio causará baja de la cooperativa cuando alcance los 18.000 euros de facturación anual, cuando lo solicite por correo electrónico o por causas establecidas en los estatutos. La baja definitiva se producirá cuando hayan sido liquidados todos los trabajos realizados.<br /><br />

    <strong>8. Prestación por desempleo.</strong> En caso de que el NUEVO SOCIO solicite la prestación por desempleo deberá solicitar previamente la baja de la cooperativa. Freelance SCM emitirá una certificación del Consejo Rector de la baja en la cooperativa y el Certificado de Empresa.
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>9.	Jurisdicción. </strong> Las partes, con renuncia a cualquier fuero propio que pudiera corresponderles, se someten a la jurisdicción de los Tribunales de Justicia de Madrid, para cualquier divergencia que pudiera suscitarse en la interpretación del presente contrato.<br />
  	  <br />
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>10. Protección de datos: </strong> <br /><br />
    
    <strong>a.	</strong>Que de acuerdo con lo establecido en 24 del Reglamento General de Protección de Datos (en adelante, RGPD), FREELANCE SCM ha implementado medidas técnicas con el fin de restringir accesos no autorizado y dado que la prestación de servicios acordada entre las partes no requiere el acceso por parte del NUEVO SOCIO a datos de carácter personal.<br /><br />
    <strong>b.	</strong>En virtud de la estipulación primera, se prohíbe expresamente que el NUEVO SOCIO acceda a datos de carácter personal incluidos en ficheros responsabilidad de FRELANCE SCM , o que en cualquier caso, sean objeto de tratamiento por parte de la misma.<br /><br />
    <strong>c.	</strong>Sin perjuicio de lo dispuesto en el párrafo anterior, el NUEVO SOCIO se obliga al cumplimiento de la obligación de secreto respecto a los datos que el personal de su entidad hubiera podido conocer con motivo de la prestación del servicio, responsabilizándose de las consecuencias que en cualquier ámbito pudieran derivarse como consecuencia de la vulneración de la referida obligación.<br /><br />
    </td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;">FREELANCE SCM . le informa que los datos personales facilitados serán utilizados con la finalidad de gestionar la relación laboral que mantiene con la misma, así como el seguimiento y control laboral. para una obligación legal, procediendo éstos del propio interesado titular de los datos bajo el consentimiento otorgado. Asimismo, FREELANCE SCM le informa que sus datos, en caso de ser solicitados, serán comunicados a las siguientes entidades:<br /><br />
    
    <strong> i.	</strong>Tesorería  General  de  la  Seguridad  Social:  en  cumplimiento  de  las  obligaciones legales vigentes en materia de seguridad social.<br />
    <strong>ii.	</strong>Agencia Estatal Tributaria: con la finalidad de llevar a cabo las obligaciones fiscales y tributarias vigentes.<br />
    <strong>iii.	</strong>Entidades gestoras de la vigilancia de la salud: para el cumplimiento de la protección respecto a contingencias de accidentes de trabajo y enfermedad profesional en los términos previstos en la legislación vigente.<br />

    <strong>iv.	</strong>Entidades bancarias: para realizar el ingreso de los importes de las nóminas.<br /><br />

    <strong>v.	</strong>Entidades aseguradoras: con la finalidad de llevar a cabo la suscripción de pólizas de seguros entre el empleado y la entidad destinataria de los datos.<br /><br />
    </td> 
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;">Los datos personales que nos proporciones serán conservados mientras se mantenga la relación laboral vigente. Puede acceder, rectificar y suprimir sus datos, portabilidad de los datos, limitación u oposición a su tratamiento, derecho a no ser objeto de decisiones automatizadas, así como a obtener información clara y transparente sobre el tratamiento de sus datos. Asimismo, en cualquier momento puede revocar su consentimiento. <br /><br />
    
    Para ejercitar los derechos de acceso, rectificación, cancelación, limitación, portabilidad y oposición reconocidos por el RGPD, el interesado deberá realizar una comunicación a FREELANCE SCM a la dirección AVENIDA MONASTERIO DE SILOS 13 C.P 28049 MADRID, a los referidos efectos, indicando como referencia “RGPD - Personal”, adjuntando copia de su Documento Nacional de Identidad. Desde FREELANCE SCM ponemos el máximo empeño para cumplir con la normativa de protección de datos dado que es el activo más valioso para nosotros. No obstante, le informamos que en caso de que usted entienda que sus derechos se han visto menoscabados en la siguiente dirección de correo electrónico info@freelance.es , o puede presentar una reclamación ante la Agencia Española de Protección de Datos (AEPD). <br /><br />
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;"><strong>11 Cláusula final: </strong> El socio actuará de buena fe de acuerdo esta normas y al buen uso de la cooperativa según su finalidad. El incumplimiento de las normas o los usos abusivos para obtener beneficios de cotizaciones, subvenciones o otras actitudes que pueden ser consideradas fraudulentas y  que puedan poner en riesgo a la cooperativa darán lugar a procedimientos de expulsión e incluso una denuncia a la administración pública o al juzgado que corresponda.
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td width="585" style="text-align: justify;">Ambas partes muestran su conformidad con el presente documento, que manifiestan haber leído y que firman a un solo efecto en lugar y fecha ut supra.</td>
  </tr>
  <tr>
  	<td height="60">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <table border="0">
        <tr>
          <td width="400">
            Lugar:
          </td>
      
          <td >
            Lugar: Madrid
          </td>
      </tr>
      <tr>
          <td width="400">
            Fecha:
          </td>
      
          <td >
            Fecha:
          </td>
      </tr>
       <tr>
    <td>&nbsp;</td>
  </tr>
      <tr>
      <td width="400">
            Fdo.-
            <br />
            Freelance SCM
          </td>
          <td>Fdo.- <br />
El Nuevo Socio </td>
        </tr>
            <tr>
      <td width="400">
          <img src="<?= Yii::getAlias('@webroot') . '/assets-custom/images/firma.png' ?>" width="289" height="104" alt=""/>  
        </td>
          <td>&nbsp;</td>
      </tr>
      </table>
    </td>
	</tr>
 </table>
</body>
</html>