<?php

namespace InteractiveValley\PakmailBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionDestinoFrontendType extends AbstractType
{
    private $listaCuidades;
    public function __construct($listaCuidades = array()) {
            $this->listaCuidades = array(
                'México'=>'México',
                'Afganistán' => 'Afganistán', 
                'Akrotiri' => 'Akrotiri', 
                'Albania' => 'Albania', 
                'Alemania' => 'Alemania', 
                'Andorra' => 'Andorra', 
                'Angola' => 'Angola', 
                'Anguila' => 'Anguila', 
                'Antártida' => 'Antártida', 
                'Antigua y Barbuda' => 'Antigua y Barbuda', 
                'Antillas Neerlandesas' => 'Antillas Neerlandesas', 
                'Arabia Saudí' => 'Arabia Saudí', 
                'Arctic Ocean' => 'Arctic Ocean', 
                'Argelia' => 'Argelia', 
                'Argentina' => 'Argentina', 
                'Armenia' => 'Armenia', 
                'Aruba' => 'Aruba', 
                'Ashmore and Cartier Islands' => 'Ashmore and Cartier Islands', 
                'Atlantic Ocean' => 'Atlantic Ocean', 
                'Australia' => 'Australia', 
                'Austria' => 'Austria', 
                'Azerbaiyán' => 'Azerbaiyán', 
                'Bahamas' => 'Bahamas', 
                'Bahráin' => 'Bahráin', 
                'Bangladesh' => 'Bangladesh', 
                'Barbados' => 'Barbados', 
                'Bélgica' => 'Bélgica', 
                'Belice' => 'Belice', 
                'Benín' => 'Benín', 
                'Bermudas' => 'Bermudas', 
                'Bielorrusia' => 'Bielorrusia', 
                'Birmania; Myanmar' => 'Birmania; Myanmar', 
                'Bolivia' => 'Bolivia', 
                'Bosnia y Hercegovina' => 'Bosnia y Hercegovina', 
                'Botsuana' => 'Botsuana', 
                'Brasil' => 'Brasil', 
                'Brunéi' => 'Brunéi', 
                'Bulgaria' => 'Bulgaria', 
                'Burkina Faso' => 'Burkina Faso', 
                'Burundi' => 'Burundi', 
                'Bután' => 'Bután', 
                'Cabo Verde' => 'Cabo Verde', 
                'Camboya' => 'Camboya', 
                'Camerún' => 'Camerún', 
                'Canadá' => 'Canadá', 
                'Chad' => 'Chad', 
                'Chile' => 'Chile', 
                'China' => 'China', 
                'Chipre' => 'Chipre', 
                'Clipperton Island' => 'Clipperton Island', 
                'Colombia' => 'Colombia', 
                'Comoras' => 'Comoras', 
                'Congo' => 'Congo', 
                'Coral Sea Islands' => 'Coral Sea Islands', 
                'Corea del Norte' => 'Corea del Norte', 
                'Corea del Sur' => 'Corea del Sur', 
                'Costa de Marfil' => 'Costa de Marfil', 
                'Costa Rica' => 'Costa Rica', 
                'Croacia' => 'Croacia', 
                'Cuba' => 'Cuba', 
                'Dhekelia' => 'Dhekelia', 
                'Dinamarca' => 'Dinamarca', 
                'Dominica' => 'Dominica', 
                'Ecuador' => 'Ecuador', 
                'Egipto' => 'Egipto', 
                'El Salvador' => 'El Salvador', 
                'El Vaticano' => 'El Vaticano', 
                'Emiratos Árabes Unidos' => 'Emiratos Árabes Unidos', 
                'Eritrea' => 'Eritrea', 
                'Eslovaquia' => 'Eslovaquia', 
                'Eslovenia' => 'Eslovenia', 
                'España' => 'España', 
                'Estados Unidos' => 'Estados Unidos', 
                'Estonia' => 'Estonia', 
                'Etiopía' => 'Etiopía', 
                'Filipinas' => 'Filipinas', 
                'Finlandia' => 'Finlandia', 
                'Fiyi' => 'Fiyi', 
                'Francia' => 'Francia', 
                'Gabón' => 'Gabón', 
                'Gambia' => 'Gambia', 
                'Gaza Strip' => 'Gaza Strip', 
                'Georgia' => 'Georgia', 
                'Ghana' => 'Ghana', 
                'Gibraltar' => 'Gibraltar', 
                'Granada' => 'Granada', 
                'Grecia' => 'Grecia', 
                'Groenlandia' => 'Groenlandia', 
                'Guam' => 'Guam', 
                'Guatemala' => 'Guatemala', 
                'Guernsey' => 'Guernsey', 
                'Guinea' => 'Guinea', 
                'Guinea Ecuatorial' => 'Guinea Ecuatorial', 
                'Guinea-Bissau' => 'Guinea-Bissau', 
                'Guyana' => 'Guyana', 
                'Haití' => 'Haití', 
                'Honduras' => 'Honduras', 
                'Hong Kong' => 'Hong Kong', 
                'Hungría' => 'Hungría', 
                'India' => 'India', 
                'Indian Ocean' => 'Indian Ocean', 
                'Indonesia' => 'Indonesia', 
                'Irán' => 'Irán', 
                'Iraq' => 'Iraq', 
                'Irlanda' => 'Irlanda', 
                'Isla Bouvet' => 'Isla Bouvet', 
                'Isla Christmas' => 'Isla Christmas', 
                'Isla Norfolk' => 'Isla Norfolk', 
                'Islandia' => 'Islandia', 
                'Islas Caimán' => 'Islas Caimán', 
                'Islas Cocos' => 'Islas Cocos', 
                'Islas Cook' => 'Islas Cook', 
                'Islas Feroe' => 'Islas Feroe', 
                'Islas Georgia del Sur y Sandwich del Sur' => 'Islas Georgia del Sur y Sandwich del Sur', 
                'Islas Heard y McDonald' => 'Islas Heard y McDonald', 
                'Islas Malvinas' => 'Islas Malvinas', 
                'Islas Marianas del Norte' => 'Islas Marianas del Norte', 
                'Islas Marshall' => 'Islas Marshall', 
                'Islas Pitcairn' => 'Islas Pitcairn', 
                'Islas Salomón' => 'Islas Salomón', 
                'Islas Turcas y Caicos' => 'Islas Turcas y Caicos', 
                'Islas Vírgenes Americanas' => 'Islas Vírgenes Americanas', 
                'Islas Vírgenes Británicas' => 'Islas Vírgenes Británicas', 
                'Israel' => 'Israel', 
                'Italia' => 'Italia', 
                'Jamaica' => 'Jamaica', 
                'Jan Mayen' => 'Jan Mayen', 
                'Japón' => 'Japón', 
                'Jersey' => 'Jersey', 
                'Jordania' => 'Jordania', 
                'Kazajistán' => 'Kazajistán', 
                'Kenia' => 'Kenia', 
                'Kirguizistán' => 'Kirguizistán', 
                'Kiribati' => 'Kiribati', 
                'Kuwait' => 'Kuwait', 
                'Laos' => 'Laos', 
                'Lesoto' => 'Lesoto', 
                'Letonia' => 'Letonia', 
                'Líbano' => 'Líbano', 
                'Liberia' => 'Liberia', 
                'Libia' => 'Libia', 
                'Liechtenstein' => 'Liechtenstein', 
                'Lituania' => 'Lituania', 
                'Luxemburgo' => 'Luxemburgo', 
                'Macao' => 'Macao', 
                'Macedonia' => 'Macedonia', 
                'Madagascar' => 'Madagascar', 
                'Malasia' => 'Malasia', 
                'Malaui' => 'Malaui', 
                'Maldivas' => 'Maldivas', 
                'Malí' => 'Malí', 
                'Malta' => 'Malta', 
                'Man, Isle of' => 'Man, Isle of', 
                'Marruecos' => 'Marruecos', 
                'Mauricio' => 'Mauricio', 
                'Mauritania' => 'Mauritania', 
                'Mayotte' => 'Mayotte', 
                'Micronesia' => 'Micronesia', 
                'Moldavia' => 'Moldavia', 
                'Mónaco' => 'Mónaco', 
                'Mongolia' => 'Mongolia', 
                'Montenegro' => 'Montenegro', 
                'Montserrat' => 'Montserrat', 
                'Mozambique' => 'Mozambique', 
                'Mundo' => 'Mundo', 
                'Namibia' => 'Namibia', 
                'Nauru' => 'Nauru', 
                'Navassa Island' => 'Navassa Island', 
                'Nepal' => 'Nepal', 
                'Nicaragua' => 'Nicaragua', 
                'Níger' => 'Níger', 
                'Nigeria' => 'Nigeria', 
                'Niue' => 'Niue', 
                'Noruega' => 'Noruega', 
                'Nueva Caledonia' => 'Nueva Caledonia', 
                'Nueva Zelanda' => 'Nueva Zelanda', 
                'Omán' => 'Omán', 
                'Pacific Ocean' => 'Pacific Ocean', 
                'Países Bajos' => 'Países Bajos', 
                'Pakistán' => 'Pakistán', 
                'Palaos' => 'Palaos', 
                'Panamá' => 'Panamá', 
                'Papúa-Nueva Guinea' => 'Papúa-Nueva Guinea', 
                'Paracel Islands' => 'Paracel Islands', 
                'Paraguay' => 'Paraguay', 
                'Perú' => 'Perú', 
                'Polinesia Francesa' => 'Polinesia Francesa', 
                'Polonia' => 'Polonia', 
                'Portugal' => 'Portugal', 
                'Puerto Rico' => 'Puerto Rico', 
                'Qatar' => 'Qatar', 
                'Reino Unido' => 'Reino Unido', 
                'República Centroafricana' => 'República Centroafricana', 
                'República Checa' => 'República Checa', 
                'República Democrática del Congo' => 'República Democrática del Congo', 
                'República Dominicana' => 'República Dominicana', 
                'Ruanda' => 'Ruanda', 
                'Rumania' => 'Rumania', 
                'Rusia' => 'Rusia', 
                'Sáhara Occidental' => 'Sáhara Occidental', 
                'Samoa' => 'Samoa', 
                'Samoa Americana' => 'Samoa Americana', 
                'San Cristóbal y Nieves' => 'San Cristóbal y Nieves', 
                'San Marino' => 'San Marino', 
                'San Pedro y Miquelón' => 'San Pedro y Miquelón', 
                'San Vicente y las Granadinas' => 'San Vicente y las Granadinas', 
                'Santa Helena' => 'Santa Helena', 
                'Santa Lucía' => 'Santa Lucía', 
                'Santo Tomé y Príncipe' => 'Santo Tomé y Príncipe', 
                'Senegal' => 'Senegal', 
                'Serbia' => 'Serbia', 
                'Seychelles' => 'Seychelles', 
                'Sierra Leona' => 'Sierra Leona', 
                'Singapur' => 'Singapur', 
                'Siria' => 'Siria', 
                'Somalia' => 'Somalia', 
                'Southern Ocean' => 'Southern Ocean', 
                'Spratly Islands' => 'Spratly Islands', 
                'Sri Lanka' => 'Sri Lanka', 
                'Suazilandia' => 'Suazilandia', 
                'Sudáfrica' => 'Sudáfrica', 
                'Sudán' => 'Sudán', 
                'Suecia' => 'Suecia', 
                'Suiza' => 'Suiza', 
                'Surinam' => 'Surinam', 
                'Svalbard y Jan Mayen' => 'Svalbard y Jan Mayen', 
                'Tailandia' => 'Tailandia', 
                'Taiwán' => 'Taiwán', 
                'Tanzania' => 'Tanzania', 
                'Tayikistán' => 'Tayikistán', 
                'Territorio Británico del Océano Indico' => 'Territorio Británico del Océano Indico', 
                'Territorios Australes Franceses' => 'Territorios Australes Franceses', 
                'Timor Oriental' => 'Timor Oriental', 
                'Togo' => 'Togo', 
                'Tokelau' => 'Tokelau', 
                'Tonga' => 'Tonga', 
                'Trinidad y Tobago' => 'Trinidad y Tobago', 
                'Túnez' => 'Túnez', 
                'Turkmenistán' => 'Turkmenistán', 
                'Turquía' => 'Turquía', 
                'Tuvalu' => 'Tuvalu', 
                'Ucrania' => 'Ucrania', 
                'Uganda' => 'Uganda', 
                'Unión Europea' => 'Unión Europea', 
                'Uruguay' => 'Uruguay', 
                'Uzbekistán' => 'Uzbekistán', 
                'Vanuatu' => 'Vanuatu', 
                'Venezuela' => 'Venezuela', 
                'Vietnam' => 'Vietnam', 
                'Wake Island' => 'Wake Island', 
                'Wallis y Futuna' => 'Wallis y Futuna', 
                'West Bank' => 'West Bank', 
                'Yemen' => 'Yemen', 
                'Yibuti' => 'Yibuti', 
                'Zambia' => 'Zambia', 
                'Zimbabue' => 'Zimbabue', 
            );
        //asort($this->listaCuidades, SORT_REGULAR);
        //$this->listaCuidades = array_merge($this->listaCuidades, array('Otro'=>'Otro...'));
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa','text',array('required'=> FALSE,'label'=>'Empresa','attr'=>array('class'=>'form-control')))
            ->add('atencion','text',array('required'=> TRUE,'label'=>'Nombre del destinatario *','attr'=>array('class'=>'form-control required')))
            ->add('calle','text',array('label'=>'Calle *','attr'=>array('class'=>'form-control required')))
            ->add('numExterior','text',array('label'=>'Número Exterior *','attr'=>array('class'=>'form-control required')))
            ->add('numInterior','text',array('label'=>'Número Interior *','attr'=>array('class'=>'form-control required')))
            ->add('pais','choice',array(
                'label'=>'País *',
                'attr'=>array('class'=>'form-control search-select'),
                'choices'=>$this->listaCuidades,
                'required'=>true,
                'empty_value' => 'Seleccionar país',
                'empty_data'  => null
                ))
            ->add('estado','text',array('label'=>'Estado o entidad federativa *','attr'=>array('class'=>'form-control required')))
            ->add('delegacion','text',array('label'=>'Municipio o Delegación *','attr'=>array('class'=>'form-control required')))
            ->add('poblacion','text',array('label'=>'Población o Colonia *','attr'=>array('class'=>'form-control required')))
            ->add('cp','text',array('label'=>'Código postal *','attr'=>array('class'=>'form-control required')))
            ->add('telefono','text',array('label'=>'Teléfono *','attr'=>array('class'=>'form-control required input-mask-phone')))
            ->add('celular','text',array('label'=>'Celular ','required'=>false,'attr'=>array('class'=>'form-control input-mask-phone')))
            ->add('email','text',array('label'=>'E-mail *','attr'=>array('class'=>'form-control required email')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\DireccionDestino'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_direcciondestino';
    }
    
    public function getArrayListCuidades(){
        
    }
}
