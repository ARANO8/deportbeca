# DeportBeca — Sistema de Gestion Deportiva
# Universidad Mayor de San Andres - Division de Becas y Deportes

## North Star
DeportBeca es la plataforma oficial de gestion deportiva de la Universidad Mayor de San Andres (UMSA), la universidad publica mas grande de Bolivia. El sistema administra la pre-inscripcion de atletas, generacion de fixtures, calificaciones y gestion documental para eventos deportivos universitarios (Intercarreras, Olimpiadas, Interauxiliares).

Los usuarios primarios son administrativos, secretarias, profesores y el Director de Becas y Deportes. El sistema es desktop-first, denso en datos y requiere maxima claridad visual.

**Filosofia de Diseno**: "Precision Institucional con Espiritu Atletico"
- Verde UMSA como color dominante: autoridad, confianza, identidad universitaria
- Oro/amarillo como acento atletico: energia, accion, logro deportivo
- Layouts limpios y densos para gestion administrativa eficiente
- Responsive pero optimizado para escritorio

## Colors

### Paleta Principal
- **Verde UMSA (Primary):** `#006B3F` - Sidebar, headers, botones primarios, marca
- **Verde Oscuro:** `#004D2C` - Hover states, fondo del sidebar, estados presionados
- **Verde Claro (Container):** `#E8F5EE` - Fondos de exito, filas activas, highlights suaves
- **Oro Atletico (Secondary):** `#F9A825` - CTAs criticos, badges activos, navegacion activa
- **Oro Oscuro:** `#F57F17` - Hover sobre elementos dorados

### Paleta Neutral
- **Background:** `#F4F7F5` - Canvas principal, blanco calido con tinte verde suave
- **Surface:** `#FFFFFF` - Cards, modales, paneles
- **Surface Variant:** `#EEF2F0` - Rayas de tabla, paneles secundarios
- **Border:** `#C8D8D0` - Bordes sutiles, divisores
- **Text Primary:** `#0D1F17` - Texto principal, titulos
- **Text Secondary:** `#4A6358` - Labels, metadatos
- **Text Muted:** `#8FA89C` - Placeholders, texto de ayuda

### Colores Semanticos
- **Success:** `#1B873A` - Estado Habilitado
- **Warning:** `#E07B00` - Estado Observado
- **Error:** `#C62828` - Errores, acciones destructivas
- **Info:** `#0277BD` - Informacion neutral

## Typography

- **Inter** como fuente unica del sistema
- Display: 2rem / 700 - Metricas hero
- Headline: 1.375rem / 600 - Titulos de pagina
- Title: 1rem / 600 - Headers de seccion
- Body: 0.875rem / 400 - Texto general, tablas
- Label: 0.75rem / 600 - Badges, chips de estado
- Caption: 0.6875rem / 400 - Metadatos

## Layout

- Sidebar 240px fijo a la izquierda, fondo verde oscuro UMSA
- Topbar 56px blanco con breadcrumb + alertas + usuario
- Content area max-width 1440px, padding 24px
- Cards blancas, radius 8px, sombra suave verde

## Shapes

- Border Radius: 8px para cards, botones, inputs
- Pills: 20px radius para badges de estado
- Tablas con header verde oscuro

## Components

### Sidebar
- Fondo #004D2C
- Logo UMSA + "Becas y Deportes"
- Item activo: borde izquierdo dorado 3px + texto dorado + fondo dorado translucido
- Iconos FontAwesome 5

### Status Badges
- Habilitado: fondo #E8F5EE, texto #1B873A
- Observado: fondo #FFF3E0, texto #E07B00
- Pendiente: fondo #EEF2F0, texto #4A6358

### KPI Cards
- Card blanca con acento de borde izquierdo semantico
- Numero grande Display 700
- Icono contextual

### Tablas
- Header: fondo #004D2C blanco
- Filas alternadas blanco / #F4F7F5
- Hover: #E8F5EE

### Botones
- Primario: #006B3F blanco, hover #004D2C
- CTA: #F9A825 texto oscuro
- Danger: #C62828 blanco

## Modulos
1. Dashboard con KPIs de inscripciones y fixture
2. Archivador de pre-inscripciones
3. Generacion y gestion de Fixture
4. Calificaciones y tabla de posiciones
5. Configuracion de Eventos
6. Gestion de Usuarios
7. Roles y Privilegios
8. Portal Publico de resultados
