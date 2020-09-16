<h1>Instalacion del proyecto</h1>

<p>Este proyecto requiere de composer para instalar todas las dependencias necesarias para ejecutar este proyecto, si ya tiene composer instalado omita este paso, si no vaya al siguiente enlace para instalarlo Instalar composer - <a href="https://getcomposer.org/download/">Composer</a></p>

<h2>Dentro del proyecto ejecutar los siguientes comandos: </h2>
<ul>
    <li>composer install</li>
    <li>cp .env.example .env</li>
    <li>php artisan key:generate</li>
    <li>php artisan jwt:secret</li>
</ul>

<h2>Para correr la base de datos: </h2>

<p>Para poder conectar laravel a la base de datos en el archivo .env generado anteriormente localice las siguientes lineas</p>

<ul>
    <li><strong>DB_DATABASE: </strong>En esta linea colocara de su nombre de su base de datos (recuerde haber creado la base de datos con anterioridad en su gestor de base de datos)</li>
    <li><strong>DB_USERNAME: </strong>En esta linea colocara el usuario de su base de datos</li>
    <li><strong>DB_PASSWORD: </strong>En esta linea colocara la contraseña para ese usuario de su base de datos</li>
</ul>

<p>Puede usar la siguiente configuracion recomendada</p>
<ul>
    <li><strong>DB_DATABASE: </strong>likeu-test</li>
    <li><strong>DB_USERNAME: </strong>root</li>
    <li><strong>DB_PASSWORD: </strong></li>
</ul>

<h2>Para correr y crear todas las tablas en la base de datos: </h2>
<p>php artisan migrate --seed</p>
<p>si presenta problemas con el comando anterio pruebe con: </p> 
<p>php artisan migrate:fresh --seed o php artisan migrate:refresh --seed</p>
