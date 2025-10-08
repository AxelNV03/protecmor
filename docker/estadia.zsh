#!/bin/zsh
# Pull
# Actualiza la rama actual con los últimos cambios de 'dev' usando rebase.
ggdev() {
     # --- VALIDACIONES INICIALES ---
          if ! git rev-parse --is-inside-work-tree > /dev/null 2>&1; then
               echo "❌ Error: No estás en un repositorio de Git."
               return 1
          fi
     
     local current_branch=$(git rev-parse --abbrev-ref HEAD)
     local commit_message="$1"
     
     if [ "$current_branch" = "dev" ]; then
          echo "🛑 ¡Alto! No puedes actualizar la rama 'dev' sobre sí misma."
          return 1
     fi
     
     if ! git diff --quiet; then
          echo "🛑 ¡Alto! Tienes cambios sin guardar en este proyecto."
          echo "   Haz commit o descarta los cambios antes de continuar."
          return 1
     fi
     
     # --- PASO 1: COMMIT Y PUSH OPCIONAL (REUTILIZANDO GPUSH) ---
     if [ -n "$commit_message" ]; then
          echo "💬 Mensaje detectado. Haciendo commit y push en '$current_branch' primero..."
          if ! gpush "$commit_message"; then
          echo "❌ Error durante el push inicial. Proceso cancelado."
          return 1
          fi
     fi
     
     # --- PASO 2: SECUENCIA DE ACTUALIZACIÓN CON REBASE ---
     echo "📥 Obteniendo los últimos cambios de 'dev' desde origin..."
     git fetch origin dev
     
     echo "🔄 Rebasando tu rama ('$current_branch') sobre 'origin/dev'..."
     git rebase origin/dev
     if [ $? -ne 0 ]; then
          echo "🛑 ¡Conflicto de rebase detectado!"
          echo "   Por favor, resuelve los conflictos y luego ejecuta 'git rebase --continue'."
          echo "   Cuando termines, deberás hacer push usando la opción '--force-with-lease'."
          return 1
     fi
     
     # --- PASO 3: PUSH FORZADO (SEGURO) DESPUÉS DEL REBASE ---
     echo "🚀 Reescribiendo el historial en el remoto para coincidir con el rebase..."
     git push --force-with-lease origin "$current_branch"
     
     echo "✅ ¡Proceso completado! Tu rama '$current_branch' está actualizada con 'dev'."
}


# Función para hacer pull en un proyecto Git específico
# Integra la rama actual en 'dev' para el proyecto en el que te encuentras.
# Uso (solo integrar): gcdev
# Uso (commit, push e integrar): gcdev "mensaje de commit"
gcdev() {
     # --- VALIDACIONES INICIALES ---
     if ! git rev-parse --is-inside-work-tree > /dev/null 2>&1; then
          echo "❌ Error: No estás en un repositorio de Git."
          return 1
     fi

     local source_branch=$(git rev-parse --abbrev-ref HEAD)
     local commit_message="$1"

     if [ "$source_branch" = "dev" ]; then
          echo "🛑 ¡Alto! Ya estás en la rama 'dev'. No puedes integrar 'dev' en sí misma."
          return 1
     fi

     echo "🚙 Iniciando proceso para integrar '$source_branch' en 'dev'..."
     if ! git diff --quiet; then
          echo "🛑 ¡Alto! Tienes cambios sin guardar en este proyecto."
          echo "   Haz commit o descarta los cambios antes de continuar."
          return 1
     fi

     # --- PASO 1: COMMIT Y PUSH OPCIONAL (REUTILIZANDO GPUSH) ---
     if [ -n "$commit_message" ]; then
          echo "💬 Mensaje detectado. Haciendo commit y push en '$source_branch' primero..."
          # Como ambas funciones son flexibles, ahora podemos llamar a gpush directamente.
          if ! gpush "$commit_message"; then
          echo "❌ Error durante el push de la rama $source_branch. Proceso cancelado."
          return 1
          fi
     fi

     # --- PASO 2: SECUENCIA DE INTEGRACIÓN ---
     echo "🔄 Cambiando a la rama 'dev' y actualizando..."
     git switch dev
     git pull origin dev
     git fetch origin # Asegura que tenemos las últimas referencias

     echo "🔗 Fusionando (merge) '$source_branch' en 'dev'..."
     git merge origin/"$source_branch"
     if [ $? -ne 0 ]; then
          echo "🛑 ¡Conflicto de merge detectado!"
          echo "   Por favor, resuelve los conflictos en este directorio y haz el push a 'dev' manualmente."
          return 1
     fi

     echo "🚀 Subiendo la rama 'dev' actualizada..."
     git push origin dev

     echo "↩️ Regresando a tu rama de origen ('$source_branch')..."
     git switch "$source_branch"

     echo "✅ ¡Proceso completado! La rama '$source_branch' fue integrada en 'dev'."
}


# Restablece base en protecmor
dbreset(){
     mysql -u user -p1234 < /home/developer/script.sql
     echo "Script de protecmor ejecutado."
     php artisan migrate
     echo "Migraciones ejecutadas."
     php artisan db:seed --class=RoleSeeder
     php artisan db:seed --class=UserSeeder
     echo "Seeders base ejecutados."
}
#---------------------------------------------------------------------------
# Función seed
# Función principal para listar y ejecutar seeders
seed() {
     # Inicializamos el arreglo de seeders
     seeders=()

     # Usamos un glob pattern de zsh para obtener solo los archivos .php en la carpeta seeders
     for file in database/seeders/*.php; do
          # Eliminar la ruta y la extensión .php
          seeder="${file##*/}"       # Obtiene solo el nombre del archivo
          seeder="${seeder%.php}"     # Elimina la extensión .php
          # Agregar el nombre al arreglo
          seeders+=("$seeder")
     done

    # Mostrar las opciones al usuario
    echo "Seeders disponibles:"
    echo "1. Todos"
    index=2  # Iniciar el índice desde 2 (ya que 1 es para "Todos")
    for seeder in "${seeders[@]}"; do
        echo "$index. $seeder"
        index=$((index + 1))  # Incrementar el índice
    done

    # Solicitar la selección del usuario
    echo -n "Selecciona una opción (o 'q' para cancelar): "
    read REPLY

    # Si el usuario elige "q" o "Q", cancelar la operación
    if [[ "$REPLY" =~ ^[Qq]$ ]]; then
        echo "Operación cancelada."
        return
    fi

    # Verificamos si el usuario eligió "1" para ejecutar todos los seeders
    if [[ "$REPLY" -eq 1 ]]; then
        echo "Ejecutando todos los seeders..."
        php artisan db:seed
        echo "Todos los seeders han sido ejecutados."
    elif [[ "$REPLY" -ge 2 && "$REPLY" -lt $(($index)) ]]; then
        # Si el número es válido, ejecutamos el seeder correspondiente
        seeder="${seeders[$REPLY-1]}"
        echo "Ejecutando el seeder: $seeder"

        # Ejecutar el seeder sin la extensión .php
        php artisan db:seed --class="$seeder"
        echo "Seeder $seeder ejecutado."
    else
        echo "Opción inválida. Intenta de nuevo."
    fi
}

start(){
    php artisan serve --host=0.0.0.0 --port=8000 &
    npm run dev -- --host 0.0.0.0 --port 5173 &
}

database(){
    echo "--- Conectando a la base de datos como 'user'"
    mariadb -h db -u user -p1234 protecmor
}

startconf() {
    echo "--- Ajustando permisos de .ssh para Git/SSH..."
    if [ -d "/home/developer/.ssh" ]; then
        chown -R developer:developer /home/developer/.ssh
        chmod 700 /home/developer/.ssh
        [ -f "/home/developer/.ssh/id_ed25519" ] && chmod 600 /home/developer/.ssh/id_ed25519
        [ -f "/home/developer/.ssh/id_ed25519.pub" ] && chmod 644 /home/developer/.ssh/id_ed25519.pub
        echo "Permisos de .ssh ajustados correctamente."
    else
        echo "No se encontró la carpeta .ssh, se omitió el ajuste de permisos."
    fi

    echo "--- Instalando dependencias de Composer..."
    composer install || { echo "Error: Composer falló"; return 1; }

    echo "--- Instalando dependencias de NPM..."
    npm install || { echo "Error: NPM falló"; return 1; }
    npm install -D sass || { echo "Error: NPM dev-dependency falló"; return 1; }

    echo "--- Generando llave de la aplicación..."
    php artisan key:generate || { echo "Error: Falló key:generate"; return 1; }

    echo "--- Asignando permisos y Ejecutando script .sql..."
    mariadb -h db -u root -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'user'@'%' IDENTIFIED BY '1234' WITH GRANT OPTION; FLUSH PRIVILEGES;" || { echo "Error: Falló dando permisos sql"; return 1; }
    
    echo "--- Ejecutando script .sql..."
    mariadb -h db -u user -p1234 < /home/developer/project/script.sql || { echo "Error: Falló import SQL"; return 1; }

    echo "--- Ejecutando migraciones..."
    php artisan migrate || { echo "Error: Falló migrate"; return 1; }

    echo "--- Ejecutando seeders base..."
    php artisan db:seed --class=RoleSeeder
    php artisan db:seed --class=UserSeeder

    echo "--- Configurando llaves Git/SSH..."
    if [ -d "$HOME/.ssh" ] && [ -n "$(ls -A "$HOME/.ssh" 2>/dev/null)" ]; then
        echo "Iniciando ssh-agent y cargando llaves..."
        eval "$(ssh-agent -s)"
        if [ -f "$HOME/.ssh/id_ed25519" ]; then
            ssh-add "$HOME/.ssh/id_ed25519"
        elif [ -f "$HOME/.ssh/id_rsa" ]; then
            ssh-add "$HOME/.ssh/id_rsa"
        else
            echo "No se encontró llave SSH conocida."
        fi
    else
        echo "No hay llaves SSH en $HOME/.ssh, se omite configuración de Git/SSH."
    fi

    echo "✅ ¡Proyecto configurado y listo para usar! ✅"
}
