# iCommerce - Instrucciones de Instalación y Prueba

## 🔧 Requisitos Previos
- XAMPP instalado y ejecutándose
- Node.js y npm instalados
- PHP y Composer (viene con XAMPP)

## 📂 Configuración del Backend (Laravel)

### 1. Configurar Base de Datos
1. Abre XAMPP Control Panel
2. Inicia **Apache** y **MySQL**
3. Ve a http://localhost/phpmyadmin
4. Crea una nueva base de datos llamada `icommerce`

### 2. Configurar Laravel
```bash
# Navega al directorio backend
cd /mnt/c/Users/crist/Downloads/mi-proyecto/backend

# Instalar dependencias (si no están instaladas)
composer install

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Iniciar servidor Laravel
php artisan serve
```

El backend estará disponible en: http://localhost:8000

## 🌐 Configuración del Frontend (React)

### 1. Instalar dependencias
```bash
# Navega al directorio frontend
cd /mnt/c/Users/crist/Downloads/mi-proyecto/frontend

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm start
```

El frontend estará disponible en: http://localhost:3000

## ✅ Pruebas de Funcionalidad

### 1. Página Principal
- ✅ Deberías ver "iCommerce - Tienda de Tecnología"
- ✅ Botones "Iniciar Sesión" y "Crear Cuenta" en la esquina superior derecha
- ✅ Secciones de productos: Smartphones, Laptops, Gaming, Accesorios

### 2. Registro de Usuario
- ✅ Haz clic en "Crear Cuenta"
- ✅ Llena todos los campos obligatorios:
  - Nombre de usuario (mínimo 3 caracteres)
  - Nombre completo
  - Email válido
  - Teléfono (mínimo 10 dígitos)
  - Contraseña (mínimo 8 caracteres)
  - Confirmar contraseña
- ✅ El sistema validará que el username sea único
- ✅ Se creará la cuenta y redirigirá al inicio

### 3. Iniciar Sesión
- ✅ Haz clic en "Iniciar Sesión"
- ✅ Puedes usar email o nombre de usuario
- ✅ Ingresa tu contraseña
- ✅ Te redirigirá al inicio como usuario logueado

### 4. Perfil de Usuario
- ✅ Una vez logueado, verás "Hola, [tu nombre]"
- ✅ Haz clic en "Mi Perfil"
- ✅ Podrás actualizar todos tus datos personales
- ✅ Cambiar contraseña (opcional)

## 🚀 Instalación Rápida

Ejecuta uno de estos scripts para configurar todo automáticamente:

**Windows:**
```bash
# Doble clic en setup.bat
```

**Linux/Mac:**
```bash
./setup.sh
```

## 🐛 Solución de Problemas

### ❌ Error CORS - "blocked by CORS policy"
**Solución aplicada:** Ya está configurado el CORS para permitir localhost:3000

Si aún tienes problemas:
```bash
cd backend
php artisan config:cache
php artisan serve
```

### Error de Conexión a Base de Datos
- Verifica que MySQL esté ejecutándose en XAMPP
- Confirma que la base de datos 'icommerce' existe
- Revisa las credenciales en `backend/.env`

### Error 404 en API
- Asegúrate que el backend Laravel esté ejecutándose en localhost:8000
- Verifica que las rutas estén configuradas correctamente

### Componentes no se cargan en React
- Verifica que todos los archivos en `/frontend/src/components/` existan
- Ejecuta `npm start` para recargar el servidor de desarrollo

## 📱 URLs de la Aplicación

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api/
- **phpMyAdmin**: http://localhost/phpmyadmin

¡Tu tienda de tecnología iCommerce está lista para usar! 🚀