# 🎉 CEOGESTION v2.0 - Sistema de Gestión de Servicios TI

**Sistema completo de gestión de servicios tecnológicos con portal del cliente, múltiples roles de usuario y seguimiento en tiempo real.**

---

## ⚡ Inicio Rápido

### Requisitos
- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js 18+

### Instalación (5 minutos)
```bash
# 1. Clonar/descargar proyecto
cd c:\xampp\htdocs\CEOGestion

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar .env (ya debe estar configurado)
cp .env.example .env
php artisan key:generate

# 4. Base de datos
php artisan migrate:fresh --seed

# 5. Compilar assets
npm run build

# 6. Ejecutar servidor
php artisan serve

# ¡Listo! Accede a http://localhost:8000
```

---

## 👤 Accesos de Prueba

### Internos (Login)
| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@ceogestion.com | password123 | Admin |
| coordinador@ceogestion.com | password123 | Coordinador |
| operario1@ceogestion.com | password123 | Operario |
| tecnico1@ceogestion.com | password123 | Técnico |

### Portal del Cliente (Token)
```
URL: http://localhost:8000/portal/acceso/{TOKEN}
Sin contraseña requerida
Ver GUIA_RAPIDA.md para obtener tokens
```

---

## 🎯 Características Principales

### ✅ Estructura de Datos
- Empresa con múltiples clientes
- Clientes con múltiples sedes (en diferentes municipios)
- Sedes con áreas y equipos
- Contratos con SLA definido
- Servicios con seguimiento completo

### ✅ Dos Formas de Registrar Servicios
1. **Vía Telefónica:** Operario llena el formulario cuando cliente llama
2. **Portal del Cliente:** Cliente accede con token y crea directamente

### ✅ Seguimiento en Tiempo Real
- Estados: REPORTADO → EN_ESPERA → EN_PROCESO → RESUELTO → CERRADO
- Historial completo de acciones
- SLA automático calculado
- Auditoría de cambios

### ✅ Descarga de PDF
- Cliente puede descargar atenciones completadas
- PDF con información completa
- Reimprimible en cualquier momento

### ✅ Roles de Usuario
- **Admin:** Acceso completo, estadísticas, reportes
- **Coordinador:** Asigna servicios a técnicos
- **Operario:** Registra servicios cuando clientes llaman
- **Técnico:** Atiende servicios asignados
- **Cliente:** Acceso portal con token

---

## 📁 Documentación

| Archivo | Contenido |
|---------|-----------|
| **GUIA_RAPIDA.md** | ⭐ Comienza aquí - Cómo ejecutar |
| **DOCUMENTACION_COMPLETA.md** | Guía técnica exhaustiva |
| **CREDENCIALES_DE_ACCESO.md** | Usuarios y tokens |
| **REQUISITOS_CUMPLIDOS.md** | Verificación de requisitos |
| **PROYECTO_COMPLETADO.md** | Resumen del proyecto |

---

## 📊 Tecnología

- **Framework:** Laravel 11
- **Base de Datos:** MySQL 5.7+
- **Frontend:** Blade Templates, Tailwind CSS
- **DataTables:** v1.13.7 para listas
- **PDF:** Generado dinámicamente
- **JavaScript:** jQuery 3.6.0

---

## 🚀 Flujos Principales

### 1. Crear Servicio (Operario)
```
Login → Servicios → Crear → Seleccionar Cliente
                                     ↓
                            Sistema carga equipos
                                     ↓
                            Seleccionar equipo
                                     ↓
                            Sistema carga contrato
                                     ↓
                            Validar SLA automático
                                     ↓
                            Crear servicio
```

### 2. Portal del Cliente
```
Acceder con token → Dashboard (estadísticas)
                            ↓
                    Ver contratos activos
                            ↓
                    Ver equipos disponibles
                            ↓
                    Ver servicios reportados
                            ↓
                    Crear nuevo servicio
                            ↓
                    Descargar PDF de atención
```

---

## 📞 Soporte

**CEOGESTION SAS**
- Email: info@ceogestion.com
- Teléfono: (1) 2345678
- Sitio: www.ceogestion.com

---

## 🧪 Comandos Útiles

```bash
# Reset base de datos (cuidado - borra todo)
php artisan migrate:fresh --seed

# Ver usuarios creados
php artisan tinker
>>> User::all();

# Limpiar caches
php artisan config:clear && php artisan view:clear

# Ver logs en tiempo real
tail -f storage/logs/laravel.log
```

---

## ✅ Estado

- ✅ Sistema completado
- ✅ Todas las funcionalidades implementadas
- ✅ Base de datos migrada
- ✅ Usuarios de prueba creados
- ✅ Documentación completa
- ✅ Listo para producción

---

## 📈 Próximas Mejoras

- [ ] Notificaciones por email/SMS
- [ ] Dashboard de coordinador con KPIs
- [ ] Reportes de SLA compliance
- [ ] Asignación automática de servicios
- [ ] API REST pública
- [ ] Aplicación móvil

---

## 📝 Licencia

Este proyecto es propiedad de CEOGESTION SAS. Todos los derechos reservados.

---

**¿Primera vez?** → Abre [GUIA_RAPIDA.md](GUIA_RAPIDA.md) ⭐

**¿Necesitas info técnica?** → Lee [DOCUMENTACION_COMPLETA.md](DOCUMENTACION_COMPLETA.md)

---

**CEOGESTION v2.0 - 2026**


Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
