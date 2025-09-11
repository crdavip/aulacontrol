# 👨‍🏫 SENA | AulaControl

Proyecto creado para el SENA, esta plataforma sirve para llevar el control de los equipos de cada ambiente, la asistencia de los Aprendices y el prestamo de equipos u otros objetos dentro del centro por medio de sistema QR.

## 🖥️ Demo

🔗 [Ver Demo en vivo](https://aulacontrol.free.nf/)  

---

## 🧰 Tecnologías utilizadas

- **Illustrator** – Creación de gráficos vectoriales, y elementos de diseño para UI/UX.
- **HTML** – Estructurar y presentar contenido en la web.
- **CSS** – Estilizar la apariencia de las páginas web.
- **JavaScript** – Permite añadir interactividad y dinamismo a las páginas web.
- **PHP** – Lenguaje de programación del lado del servidor, ideal para el desarrollo web dinámico.
- **MySQL** - Sistema de gestión de bases de datos relacionales de código abierto.

---

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/crdavip/aulacontrol
cd aulacontrol
```

### 2. Crear la base de datos en MySQL

```bash
Desde phpMyAdmin crea una nueva BD con el nombre aulacontrol
```

### 3. Importar el SQL

```bash
En phpMyAdmin importa el SQL que está en data/aulacontrol.sql
```

### 4. Configurar la BD

```bash
Ve a model/db.php y cambia estos valores por los de tu BD:

private $host = "localhost";
private $username = "root";
private $password = "";
private $database = "aulacontrol";
```

### 5. Iniciar el servidor

```bash
Ya esta lista para ser usada en un servidor con Apache
```

---

## 🥷 Accesos de Prueba

```bash
100000     # Usuario Instructor
100002     # Usuario Aprendiz
123456     # Contraseña
```

---

## 🛠️ Configuración Adicional

```bash
- GD      # Activar el GD de PHP en tu servidor "php.ini"
```

---

## 👨‍💻 Autor
Desarrollado con ❤️ por **Cristian David**
🔗 [GitHub](https://github.com/crdavip) · [LinkedIn](https://www.linkedin.com/in/crdavip/)