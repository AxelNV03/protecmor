# ==============================================================================
# Dockerfile para Entorno de Desarrollo Laravel en Arch Linux (Versión Final v4)
# ==============================================================================
FROM archlinux:latest

ARG HOST_UID=1000
ARG HOST_GID=1000

# --- (1) Actualizar la lista de mirrors para descargas más rápidas ---
# Obtiene una lista de mirrors HTTPS de EE.UU. (muy confiables) y la activa.
RUN curl -s "https://archlinux.org/mirrorlist/?country=US&protocol=https&use_mirror_status=on" | \
    sed -e 's/^#Server/Server/' -e '/^#/d' > /etc/pacman.d/mirrorlist

# --- (2) Inicializar Pacman Keyring para evitar errores de firmas ---
RUN pacman-key --init && \
    pacman-key --populate archlinux

# --- (3) Instalación de Dependencias del Sistema ---
RUN pacman -Syu --noconfirm && \
    pacman -S --noconfirm \
    base-devel \
    sudo \
    curl \
    wget \
    git \
    unzip \
    libxml2 \
    sqlite \
    libzip \
    openssl \
    mariadb-clients \
    oniguruma \
    php \
    php-intl \
    php-gd

# --- (4) Activar extensiones de PHP necesarias para Laravel ---
RUN sed -i 's/^;extension=bcmath/extension=bcmath/' /etc/php/php.ini && \
    sed -i 's/^;extension=pdo_mysql/extension=pdo_mysql/' /etc/php/php.ini

# --- (4) Instalar NodeJS (Método Directo y a Prueba de Fallos) ---
ENV NODE_VERSION=20.11.1
RUN curl -L "https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" \
    -o /tmp/node.tar.xz && \
    tar -xJf /tmp/node.tar.xz -C /usr/local/lib && \
    rm /tmp/node.tar.xz && \
    ln -s /usr/local/lib/node-v${NODE_VERSION}-linux-x64/bin/node /usr/local/bin/node && \
    ln -s /usr/local/lib/node-v${NODE_VERSION}-linux-x64/bin/npm /usr/local/bin/npm && \
    ln -s /usr/local/lib/node-v${NODE_VERSION}-linux-x64/bin/npx /usr/local/bin/npx

# --- (6) Instalar Composer y Laravel Installer ---
ENV COMPOSER_VERSION 2.8.12
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    --version=${COMPOSER_VERSION}
RUN composer global require "laravel/installer:^5.0"
ENV PATH="/root/.composer/vendor/bin:$PATH"

# --- (7) Creación del Usuario Final ---
RUN groupadd -g $HOST_GID developer && \
    useradd -u $HOST_UID -g $HOST_GID -m -s /bin/bash developer
RUN echo "developer ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# --- (8) Configuración Final ---
USER developer
WORKDIR /home/developer/project
CMD ["/bin/bash"]