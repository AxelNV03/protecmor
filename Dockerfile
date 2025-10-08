# ==============================================================================
# Dockerfile para Entorno de Desarrollo Laravel en Arch Linux (Versión Final Optimizada)
# ==============================================================================
FROM archlinux:latest

# --- ARGs para UID/GID del host ---
ARG HOST_UID=1000
ARG HOST_GID=1000

# --- Inicializar Keyring ---
RUN pacman-key --init && \
    pacman-key --populate archlinux

# --- Configuración de Mirrors ---
COPY docker/mirrorlist /etc/pacman.d/mirrorlist

# --- Actualizar e instalar dependencias de sistema en una sola capa ---
RUN pacman -Syyu --noconfirm && \
    pacman -S --noconfirm \
    base-devel sudo curl wget zsh starship git unzip libxml2 tzdata sqlite libzip openssl openssh \
    mariadb-clients oniguruma php php-intl php-gd 

# --- Activar extensiones de PHP necesarias para Laravel ---
RUN sed -i 's/^;extension=bcmath/extension=bcmath/' /etc/php/php.ini && \
    sed -i 's/^;extension=pdo_mysql/extension=pdo_mysql/' /etc/php/php.ini

# --- Instalar NodeJS ---
ENV NODE_VERSION=24.8.0
RUN curl -L "https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" -o /tmp/node.tar.xz && \
    tar -xJf /tmp/node.tar.xz -C /usr/local/lib && \
    rm /tmp/node.tar.xz && \
    ln -s /usr/local/lib/node-v${NODE_VERSION}-linux-x64/bin/node /usr/local/bin/node && \
    ln -s /usr/local/lib/node-v${NODE_VERSION}-linux-x64/bin/npm /usr/local/bin/npm && \
    ln -s /usr/local/lib/node-v${NODE_VERSION}-linux-x64/bin/npx /usr/local/bin/npx

# --- Instalar Composer y Laravel Installer ---
ENV COMPOSER_VERSION=2.8.12
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} && \
    composer global require "laravel/installer:^5.0"
ENV PATH="/home/developer/.config/composer/vendor/bin:$PATH"


# --- Crear usuario developer ---
RUN groupadd -g $HOST_GID developer && \
    useradd -u $HOST_UID -g $HOST_GID -m -s /usr/bin/zsh developer && \
    echo "developer ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# --- Configurar zsh ---
# Clonar plugins zsh
RUN git clone https://github.com/zsh-users/zsh-autosuggestions /usr/share/zsh/plugins/zsh-autosuggestions && \
    git clone https://github.com/zsh-users/zsh-syntax-highlighting /usr/share/zsh/plugins/zsh-syntax-highlighting

# Copiar archivos de configuración
RUN mkdir -p /home/developer/.config/{zsh,dircolors}/

COPY docker/estadia.zsh /home/developer/.config/zsh/estadia.zsh
COPY docker/alias.zsh /home/developer/.config/zsh/alias.zsh
COPY docker/functions.zsh /home/developer/.config/zsh/functions.zsh
COPY docker/zsh-file.zsh /home/developer/.zshrc
COPY docker/starship.toml /home/developer/.config/starship.toml
COPY docker/gruvbox-rainbow.dircolors /home/developer/.config/dircolors/gruvbox-rainbow.dircolors
COPY docker/gitconf /home/developer/.gitconfig
COPY docker/entorno /home/developer/project/.env

# --- Ajustar permisos ---
RUN chown -R developer:developer /home/developer && \
    chsh -s /usr/bin/zsh developer

# Configurar zona horaria
RUN ln -sf /usr/share/zoneinfo/America/Mexico_City /etc/localtime && \
    echo "America/Mexico_City" > /etc/timezone

# --- Final: usuario, directorio y shell ---
USER developer
WORKDIR /home/developer/project
SHELL ["/usr/bin/zsh", "-c"]
CMD ["/usr/bin/zsh"]

