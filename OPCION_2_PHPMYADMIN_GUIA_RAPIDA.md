# 🎯 OPCIÓN 2: PHPMYADMIN SQL - GUÍA RÁPIDA

## 📋 RESUMEN

**Sin Terminal, sin complejidades. Solo SQL en phpMyAdmin.**

```
1. Descargar archivo SQL
2. Ir a phpMyAdmin
3. Copiar y pegar
4. Ejecutar
5. ¡Listo!
```

---

## 📥 PASO 1: DESCARGAR ARCHIVO SQL

### Opción A: Desde tu computadora
El archivo **`insert_maestras_phpmyadmin.sql`** ya está generado (33.5 KB)

Busca en tu carpeta del proyecto:
```
C:\xampp\htdocs\CEOGestion\insert_maestras_phpmyadmin.sql
```

### Opción B: Generar nuevo archivo
Si necesitas regenerarlo, ejecuta en tu PC:

```bash
cd C:\xampp\htdocs\CEOGestion
php generate_sql_inserts.php
```

Resultado:
```
✅ ARCHIVO GENERADO
Nombre: insert_maestras_phpmyadmin.sql
Tamaño: 33.47 KB
```

---

## 🌐 PASO 2: IR A PHPMYADMIN EN CPANEL

### 1. Acceder a cPanel
```
https://tu-dominio.com:2083
Usuario: tu_usuario_cpanel
Password: tu_password
```

### 2. Buscar phpMyAdmin
En cPanel, busca el icono: **phpMyAdmin** o **Databases**

### 3. Seleccionar Base de Datos
- Base de datos: **`simotec_ceogestion_prod`**
- Usuario: el usuario que creaste

Click en la BD para abrirla.

---

## 📝 PASO 3: COPIAR Y PEGAR SQL

### 1. Abre el archivo `insert_maestras_phpmyadmin.sql`
Con cualquier editor de texto:
- Notepad++
- VS Code
- Visual Studio
- O el Bloc de notas normal

### 2. Selecciona TODO el contenido
```
Ctrl + A
```

### 3. Copia
```
Ctrl + C
```

### 4. En phpMyAdmin, click en: SQL

En la interfaz de phpMyAdmin:
- Arriba verás varias pestañas: Structure, SQL, Export, etc.
- Click en: **SQL**

### 5. Pega en la caja de texto
```
Ctrl + V
```

Deberías ver el contenido SQL como:

```sql
-- INSERTS PARA PHPYADMIN
SET FOREIGN_KEY_CHECKS=0;

DELETE FROM `paises`;
INSERT INTO `paises` VALUES (1,'170','Colombia',...);
...
DELETE FROM `departamentos`;
INSERT INTO `departamentos` VALUES (1,'05','Antioquia',...);
...
```

---

## ⚙️ PASO 4: EJECUTAR SQL

### En phpMyAdmin

1. **Scroll hacia abajo** hasta el botón **Ejecutar** (o **Go**)
2. **Click: Ejecutar**
3. **Espera** a que termine (puede tardar 5-10 segundos)

### Resultado esperado

```
✅ MySQL returned an empty result set (i.e. zero rows).
✅ La consulta se ha ejecutado correctamente.
✅ 118 consultas ejecutadas
```

---

## ✅ PASO 5: VERIFICAR RESULTADO

### Opción A: En phpMyAdmin

1. En phpMyAdmin, panel izquierdo
2. Expande tu BD: `simotec_ceogestion_prod`
3. Haz click en tabla: **paises**
4. Deberías ver: **1 registro**

### Opción B: Verificar todas las maestras

Desde phpMyAdmin, en la pestaña SQL, ejecuta:

```sql
SELECT 'paises' as tabla, COUNT(*) as registros FROM paises
UNION ALL
SELECT 'departamentos', COUNT(*) FROM departamentos
UNION ALL
SELECT 'municipios', COUNT(*) FROM municipios
UNION ALL
SELECT 'barrios', COUNT(*) FROM barrios
UNION ALL
SELECT 'tipos_equipos', COUNT(*) FROM tipos_equipos
UNION ALL
SELECT 'categorias', COUNT(*) FROM categorias
UNION ALL
SELECT 'estado_servicios', COUNT(*) FROM estado_servicios
UNION ALL
SELECT 'roles', COUNT(*) FROM roles
UNION ALL
SELECT 'permissions', COUNT(*) FROM permissions
UNION ALL
SELECT 'role_permissions', COUNT(*) FROM role_permissions;
```

Resultado esperado:

```
| tabla               | registros |
|-------------------|-----------|
| paises            | 1         |
| departamentos     | 32        |
| municipios        | 36        |
| barrios           | 90        |
| tipos_equipos     | 15        |
| categorias        | 5         |
| estado_servicios  | 6         |
| roles             | 3         |
| permissions       | 32        |
| role_permissions  | 41        |
```

---

## ⚠️ SI HAY ERROR

### Error: "Cannot truncate a table referenced in a foreign key constraint"

**SOLUCIÓN:**

En phpMyAdmin, ejecuta PRIMERO esto:

```sql
SET FOREIGN_KEY_CHECKS=0;
```

Luego copia y pega TODO el archivo SQL como antes.

Al final, ejecuta:

```sql
SET FOREIGN_KEY_CHECKS=1;
```

---

### Error: "Syntax error"

**Causa probable:** Copiar solo parte del archivo

**SOLUCIÓN:**
1. Abre de nuevo el archivo `insert_maestras_phpmyadmin.sql`
2. Asegúrate de copiar TODO (Ctrl+A en el editor)
3. Pega de nuevo

---

### Error: "Table already exists"

**Solución:** Las maestras ya estaban presentes

**Verificar:** El archivo incluye `DELETE FROM tabla` que limpia primero, así que es seguro ejecutar múltiples veces.

---

## 🎉 ¡LISTO!

Una vez que veas "✅ Query executed successfully", tu BD está restaurada.

### Próximos pasos:

1. ✅ Accede a: **https://tu-dominio.com/**
2. ✅ Deberías ver: Página de login
3. ✅ Login con: `admin@ceogestion.com` / `password123`
4. ✅ Dashboard debe cargar sin errores 500

---

## 📊 COMPARATIVA: ¿Por qué phpMyAdmin SQL?

| Aspecto | Opción 1 (Panel Web) | Opción 2 (SQL) |
|--------|-------------------|-----------|
| Complejidad | Media | 🟢 Muy fácil |
| Requiere .env | Sí | 🟢 No |
| Requiere routing | Sí | 🟢 No |
| Requiere PHP activo | Sí | 🟢 No |
| Directo a BD | No | 🟢 Sí |
| Auditoria (ves el SQL) | No | 🟢 Sí |
| Tiempo | ~30 seg | 🟢 ~5 min |
| **Recomendado si** | Usas Panel | **SIN Terminal** |

---

## ✅ CHECKLIST

- [ ] Descargué `insert_maestras_phpmyadmin.sql`
- [ ] Accedí a cPanel > phpMyAdmin
- [ ] Seleccioné BD: `simotec_ceogestion_prod`
- [ ] Fui a pestaña: SQL
- [ ] Copié TODO el contenido del archivo
- [ ] Pegué en phpMyAdmin
- [ ] Ejecuté (Go/Ejecutar)
- [ ] Vi: "✅ Query executed successfully"
- [ ] Verifiqué maestras con SELECT COUNT
- [ ] Probé login: admin@ceogestion.com / password123
- [ ] Dashboard cargó sin errores 500

---

**ESTADO:** ✅ Listo para ejecutar  
**FECHA:** 6 Mayo 2026  
**DURACIÓN:** ~5 minutos
