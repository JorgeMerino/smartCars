-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-07-2017 a las 17:42:44
-- Versión del servidor: 10.1.25-MariaDB
-- Versión de PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `smartcars`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `cliente` varchar(9) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `pais` varchar(30) NOT NULL,
  `ciudad` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `hora` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adquiere`
--

CREATE TABLE `adquiere` (
  `vehiculo` varchar(8) NOT NULL,
  `cliente` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `adquiere`
--
DELIMITER $$
CREATE TRIGGER `daDisponibilidad` AFTER DELETE ON `adquiere` FOR EACH ROW BEGIN
    UPDATE vehiculos
    SET disponible = 1
    WHERE matricula = OLD.vehiculo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `quitaDisponibilidad` AFTER INSERT ON `adquiere` FOR EACH ROW BEGIN
    UPDATE vehiculos
    SET disponible = 0
    WHERE matricula = NEW.vehiculo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `cuenta` varchar(15) NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nacimiento` date NOT NULL,
  `permisoA` tinyint(1) NOT NULL,
  `permisoB` tinyint(1) NOT NULL,
  `email` varchar(30) NOT NULL,
  `banco` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `clientes`
--
DELIMITER $$
CREATE TRIGGER `borraDatos` AFTER DELETE ON `clientes` FOR EACH ROW BEGIN
	DELETE FROM banco WHERE cuenta = OLD.banco;
    DELETE FROM passwords WHERE cliente = OLD.dni;
    DELETE FROM accesos WHERE cliente = OLD.dni;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `passwords`
--

CREATE TABLE `passwords` (
  `cliente` varchar(9) NOT NULL,
  `metodo` varchar(50) NOT NULL,
  `iteraciones` int(11) NOT NULL,
  `sal` varchar(128) NOT NULL,
  `pass` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `marca` varchar(20) NOT NULL,
  `modelo` varchar(20) NOT NULL,
  `matricula` varchar(8) NOT NULL,
  `disponible` tinyint(1) NOT NULL,
  `anio` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `color` varchar(20) NOT NULL,
  `potencia` int(11) NOT NULL,
  `combustible` varchar(10) NOT NULL,
  `transmision` varchar(10) NOT NULL,
  `km` int(11) NOT NULL,
  `permiso` varchar(1) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` varchar(10000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`marca`, `modelo`, `matricula`, `disponible`, `anio`, `precio`, `color`, `potencia`, `combustible`, `transmision`, `km`, `permiso`, `tipo`, `imagen`, `titulo`, `descripcion`) VALUES
('Ducati', 'XDiavel S', '0893 KCV', 1, 2017, 23000, 'Negro', 156, 'Gasolina', 'Manual', 0, 'A', 'Proximo', 'images/DucatiXDiavel.jpg', 'Disfruta de la carretera, con toda tranquilidad', 'La fuerte personalidad de la nueva Ducati XDiavel se expresa también en los detalles. La versión S combina materiales valiosos y acabados refinados en contenidos tecnológicos de alto nivel. El resultado es un objeto precioso, de diseño único e inconfundible. El corazón de la XDiavel, encastrado en el centro e indiscutible protagonista, es el nuevo motor Ducati Testastretta DVT 1262, en negro luminoso con torneados visibles, capaz de liberar un elevado par en todos los regímenes. La XDiavel nace también de la constante inversión en calidad del diseño, los materiales y las tecnologías. Todo esto garantiza al motor TestastrettaDVT 1262 intervalos de mantenimiento especialmente largos. La utilización de materiales específicos para el soporte de las válvulas, la mejora de la eficiencia de combustión y la contención de las temperaturas de funcionamiento, confieren a la XDiavel la máxima fiabilidad y una gestión reducida a los mínimos términos. De hecho, las operaciones de mantenimiento programadas se realizan solo cada 15.000 km, mientras que el Desmo Service, que comprende el control y el eventual reglaje del juego de válvulas, se lleva a cabo tan solo cada 30.000 km. De este modo, la XDiavel permite apreciar el “low speed excitement” con total tranquilidad.'),
('Mercedes', 'CLA', '0974 HPB', 1, 2015, 300, 'Blanco', 105, 'Diesel', 'Manual', 11000, 'B', 'Renting', 'images/MercedesCLA.jpg', 'CLA. Rock. Star.', 'Las líneas especialmente llamativas y las superficies convexas y cóncavas confieren al nuevo CLA Coupé un carácter deportivo y vanguardista. Elementos típicos de coupé, como las ventanillas sin marco o las ventanillas enmarcadas en cromo o negro de alto brillo en función del equipamiento, confieren al modelo una elegancia y valencia especiales. La línea del techo se inclina suavemente hacia la zaga donde los embellecedores de la salida de escape integrados en el paragolpes y el llamativo perfil aerodinámico conforman un final irresistible. Un elemento muy destacado: el característico diseño de los grupos ópticos traseros compuestos opcionalmente en su totalidad por diodos luminosos.  Como corresponde a su innovador diseño, el nuevo CLA Coupé puede encargarse también con la pintura mate designo plata polar magno y otras innovadoras pinturas. Las múltiples líneas y paquetes de equipamiento brindan además infinitas opciones de personalización del nuevo CLA Coupé. En el puesto de conducción destaca el display multimedia exento de 20,3 cm ―opcional en función del equipamiento― con su innovador diseño que recuerda a un smartphone. La presencia de numerosos mandos y marcos de cromo plateado con superficies metálicas y un tacto de gran calidad no dejan a nadie indiferente. Las molduras de gran tamaño en aluminio, madera o fibra de carbono subrayan el carácter lujoso del nuevo CLA.   Los asientos deportivos de aspecto integral también son muy especiales y, como opción, se ofrece una iluminación de ambiente que incluye doce colores a elegir.'),
('Jaguar', 'XE', '1096 JPS', 1, 2016, 38000, 'Azul Marino', 200, 'Diesel', 'Manual', 0, 'B', 'Proximo', 'images/JaguarXE.jpg', 'La berlina deportiva más avanzada, eficiente y segura jamás producida por Jaguar.​', 'La imagen enérgica y la agilidad en carretera del XE lo identifican al instante como un Jaguar. Se comporta como un Jaguar, se conduce como un Jaguar. El XE es Jaguar de principio a fin. Con un coeficiente de resistencia aerodinámica (Cd) de tan solo 0,26, el XE corta el viento para mejorar la eficiencia y el refinamiento. Desde la emoción en estado puro del V6 sobrealimentado, capaz de alcanzar los 250 km/h, a la eficiencia de unas emisiones de CO2 de tan solo 99 g/km, hay una gama de motores adaptados a todos los estilos de conducción. El XE atrae todas las miradas al instante. Es un vehículo que sobresale entre sus competidores por las magníficas proporciones y pureza de superficies que constituyen la base del diseño Jaguar. InControl es nuestra gama de tecnologías avanzadas diseñadas para conectarte a ti y a tu XE con el resto del mundo. Puedes elegir dos sistemas de infoentretenimiento InControl: Touch, disponible en todos los modelos XE, o el nuevo y potente sistema Touch Pro* de nivel superior con lo último en infoentretenimiento. Con una gama de tecnologías innovadoras e inteligentes que mantienen los costes bajo control y primas de seguro competitivas por parte de las compañías aseguradoras, el XE es sin duda la elección más inteligente.'),
('Maserati', 'Levante', '1756 KDW', 1, 2017, 85000, 'Blanco', 430, 'Gasolina', 'Automatico', 0, 'B', 'Proximo', 'images/MaseratiLevante.jpg', 'The Maserati of SUVs', 'Lujoso y aventurero, el Levante proporciona los más altos niveles de confort, al mismo tiempo que ofrece un rendimiento excelente, incluso en las condiciones más extremas. No hay problemas de espacio ni de rendimiento. Todas las versiones vienen con una caja de cambios de ocho marchas, el sofisticado sistema inteligente Q4 AWD de Maserati y el torque vectoring para una conducción realmente apasionante. Sus elegantes superficies, sus amplios asientos de cuero, los intuitivos controles a bordo y una ingeniería excepcional crean una clase completamente nueva de SUV. El inconfundible diseño Maserati se ve en la silueta elegante y deportiva, que cuenta con las tres llamativas y características salidas de aire en las aletas delanteras. El estilo rotundo de la parrilla frontal rememora a la de los grandes deportivos Maserati del pasado e incorpora el legendario logotipo del Tridente, un símbolo de exclusividad, refinamiento y elegancia. El Levante es un SUV como ningún otro, que incorpora la pasión, la sofisticada ingeniería y la exclusividad que ha distinguido a cada vehículo diseñado y construido por Maserati en el corazón de Italia, desde que fue fundada en 1914. 100% SUV, 100% Maserati. Listo para afrontar cualquier cosa que se le ponga por delante, el Levante domina incluso en los terrenos más complicados o en las peores condiciones meteorológicas, para llevarle donde quiera estar y hacer lo que quiera hacer.'),
('Porsche', '718 Cayman', '1986 HML', 1, 2015, 65000, 'Amarillo', 295, 'Gasolina', 'Manual', 4500, 'B', 'Compra', 'images/PorscheCayman.jpg', '718 Cayman. Por puro deporte', 'El 718 Cayman deja que los hechos hablen por sí solos. Con deportividad, claro. Como Coupé devorador de curvas, defiende su punto de vista muy lejos de las tendencias de la moda y de las carreteras de cuatro carriles. Ya sea en ciudad. O sobre el circuito. Pero siempre por puro deporte.  No es ninguna sorpresa que el 718 Cayman no solo continúe ahí donde se detuvo el 718 GTR Coupé en 1963, sino que teja ahora su propia leyenda. Con cabeza. Con espíritu de lucha deportivo. Y con cada curva. Precisión sin caer en la extravagancia. Seguro de sí mismo, nada discreto. Profundo, ancho, plano. El diseño de los modelos 718 se caracteriza por sus ángulos deportivos y sus formas claramente definidas.  La geometría horizontal de las tomas de aire da una apariencia aún más ancha al frontal, lo que le aporta un ímpetu adicional. ¿Las aletas delanteras? Inconfundiblemente Porsche. Son más altas que el capó delantero y acentúan el diseño de los faros con su lenguaje formal, con expresivas molduras interiores y luces diurnas LED integradas. La línea lateral resalta el concepto de motor central. Los pasos de rueda son grandes, para llantas de hasta 20 pulgadas. Con esto, una cosa queda clara: los modelos 718 no se han fabricado para estar en el garaje. Sino para vivir la diversión al volante. Por eso, el contorno de las puertas conducen el aire hacia las grandes tomas de aire laterales. Para una óptima alimentación de los motores turbo y, con ello, una dinámica propulsión.'),
('Mercedes', 'Clase E', '2063 HGR', 1, 2015, 45000, 'Rojo', 210, 'Gasolina', 'Automatico', 5000, 'B', 'Compra', 'images/MercedesE.jpg', 'Clase E. Inteligencia al servicio de las emociones.', 'Rasgos nítidos y poderosos en combinación con detalles deportivos hacen de la nueva Clase E Coupé un icono de estilo. Los diseñadores no han dejado nada al azar. Todas las proporciones son perfectas, desde el diseño frontal deportivo y llamativo, pasando por el purismo del diseño lateral, hasta la zaga atlética y musculosa.  La nueva Clase E Coupé destaca por su sugestiva deportividad desde cualquier perspectiva. La línea del techo desemboca con extraordinaria fluidez en una zaga de corte atlético, dando lugar a una estética de coupé moderna e imponente.   Una parrilla noble de efecto diamante, los faros MULTIBEAM LED opcionales y un parachoques frontal prolongado hacia abajo configuran un diseño frontal destacado y subrayan una vez más el aire típico de los coupés de Mercedes-Benz. El exterior moderno e intemporal incluye además llantas de aleación y elementos de diseño en cromo y aluminio. El tren de rodaje AGILITY CONTROL de altura rebajada acentúa asimismo la estética deportiva.  El interior de la nueva Clase E Coupé destaca por su generosidad. Gracias a sus cuatro plazas de asiento con plena funcionalidad resulta una opción perfecta como elegante modelo para viajes, siguiendo la tradición de los exclusivos modelos Gran Turismo. La configuración deportiva y elegante, con gran cuidado de los detalles y un acabado esmerado, convence a cada uno de los cuatro ocupantes.  La calidad y el efecto envolvente del habitáculo conforman un espacio de bienestar incomparable. Un equipo especialmente fascinante es el puesto de conducción widescreen opcional, formado por dos visualizadores de 12,3 pulgadas y botones de control táctil en el volante. Es la llave de acceso a un mundo digital fascinante.'),
('Maserati', 'Ghibli', '2349 HXC', 1, 2015, 850, 'Azul', 330, 'Gasolina', 'Manual', 4500, 'B', 'Renting', 'images/MaseratiGhibli.jpg', 'Absolutamente opuesto a lo ordinario', 'El Maserati Ghibli es un ejemplo supremo del diseño italiano, que combina elegantes contornos deportivos con la mejor ingeniería. También ofrece niveles de lujo por los que se reconoce a Maserati en todo el mundo. El Ghilbi es un coche que nació para inspirar y emocionar, con líneas limpias y formas esculpidas que atrapan la vista y cautivan la imaginación. Su largo capó aloja un potente motor, mientras que la parrilla delantera y el logotipo clásico del Tridente anuncian al instante que se trata de un Maserati. El Ghibli cuenta con los más altos niveles de agilidad, que hacen responder al coche instantáneamente a cada solicitud del conductor. Desde la transmisión ZF de ocho marchas, hasta el motor V6 Twin Turbo, cada aspecto de este excepcional Maserati ha sido diseñado y construido para maximizar el rendimiento y el placer de conducir. Avanzada tecnología y las mejores especificaciones para nuevos niveles de lujo, junto al gran equipamiento de serie del Ghibli hay una amplia gama de elementos opcionales. Ésta incluye sistemas de sonido premium, creados en asociación con Bowers & Wilkins o Harman Kardon, y el interior más elegante del mundo para un coche, diseñado por la renombrada casa de moda Ermenegildo Zegna. '),
('BMW', 'Serie 2', '2361 JKL', 1, 2016, 300, 'Rojo', 190, 'Diesel', 'Automatico', 5500, 'B', 'Renting', 'images/BMWserie2.jpg', 'Despréndete de lo corriente. BMW Serie 2', 'Una mirada lo dice todo. Este coche es la continuación de una larga tradición de coupés BMW. Es un homenaje a un icono de la historia de BMW: el legendario BMW 2002. Pero con una personalidad propia llena de pasión y descaro. Cada una de las líneas del BMW Serie 2 Coupé subraya su carácter rebelde. Y el interior está diseñado para que experimentes el puro placer de conducción. Un coupé decidido a definir sus propios hitos. Cuando vives la vida al máximo, no te interesan las concesiones. Ni tampoco al BMW Serie 2 Coupé. El agarre, la distribución de peso 50:50 entre los dos ejes y las prestaciones deportivas de la tracción trasera son únicos en su categoría. Dinámico y alegre, con un solo objetivo: placer de conducir en estado puro. El BMW Serie 2 Coupé es insaciable: más prestaciones, más deportividad, más razones para asombrar. Pero también es el más superlativo en los mínimos: menos consumo y menos emisiones. Con el modo ECO PRO, el sistema Brake Energy Regeneration o la función Auto Start/Stop, el coupé hace un uso experto de las medidas inteligentes de BMW EfficientDynamics. Y gracias a su diseño aerodinámico, el BMW Serie 2 Coupé conforma una espléndida silueta.'),
('Audi', 'TT', '2891 KBN', 1, 2017, 41000, 'Azul', 185, 'Gasolina', 'Manual', 0, 'B', 'Proximo', 'images/AudiTT.jpg', ' Échale la culpa a tu instinto Audi TT Coupé ', 'Vuelve un vehículo que revolucionó el diseño y la deportividad. El reto fue reinterpretar completamente una figura icónica y hacer que siga siendo, en esencia, la misma. Rendir un justo homenaje a las míticas formas que elevaron a la categoría de clásico a la primera generación del Audi TT. Un vehículo con más genes de la familia R que nunca, representados con la parrilla delantera Audi Singleframe, más ancha y plana, y la colocación de los cuatro aros en el capó, siguiendo el estilo de los deportivos de alto rendimiento. En cuanto a tecnologías, el Audi TT cuenta con grandes innovaciones como el Audi virtual cockpit, el sistema Audi drive select, los innovadores faros Matrix LED con intermitentes dinámicos y todos los sistemas de asistencia disponibles en Audi. Si el Audi TT es lo que quieres, échale la culpa a tu instinto. El diseño del Audi TT rememora la primera generación de un icono con múltiples detalles, como los arcos de rueda anchos y redondos, los tubos de escape mucho más centrados y la tapa de depósito de aluminio con el nombre del modelo inscrito en ella. Debajo del tapón circular del depósito, situado en el lado derecho, el conductor ya no deberá desenroscar ningún tapón: el repostaje se realiza directamente, como en los vehículos más deportivos.'),
('Honda', 'Shadow VT750C2B', '2987 JDC', 1, 2016, 150, 'Negro', 46, 'Gasolina', 'Manual', 4500, 'A', 'Renting', 'images/HondaShadow.jpg', 'Shadow VT750C2B. Pura libertad', 'Una potencia deportiva mejorada, un nuevo estilo afilado y una conducción más ágil y refinada convierten a la Shadow VT750C2B en la compañera perfecta para disfrutar de una conducción con las máximas prestaciones. Escápate con un suave giro del acelerador. El bicilíndrico de 750 cc y refrigeración líquida de la Shadow VT750C2B proporciona una excelente respuesta y aceleración, con un elevado par motor en bajas y medias revoluciones, con el inconfundible sonido de una bicilíndrica de alta cilindrada. Su rendimiento se combina con un impresionante consumo y con el cumplimiento de la norma Euro 4 (emisiones de CO2 de 81g/km). En definitiva, sus máximas prestaciones reducen el paso por la gasolinera. Ilumina la instrumentación de la Shadow VT750C2B en un color a juego con tu estado de ánimo. Además de mostrar la información habitual, como el consumo, la temperatura de los puños calefactados o la velocidad, la grande y brillante pantalla LCD de la Shadow VT750C2B te informa del comportamiento de la moto con una serie de testigos con diferentes colores. Disfruta del paisaje mientras el cambio exclusivo de doble embrague (DCT) de la Shadow VT750C2B se encarga de las marchas. Con la Transmisión de Doble Embrague (DCT) de la Shadow VT750C2B los cambios de marcha son extremadamente suaves, sin pérdidas de potencia, por lo que no perderás ni un ápice de diversión... ya sea conduciendo hacia el trabajo o en una escapada de fin de semana. Hay tres modos diferentes en función de las condiciones, incluido un dinámico modo S (Sport) que te proporcionará una conducción muy divertida con solo tocar un botón.'),
('Audi', 'A5', '3450 HRF', 1, 2015, 39000, 'Blanco', 175, 'Diesel', 'Manual', 4500, 'B', 'Compra', 'images/AudiA5.jpg', ' Nuevo Audi A5 Coupé Elegancia deportiva', 'Audi creó una obra de arte que cautivó a aficionados de todo el mundo. Nueve años después llega el lanzamiento de la segunda generación, con un nuevo y afinado estilo y tecnología de última generación. La nueva generación del Audi A5 Coupé es atlética, deportiva, elegante y con una sofisticada aerodinámica. Bajo la carrocería, el Audi A5 impresiona con un chasis enteramente nuevo, motores potentes, innovadores equipamientos de infotainment y avanzados sistemas de ayuda a la conducción. Muestra un aspecto fresco, moderno y afinado. Y sin embargo es fiel a su ADN, con el carácter deportivo y elegante transmitido a la nueva versión. El mejor ejemplo es la forma ondulada en la línea de cintura que ya caracterizaba al modelo anterior. Una línea de formas precisas cuyo ensanchamiento sobre los pasos de rueda, una sutil alusión a la tracción total quattro, enfatiza la silueta. Una inteligente combinación de diferentes tecnologías realza la seguridad, confort y eficiencia del nuevo Audi A5 Coupé. Al mismo tiempo, Audi sigue avanzando hacia la conducción autónoma. En comparación con el modelo anterior, casi todos los sistemas son completamente nuevos o han sido profundamente actualizados. El equipamiento de serie es aún más generoso. Entre los elementos incluidos están los faros de xenón con luces diurnas LED y los pilotos traseros LED, la iluminación interior LED, el Audi MMI radio plus con pantalla a color de 17,8cm (7\"), Bluetooth, función de carga por USB, y los modos de conducción Audi drive select y el asistente para evitar accidentes por alcance Audi pre sense city.'),
('Kawasaki', 'ER-6f', '4008 JLD', 1, 2016, 6500, 'Verde', 72, 'Gasolina', 'Manual', 2500, 'A', 'Compra', 'images/KawasakiER.jpg', 'Disfruta de una realidad completamente nueva con la ER-6f', 'Escapa de la ciudad y disfruta de una realidad completamente nueva con la ER-6f 2016. Más fácil de conducir con un asiento más bajo. Disfruta de nuevos y deconocidos rincones a lomos de la nueva ER-6f. El nuevo diseño del doble faro delantero le aporta a la nueva ER-6f un rostro más agresivo. El diseño del faro presenta dos bombillas de posición, al igual que en nuestros modelos Ninja. En este modelo, el diseño esculpido es un claro ejemplo de la atención que se ha dado a los detalles. El nuevo chasis perimetral de doble tubo es un componente clave en la identidad de la ER-6f. El diseño de doble viga del nuevo chasis, realizado en acero de alta resistencia, le otorga a la motocicleta una sensación de ligereza. Gracias a la combinación de una inyección de combustible eficiente y un catalizador de 3 vías dentro del silenciador se consigue que las emisiones sean muy bajas. El depósito es 20 mm más alto, lo que permite que la masa de la ER-6f se concentre visualmente en la parte delantera. La parta trasera del depósito se inclina hacia atrás formando una línea recta con el asiento. El equilibrio de la rigidez del chasis de doble viga y del basculante crea un conjunto más ligero y más fácil de pilotar. El nuevo diseño de doble brazo del basculante hace uso de una viga en forma de D, que ofrece una óptima rigidez.'),
('Ford', 'Mustang', '5321 JCV', 1, 2016, 400, 'Rojo', 300, 'Gasolina', 'Automatico', 6500, 'B', 'Renting', 'images/FordMustang.jpg', 'La redefinición de una leyenda', 'Emblemático, potente y dinámico: este es el nuevo Ford Mustang. El nuevo Mustang ofrece lo máximo en rendimiento, elegancia y eficiencia, además de diseño vanguardista y tecnologías avanzadas, todo combinado para redefinir una leyenda. Fiel al espíritu del Mustang y con una forma ultramoderna, el capó alargado del cupé fluye hacia abajo para reducir el arrastre, ayudando al vehículo a cortar el aire fácilmente. Los parachoques delanteros mejoran el agarre del vehículo para que puedas disfrutar del extraordinario rendimiento del nuevo Mustang. El distintivo diseño de sus faros delanteros y traseros, junto con otras de sus características emblemáticas, no dejan duda de la identidad del coche. Un vehículo deportivo pensado totalmente para el conductor, con una gama de tecnologías puestas a tu disposición para mejorar la experiencia al volante. La combinación de una distintiva parrilla frontal, un capó perfectamente esculpido, luces traseras de triple barra, el llamativo emblema de GT o del caballo y tubos de escape dobles otorgan al nuevo Mustang una imagen potente. Una carrocería atlética y al mismo tiempo sensual perfectamente combinada con características de diseño que mejoran la aerodinámica y el agarre del vehículo, aumentando al máximo su rendimiento y eficiencia.'),
('Maserati', 'Gran Cabrio', '5328 HST', 1, 2015, 125000, 'Rojo', 405, 'Gasolina', 'Manual', 8500, 'B', 'Compra', 'images/MaseratiGranCabrio.jpg', 'La euforia del aire libre', 'El impresionante perfil del GranCabrio Sport lleva la inconfundible firma de Pininfarina, unas líneas que han adornado nuestros vehículos durante generaciones. El GranCabrio MC es el Maserati descapotable más resistente, enriquecido con la seductora elegancia italiana y un lujoso confort. El impecable e imponente frontal cuenta con numerosas características de nuevo diseño. Desde el paragolpes delantero y el splitter, hasta la prominente nueva parrilla con el icónico Tridente Maserati. Con la capota puesta, la impresión es de fluidez atlética, un aspecto que resaltan las aletas redondeadas traseras y un nuevo paragolpes diseñado para encajar con la estética del delantero. La trasera se caracteriza por un escape alto situado en posición central y un nuevo paragolpes dinámico, diseñado para mejorar el flujo de aire en la parte posterior del coche. El aspecto poderoso del GranCabrio MC se ve acentuado por las llantas MC Design de 20\". La tecnología vanguardista asegura una conducción que responde a todas sus necesidades. Al apretar un botón el coche se transforma en un llamativo descapotable. Los mandos de control intuitivos están todos colocados cerca de las manos del conductor, mientras que un climatizador inteligente proporciona un confort completo para los ocupantes. De una exquisita elegancia en su interior y en su exterior, las líneas aerodinámicas del GranCabrio dan al coche un tacto lujoso y deportivo. Con sólo tocar un botón, se transforma de coupé deportivo a roadster descubierto, para una experiencia de conducción realmente inspiradora.'),
('Mini', 'Cooper', '5561 JSN', 1, 2016, 320, 'Rojo', 170, 'Gasolina', 'Manual', 5000, 'B', 'Renting', 'images/MiniCooper.jpg', 'Un nuevo clásico', 'El nuevo MINI Cooper S alcanza los 100 km/h en 6,9 segundos, más o menos lo mismo que tardarían en acomodarse cinco personas gracias a las dos nuevas puertas traseras. Pero eso no es todo. La mayor distancia entre ejes ofrece 72 mm más a los ocupantes del asiento trasero para que no sólo puedan entrar con más comodidad, sino también estirarse a gusto. Pero tendrán que hacerlo rápido, ya que tienes 141 kW de potencia a tu disposición. No hay tiempo para remolonear.'),
('Audi', 'Q7', '5832 HTR', 1, 2015, 67000, 'Azul', 230, 'Gasolina', 'Automatico', 5500, 'B', 'Compra', 'images/AudiQ7.jpg', ' Lo extraordinario no conoce límites. Audi Q7 ', 'Justo como debe ser un Audi Q7: atlético, atemporal, único. Con un aspecto deportivo realzado. Con más anchura y altura, y una presencia aún más impactante. Potente e impactante, pero aun así, ligero. Un diseño que habla un nuevo lenguaje, como la parrilla Audi Singleframe en diseño tridimensional o la tecnología Audi Matrix LED que consigue aportar un brillo cristalino y proporcionan una tecnología de iluminación de las más avanzadas del sector de la automoción. El Audi Q7 es el resultado de una idea ambiciosa: no dejar de mejorar nunca. Algo extraordinario es conseguir más amplitud en menos espacio. Aunque el Audi Q7 es más compacto que su predecesor, ofrece a sus ocupantes más amplitud para las piernas y la cabeza y presenta varias configuraciones de espacio y una tercera fila opcional de asientos plegables electrónicamente. De esta forma, tendrá la opción de disfrutar de siete asientos de acuerdo con sus necesidades. Preparado para enfrentarse a cualquier situación. Realzado por superficies claramente definidas y líneas firmes. Atlético, atemporal, único. Los genes de quattro se hacen claramente visibles en los pasos de rueda y en el embellecedor lateral con el logotipo de quattro grabado.'),
('Seat', 'Ateca', '6289 JLS', 1, 2016, 20000, 'Naranja', 125, 'Diesel', 'Manual', 0, 'B', 'Proximo', 'images/SeatSuv.jpg', 'Seat Ateca. Una mirada llena de confianza', 'El SEAT Ateca afronta el día a día lleno de confianza gracias al inconfundible y dinámico estilo que crean las definidas líneas de su diseño exterior. En su interior, todo ha sido concebido para que la rutina sea una experiencia fantástica. Y ahora puedes ir un paso por delante con el nuevo SEAT Ateca FR, que te ofrece rendimiento y un toque deportivo para que tu día a día supere todas tus expectativas gracias a su tracción 4Drive. Consigue que tu rutina diaria discurra a la perfección con una tecnología avanzada diseñada para ofrecerte la experiencia urbana más optimizada. Con la tecnología Full Link, podrás conectar tu smartphone y disfrutar de tus apps mientras conduces y, con el cargador inalámbrico, cargar el móvil ya no te supondrá ningún esfuerzo. La seguridad en la ciudad se consigue previendo cómo simplificar cualquier situación para que tu día discurra sin tropiezos. El Asistente en caso de atasco* te ayuda a detenerte y arrancar durante una congestión de tráfico y, gracias a detector de ángulos muertos con Alerta de tráfico posterior , los cambios de carril serán más seguros. Cada detalle del interior y exterior del SEAT Ateca ha sido diseñado meticulosamente para que tu rutina diaria sea siempre especial. Te ofrece todo lo que necesitas en términos de tecnología y diseño para transformar lo cotidiano en algo maravilloso.'),
('Jeep', 'Wrangler', '7123 JTD', 1, 2016, 37000, 'Azul', 175, 'Diesel', 'Automatico', 3000, 'B', 'Compra', 'images/JeepWrangler.jpg', 'Jeep Wrangler. Nacido para la aventura', 'En el interior del Wrangler disfrutarás de un amplio espacio de almacenamiento, una ergonomía bien diseñada y protección contra el ruido, la vibración y la aspereza del terreno. Además, dispones de opciones diseñadas para ofrecerte más confort, como asientos delanteros calefactados tapizados en cuero, portavasos iluminados, iluminación en los espacios para los pies y un espejo retrovisor fotosensible y luces LED para lectura de mapas. Con el sistema UconnectTM puedes manejar todo el sistema de información y entretenimiento. Los comandos de voz hacen que la conducción de tu Jeep® Wrangler sea mucho más fácil. Te permiten usar la voz para seleccionar emisoras de radio o llamar por teléfono sin apartar la vista de la carretera. UconnectTM Navigation facilita el uso del navegador GPS y permite introducir el destino utilizando la voz. Puedes hablar por teléfono mientras conduces gracias al sistema de comunicación integrado en el vehículo, que puede activarse también con la voz.  Por último, puedes escuchar tu música favorita a través de un CD/DVD, un dispositivo MP3, el puerto USB, la entrada auxiliar de audio, un disco duro, o el streaming de audio por Bluetooth®. Las prestaciones del Jeep® Wrangler te permiten conducir en cualquier tipo de terreno. La tracción te ayuda a mantener el control en cualquier situación. La suspensión del Jeep® Wrangler ayuda a mejorar su respuesta fuera del asfalto gracias a su mayor flexibilidad, la articulación de los ejes y el recorrido de las ruedas. La maniobrabilidad es clave para evitar situaciones de emergencia y daños en el vehículo. La altura libre al suelo del Jeep® Wrangler hace que tu vehículo esté preparado para todo tipo de terrenos y la protección de los bajos te permite superar troncos, rocas y terrenos irregulares.'),
('Audi', 'S3', '7598 HWT', 1, 2015, 450, 'Gris Grafito', 198, 'Gasolina', 'Manual', 13000, 'B', 'Renting', 'images/AudiS3.jpg', 'Solo hay una dirección: hacia adelante', 'Más potencia, mayor potencial dinámico, mayor placer de conducción; así es el nuevo Audi S3 y su motor 2.0 TFSI de rendimiento mejorado y tracción quattro de serie.  Su aspecto expresa potencia aún cuando está parado. Detalles como los retrovisores exteriores en aluminio o la parrilla Singleframe con diseño S y los listones horizontales cromados caracterizan el exterior del vehículo. Los faldones laterales siguen el diseño del parachoques delantero y trasero, y aportan un toque aún más contundente. Con numerosos sistemas de asistencia al conductor, el nuevo Audi S3 deja el listón muy alto. Por ejemplo, el sistema opcional Audi active lane ayuda al conductor a permanecer dentro de su carril a velocidades superiores a los 65 km/h. La cámara frontal detecta las líneas de delimitación de carril, y tan pronto como el vehículo se acerca a una línea sin utilizar el intermitente, el sistema interviene y aplica una corrección suave de rumbo a través de la dirección asistida del vehículo. El asistente de conducción en atascos opcional ayuda al conductor cuando el tráfico es denso y se conduce a velocidades de menos de 65 km/h, manteniendo una distancia de seguridad suficiente con el vehículo de delante. El asistente de atascos también toma el control del vehículo, si es necesario.'),
('Chevrolet', 'Camaro', '7649 KBW', 1, 2017, 33000, 'Verde', 350, 'Gasolina', 'Automatico', 0, 'B', 'Proximo', 'images/ChevroletCamaro.jpg', 'Una leyenda a cualquier velocidad ', 'Se podrían usar muchas palabras para describir a esta legendaria familia deportiva, pero la única que captura toda la experiencia en ingeniería y refinamiento que lleva cada auto -desde el LS de gran reacción hasta el ZL1 supercargado- es Camaro. El diseño de cada borde y superficie fue cuidadosamente pensado para que el Camaro de sexta generación -más liviano y atlético- pudiera ofrecer un desempeño ágil junto con una presencia en la carretera difícil de ignorar. Descarga y disfruta el Camaro 2018 directamente en tu smartphone o tableta. Personalízalo combinando rines, colores, franjas y accesorios y después date una vuelta en 360° por el interior. Los modelos equipados con un motor V8 tienen Launch Control, que usa el control de tracción de desempeño para que los neumáticos traseros sigan girando a la velocidad correcta; así puedes ajustar tus rpm de lanzamiento y deslizamiento de las ruedas a través del centro de información al conductor. El ZL1 tiene además Custom Launch Control con la función de bloqueo de línea te permite bloquear las ruedas delanteras para que las ruedas traseras giren, calentando los neumáticos para un agarre máximo en la pista. Esta función es para usar en carreras de recorrido cerrado.'),
('Hummer', 'H3 Alpha', '7800 JKN', 1, 2016, 40000, 'Naranja', 295, 'Gasolina', 'Automatico', 0, 'B', 'Proximo', 'images/HummerH3.jpg', 'Nuevo Hummer H3 Alpha', 'Si algo le faltaba al Hummer H3 para parecerse a sus hermanos mayores es un motor V8 bien gordo bajo el capó. Pues dicho y hecho, en General Motors habrán pensado lo mismo que muchos (entre los que no me incluyo) y va a presentar en el salón de Nueva York un nuevo modelo del pequeñín de la casa. El Hummer H3 Alpha recupera la denominación Alpha como tope de gama (perdida con la “extinción” del H1) y equipa un motor V8 5.3, sustituyendo al raquítico 3.5 de 5 cilindros en línea que equipan sus congéneres más débiles. Este nuevo motor entrega una potencia de 295 CV y 438 Nm de par máximo, suficientes para un coche de estas características. El diseño es básicamente el mismo que en otras variantes, salvo en el interior, que lleva insertada la palabra Alpha en el volante, además de venir equipado con el pack interior de lujo. Y pocas diferencias más hay entre un H3 normal y este H3 Alpha. Ahora el cubrecárter es plateado como en Hummer H2 y ya está bien. Y por último, coincido también con mi colega de Autoblog al preguntarme por el posible anti-Wrangler sobre el que se lleva rumoreando casi un año. Éste estoy seguro que no es y por como se ven las cosas, me da que no lo vamos a ver ni en pintura. '),
('Subaru', 'BRZ', '7863 JMS', 1, 2016, 32000, 'Azul', 200, 'Gasolina', 'Manual', 2000, 'B', 'Compra', 'images/SubaruBRZ.jpg', 'Subaru BRZ - Brand New Wind', 'El BRZ cuenta con una nueva generación de 2.0l DOHC SUBARU BOXER, más compacto y equipado con una tecnología de inyección directa de combustible, mejorando su respuesta y consumos. A este motor de 200 CV le encantan las revoluciones. Con una generosa zona roja, una curva de par óptima y 100 CV por litro, obtendrá la potencia que necesite a cualquier régimen de motor, causando un menor impacto en el medio ambiente. El reto era dotar al  BRZ con un centro de gravedad lo más bajo posible, para una conducción más deportiva. Todos los elementos del coche, desde el suelo hasta el techo, pasando por el diseño del motor horizontalmente opuesto y la posición de los asientos, contribuyen a proporcionar al BRZ de  uno de los centros de gravedad más bajos de todo el segmento deportivo. Cinturones delanteros ajustables en altura, asientos delanteros que reducen el riesgo de lesiones cervicales y limitadores de cabeza de absorción de energía en caso de colisión desde atrás. El BRZ incorpora de serie airbags SRS* frontales, laterales delanteros, de cortina, y de rodilla para proteger las extremidades inferiores. Incluye anclajes ISO-FIX para facilitar la instalación de sillas infantiles compatibles. El sistema VDC, de serie en el BRZ, supervisa y analiza si el vehículo está respondiendo a la ruta prevista del conductor a través de un conjunto de sensores. En caso de aproximarse a los límites de estabilidad determinados al tomar una curva o al esquivar un obstáculo, la distribución del par de la AWD, la potencia del motor y los frenos de cada rueda se ajustan para ayudar a mantener la trayectoria del vehículo.'),
('Ford', 'Focus', '8306 JCX', 1, 2016, 200, 'Azul', 125, 'Diesel', 'Manual', 3500, 'B', 'Renting', 'images/FordFocus.jpg', 'El Focus más elegante, eficiente y avanzado y es mejor que nunca', 'Con un diseño exterior novedoso y un espacio interior más avanzado y sofisticado que nunca, es el Focus más elegante de todos los tiempos. El Ford Focus incorpora una amplia gama de tecnologías avanzadas. Por ejemplo, Ford SYNC 3 con control de voz y pantalla táctil de 8” te permite controlar de forma intuitiva los sistemas de navegación, entretenimiento y climatización del coche usando tan solo la voz. El sistema de aparcamiento asistido se ha mejorado para facilitar las maniobras de estacionamiento en paralelo y en perpendicular.    Una amplia gama de avanzados motores de gasolina y diésel ofrecen el equilibrio óptimo entre potencia y bajo consumo de combustible. Un avanzado sistema de transmisión PowerShift con levas de cambio optimiza las prestaciones y la eficiencia de todos ellos. Su estilo atrevido y sus asombrosos detalles, como la nueva rejilla delantera y los faros traseros renovados, se combinan para convertir el nuevo Focus en la combinación perfecta de funcionalidad y diseño. Gracias a su ingeniosa tecnología y a su diseño inteligente, el Focus hace que cada minuto que pasas en el vehículo sea lo más cómodo, seguro y divertido posible.  Las tecnologías innovadoras también te permiten mantenerte tan conectado a la carretera como a tu música, mensajes y llamadas en tus desplazamientos.'),
('Porsche', 'Macan', '8640 JTR', 1, 2016, 98000, 'Azul', 250, 'Gasolina', 'Manual', 0, 'B', 'Proximo', 'images/PorscheMacan.jpg', 'Macan Intensamente Porsche', 'Los rasgos deportivos son genes dominantes, lo que se aprecia también en la historia de todos los modelos Macan. Como los característicos faros delanteros, integrados en el capó del motor. Las grandes y potentes tomas de aire en el carenado delantero parecen estar esperando inhalar vida. Cada segundo.  El capó del motor se extiende hasta los cajas pasos de rueda, dotando al frontal de un aspecto ancho y fuerte. Una reminiscencia del legendario Porsche 917, que alcanzó innumerables victorias a principios de la década de los 70, por ejemplo, en las 24 Horas de Le Mans, donde mantuvo el récord de distancia durante 39 años. La línea lateral: típica de Porsche. Desde esta perspectiva, cada músculo parece estar en tensión, como una fiera a punto de saltar. La línea de techo desciende claramente hacia atrás, creando así un contorno típico del automovilismo deportivo con una elevada calidad aerodinámica. Nuestros diseñadores la denominan Porsche Flyline.  Otro atractivo visual son las molduras laterales del Macan. Aportan un acento deportivo y se inspiran en su forma en el 918 Spyder. De este modo, las proporciones de las puertas parecen más estrechas y los flancos claramente más esbeltos y deportivos. Están disponibles en Negro Mate, Negro Lava, esmaltadas en el color exterior correspondiente o, especialmente exclusivas, en carbono. En el exclusivo paquete exterior Turbo en Negro (pulido al brillo).'),
('Harley Davidson', 'Night Rod', '8673 JFR', 1, 2016, 20450, 'Dorado', 125, 'Gasolina', 'Manual', 2500, 'A', 'Compra', 'images/HarleyDavidsonNightRod.jpg', 'El caballo negro siempre ha sido el más misterioso.', 'Si crees que todo el metal oscurecido se ve siniestro, tienes razón. La moto Night Rod Special® es agresión pura y dura, desde la sección trasera tipo fast back hasta su aerodinámica delantera. Equipada con un motor V-Twin de 60°, con refrigeración líquida y lleno de detalles creados para darte auténticas sensaciones de moto de competición, como los dos árboles de levas en cabeza, 4 válvulas por cilindro y tomas de aire que alimentan el escape doble. Dicho de otro modo, cuando gires el acelerador empezarán a pasar muchas cosas. Y es mejor si todo pasa bajo la protección de la oscuridad. Como todo lo que montamos sobre una Harley-Davidson, el motor Revolution de 1250 cc que equipa nuestra gama V-Rod tiene su propia historia. Esta en concreto empieza en las pistas de carreras con el motor de la VR1000 oficial de carreras de la marca. Cuando empezó a rodar en la pista, los engranajes empezaron a girar para encontrar la manera de llevar estas altas prestaciones a las calles. Así que la historia nos lleva hasta Alemania y a la colaboración exclusiva con el legendario fabricante Porsche y su equipo de ingenieros de renombre mundial. ¿El resultado? Dos árboles de levas en culata. Cuatro válvulas en cabeza. Refrigeración líquida. Una entrega de potencia suave y delicada hasta la linea roja del cuentarrevoluciones. En pocas palabras: la combinación de potencia y par más lograda del mercado, en un diseño V-Twin revolucionario y revolucionado. No podía haber nada más único y exclusivo de Harley-Davidson que este motor.'),
('Mazda', '3', '9370 HTR', 1, 2015, 230, 'Rojo', 120, 'Diesel', 'Manual', 6500, 'B', 'Renting', 'images/Mazda3.jpg', 'Mazda3, un modelo deportivo y estimulante', 'El impresionante exterior del Mazda3 2017, representa a la perfección el galardonado diseño KODO – Alma del movimiento, transmite la idea de movimiento poderoso y dinámico en cada detalle. Gracias a los últimos avances en la Tecnología SKYACTIV, con el Mazda3 2017 hemos logrado un nivel de prestaciones definitivo, un nivel de disfrute de la conducción inigualable y un consumo de combustible excelente.   Las características de seguridad avanzadas de i-ACTIVSENSE te garantizan la mejor protección, mientras que el sistema MZD Connect, de información y entretenimiento de a bordo, te permite disfrutar de un acceso sencillo a toda tu música, redes sociales y mensajes de texto. El sistema i-ACTIVSENSE actúa en todos los niveles del Mazda3 ofreciéndote seguridad y confort. Estas tecnologías te ayudan en la conducción en vías concurridas, sin que por ello tu experiencia a bordo del Mazda sea menos divertida. Fabricado con la nueva tecnología SKYACTIV TECHNOLOGY, el Mazda3 2017 ofrece un ahorro de combustible, al tiempo que proporciona una experiencia de conducción divertida. Esta nueva tecnología, te permite experimentar una mayor sensación de control y unos excepcionales niveles de confort y sensibilidad durante la conducción. MZD Connect: sistema de conectividad móvil que te permite acceder a información del tráfico, a una amplia selección de radios por Internet y a tus cuentas de redes sociales. También te permite hacer un seguimiento del ahorro de combustible y te informa de las próximas fechas de mantenimiento.'),
('Tesla', 'Model S', '9625 KCR', 1, 2017, 65000, 'Rojo', 300, 'Electrico', 'Automatico', 0, 'B', 'Proximo', 'images/TeslaS.jpg', 'Model S, a medida del conductor', 'El Model S está diseñado para ser el sedán más seguro y emocionante en el camino. Con un rendimiento sin igual gracias al exclusivo sistema de propulsión totalmente eléctrico de Tesla, el Model S acelera de 0 a 100 km/h en tan sólo 2.7 segundos. El Model S incluye la posibilidad de conducción asistida Autopilot, diseñada para que pueda conducir en carretera no sólo con más seguridad, sino también sin estrés. El Model S es un vehículo para el conductor. El habitáculo combina la meticulosa ingeniería de ruidos con el exclusivo y silencioso sistema de propulsión de Tesla para obtener la dinámica de sonidos de un estudio de grabación. La joya del interior es la pantalla táctil de 17 pulgadas, que está orientada hacia el conductor e incluye modos diurno y nocturno para una mejor visibilidad sin distracciones. Coloca un extraordinario contenido a su alcance e incluye conectividad móvil, por lo tanto, podrá encontrar fácilmente su destino, canción favorita o un restaurante nuevo. El Model S de doble motor es una gran mejora con respecto a los sistemas convencionales de tracción a las cuatro ruedas. Con 2 motores, uno en la parte delantera y el otro en la parte trasera, el Model S controla de forma digital e independiente el par motor en las ruedas delanteras y traseras. El resultado es un control de tracción incomparable en todas las condiciones. Los automóviles convencionales con tracción a las cuatro ruedas utilizan mecanismos articulados complejos para distribuir la potencia de un solo motor a las 4 ruedas. Esos sistemas sacrifican la eficiencia en favor de lograr la tracción completa en todo tipo de condiciones. En cambio, el motor del Model S es más ligero, más pequeño y más eficiente que su equivalente convencional de tracción trasera, lo que proporciona mayor autonomía y una aceleración más rápida. El Model S Performance viene de serie con doble motor y tracción en las cuatro ruedas, combinando el alto desempeño del motor trasero con la alta eficiencia del motor delantero para lograr una aceleración asombrosa: de cero a 100 km por hora en 2.7 segundos.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`cliente`);

--
-- Indices de la tabla `adquiere`
--
ALTER TABLE `adquiere`
  ADD PRIMARY KEY (`vehiculo`),
  ADD KEY `cliente` (`cliente`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`cuenta`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`cliente`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`matricula`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `adquiere`
--
ALTER TABLE `adquiere`
  ADD CONSTRAINT `adquiere_ibfk_1` FOREIGN KEY (`vehiculo`) REFERENCES `vehiculos` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adquiere_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
