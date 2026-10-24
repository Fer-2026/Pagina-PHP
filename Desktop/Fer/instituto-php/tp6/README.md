# TP6: Diseño y creación del esquema relacional en MySQL

Base de datos `instituto_14_abril` del sitio web del Instituto Cooperativo 14 de Abril N.º 8001.

| Archivo | Contenido |
|---|---|
| [`NORMALIZACION.md`](NORMALIZACION.md) | Parte 1: normalización (1FN, 2FN, 3FN), listado de tablas con PK/FK y diagrama |
| [`schema.sql`](schema.sql) | Parte 2: script DDL (base, 9 tablas, claves, índices) |
| [`consultas.sql`](consultas.sql) | Parte 3: consultas de prueba CRUD |

## Cómo ejecutarlo (XAMPP)
1. Abrir el **XAMPP Control Panel** e iniciar **Apache** y **MySQL**.
2. Entrar a http://localhost/phpmyadmin
3. Pestaña **Importar**, elegir `tp6/schema.sql` y tocar **Importar**.
4. Repetir con `tp6/consultas.sql` (o pegar sus bloques en la pestaña **SQL** para ver los resultados de cada `SELECT`).

Por línea de comandos (Git Bash, desde la raíz del repositorio):
```bash
/c/xampp/mysql/bin/mysql.exe -u root --default-character-set=utf8mb4 < tp6/schema.sql
/c/xampp/mysql/bin/mysql.exe -u root --default-character-set=utf8mb4 -t < tp6/consultas.sql
```
`schema.sql` se puede ejecutar varias veces: reinicia las tablas.

## Capturas
![Tablas creadas](docs/capturas/01-tablas-creadas.png)
![Diseñador de phpMyAdmin](docs/capturas/02-disenador.png)
![SELECT con WHERE y ORDER BY](docs/capturas/03-select-where.png)
![INNER JOIN](docs/capturas/04-inner-join.png)
![UPDATE](docs/capturas/05-update.png)
![DELETE](docs/capturas/06-delete.png)
