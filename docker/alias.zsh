# Archivo de alias para Zsh
# Este archivo se carga al iniciar Zsh

alias ls='ls --color=always'            # Listar archivos con colores
alias grep='grep --color=always'        # Resaltar coincidencias en grep
alias cl='clear'                        # Limpiar la terminal
alias ..='cd ..'                        # Subir un nivel en el sistema de archivos
alias ...='cd ../..'                    # Subir dos niveles en el sistema de archivos
alias ....='cd ../../..'                # Subir tres niveles en el sistema de archivos
alias ex='exit'                         # Salir de la terminal
alias system='sudo systemctl'           # Alias para systemctl
alias gl='git --no-pager log --oneline --graph --decorate --all'  # Mejor vista de git log
alias c='wl-copy' # Copiar al portapapeles
alias rl='source ~/.zshrc' # Recargar configuración de zsh