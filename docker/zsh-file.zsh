# Configuración de Zsh

# Cargar alias.zsh desde el mismo directorio
source "$HOME/.config/zsh/alias.zsh"

# Cargar el archivo de funciones
source "$HOME/.config/zsh/functions.zsh"

# Cargar las funciones y alias de estadia.zsh
source "$HOME/.config/zsh/estadia.zsh"

# Arranca Starship
eval "$(starship init zsh)"

# Carga los colores de dircolors
eval "$(dircolors -b ~/.config/dircolors/gruvbox-rainbow.dircolors)"

# Cargar plugins de Zsh
source /usr/share/zsh/plugins/zsh-autosuggestions/zsh-autosuggestions.zsh
source /usr/share/zsh/plugins/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh