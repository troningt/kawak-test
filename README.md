# Sistema de Gestión de Documentos

Sistema CRUD para gestión de documentos con generación automática de códigos únicos.

## Requisitos

- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Apache con mod_rewrite habilitado

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/troningt/kawak-test
cd kawak-test
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar base de datos

Editar el archivo `config/database.php` con las credenciales de tu base de datos:

```php
return [
    'host' => 'localhost',
    'database' => 'doc_management',
    'username' => 'tu_usuario',
    'password' => 'tu_contraseña',
    ...
];
```

### 4. Crear base de datos y tablas

Ejecutar los scripts SQL en el siguiente orden:

```bash
mysql -u tu_usuario -p < database/schema.sql
mysql -u tu_usuario -p < database/seed.sql
```

**Ubicación de scripts SQL:**
- DDL (estructura): `database/schema.sql`
- DML (datos): `database/seed.sql`

### 5. Configurar servidor web

#### Opción A: Servidor incorporado de PHP (Desarrollo)

```bash
cd public
php -S localhost:8000
```

Acceder a: `http://localhost:8000`

#### Opción B: Apache (Producción)

Configurar el DocumentRoot apuntando a la carpeta `public/`:

```apache
<VirtualHost *:80>
    DocumentRoot "/ruta/al/proyecto/public"
    <Directory "/ruta/al/proyecto/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Credenciales de Acceso

**Usuario:** `admin`  
**Contraseña:** `admin123`

## Estructura del Proyecto

```
document-management/
├── config/              # Configuración
│   └── database.php
├── src/
│   ├── Controllers/     # Controladores (AuthController, DocumentController)
│   ├── Models/          # Modelos (Database, Documento, Proceso, TipoDoc)
│   ├── Services/        # Servicios (DocumentCodeGenerator)
│   └── Views/           # Vistas
│       ├── layouts/
│       └── documents/
├── public/              # Punto de entrada público
│   ├── index.php
│   └── .htaccess
├── database/            # Scripts SQL
│   ├── schema.sql
│   └── seed.sql
├── vendor/              # Dependencias de Composer
├── composer.json
├── .gitignore
└── README.md
```

## Funcionalidades

- **Autenticación:** Login y logout de usuarios
- **Listar documentos:** Tabla con todos los documentos registrados
- **Buscar:** Búsqueda por nombre, contenido o código
- **Crear:** Registro de nuevos documentos con código único automático
- **Editar:** Actualización de documentos (recalcula código si cambia tipo/proceso)
- **Eliminar:** Eliminación de documentos

## Generación de Códigos

El sistema genera códigos únicos con el formato:

```
[TIP_PREFIJO]-[PRO_PREFIJO]-[CONSECUTIVO]
```

**Ejemplo:**
- Tipo: Instructivo (INS)
- Proceso: Ingeniería (ING)
- Código generado: `INS-ING-1`

### Características del código:

- Los consecutivos son únicos por combinación de Tipo y Proceso
- Si editas un documento y cambias su tipo o proceso, se genera un nuevo código
- No se reutilizan consecutivos ya asignados

## Arquitectura

El proyecto sigue los principios:

- **MVC:** Separación en Modelos, Vistas y Controladores
- **PSR-4:** Autoloading de clases
- **SRP:** Responsabilidad única por clase
- **Inyección de Dependencias:** En servicios y controladores
- **UTF-8:** Toda la aplicación usa codificación UTF-8

## Seguridad

- Uso de PDO con prepared statements (prevención de SQL Injection)
- Validación de sesiones en todas las rutas protegidas
- Escape de datos con `htmlspecialchars()` en vistas
- Transacciones de base de datos para operaciones críticas

## Base de Datos

### Tablas

- **PRO_PROCESO:** Procesos organizacionales (precargada con 5 registros)
- **TIP_TIPO_DOC:** Tipos de documentos (precargada con 5 registros)
- **DOC_DOCUMENTO:** Documentos principales

### Relaciones

- `DOC_DOCUMENTO.DOC_ID_TIPO` → `TIP_TIPO_DOC.TIP_ID`
- `DOC_DOCUMENTO.DOC_ID_PROCESO` → `PRO_PROCESO.PRO_ID`

## Pruebas

Para probar la aplicación:

1. Iniciar sesión con las credenciales proporcionadas
2. Crear documentos seleccionando diferentes tipos y procesos
3. Verificar que los códigos se generen correctamente
4. Editar documentos cambiando tipo/proceso para verificar regeneración de código
5. Buscar documentos por diferentes criterios
6. Eliminar documentos

## Tecnologías Utilizadas

- PHP 7.4+
- MySQL
- Composer (Autoloading PSR-4)
- PDO para acceso a datos
- Arquitectura MVC
